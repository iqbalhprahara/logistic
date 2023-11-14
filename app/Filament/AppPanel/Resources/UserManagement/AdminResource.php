<?php

namespace App\Filament\AppPanel\Resources\UserManagement;

use App\Dto\User\UpdateAdminUserDto;
use App\Filament\AppPanel\Resources\UserManagement\AdminResources\Pages;
use App\Models\Auth\User;
use App\Services\UserService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'admin';

    protected static ?string $pluralModelLabel = 'admins';

    protected static ?string $navigationLabel = 'Admin';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected const DISABLE_MODIFICATION_FOR_ADMIN = [
        'admin@banana-xpress.com',
    ];

    public static function canViewAny(): bool
    {
        return auth()->user()->can('user-management:admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Admin Data')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->string()
                            ->required()
                            ->maxLength(100)
                            ->extraInputAttributes(['maxlength' => 100])
                            ->placeholder('John Doe')
                            ->autofocus(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->rule('email:rfc,dns,spoof,filter')
                            ->required()
                            ->placeholder('john.doe@mail.com'),
                        Forms\Components\Select::make('role')
                            ->options(Role::whereNot('name', 'Client')->pluck('name', 'id'))
                            ->required()
                            ->exists(Role::class, 'id', modifyRuleUsing: fn (Exists $rule) => $rule->whereNot('name', 'Client'))
                            ->dehydrateStateUsing(fn ($state): ?Role => Role::find($state))
                            ->native(false)
                            ->searchable()
                            ->placeholder('Select admin role'),
                    ]),
                Forms\Components\Fieldset::make('Password')
                    ->schema([
                        Forms\Components\Toggle::make('change_password')->hiddenOn('create')->default(true)->reactive()->columnSpanFull(),
                        Forms\Components\TextInput::make('password')
                            ->hidden(fn (Get $get) => $get('change_password') === false)
                            ->disabled(fn (Get $get) => $get('change_password') === false)
                            ->label('New Password')
                            ->validationAttribute('password')
                            ->password()
                            ->rule(Password::defaults())
                            ->confirmed()
                            ->required()
                            ->reactive(),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->hidden(fn (Get $get) => $get('change_password') === false)
                            ->disabled(fn (Get $get) => $get('change_password') === false)
                            ->label('Confirm Password')
                            ->password()
                            ->reactive(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('role')
            ->groups([
                Tables\Grouping\Group::make('role')
                    ->getTitleFromRecordUsing(fn (User $record): string => $record->roles->first()?->name)
                    ->getDescriptionFromRecordUsing(fn (User $record): string => "User with role {$record->roles->first()?->name}")
                    ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy(DB::raw("(select name from roles join model_has_roles on roles.id = model_has_roles.role_id where model_has_roles.model_type = 'auth.user' and model_has_roles.model_id = users.uuid)"), $direction)),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('uuid')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('role')->getStateUsing(fn (User $record) => $record->roles->first()?->name),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->multiple()
                    ->relationship('roles', 'name', modifyQueryUsing: fn (Builder $query) => $query->where('name', '!=', 'Client'))
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn (User $record) => self::disabledForModification($record))->authorize('user-management:admin:update')
                    ->mutateRecordDataUsing(function (User $record, array $data): array {
                        $data['role'] = $record->roles()->value('id');

                        return $data;
                    })
                    ->using(fn (User $record, UserService $userService, array $data) => DB::transaction(fn () => $userService->updateAdminUser(UpdateAdminUserDto::from(array_merge($record->toArray(), $data))))),
                Tables\Actions\RestoreAction::make()->hidden(fn (User $record) => self::disabledForModification($record) || is_null($record->deleted_at))->authorize('user-management:admin:restore'),
                Tables\Actions\DeleteAction::make()->hidden(fn (User $record) => self::disabledForModification($record) || ! is_null($record->deleted_at))->authorize('user-management:admin:delete'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()->authorize('user-management:admin:restore'),
                    Tables\Actions\DeleteBulkAction::make()->authorize('user-management:admin:delete')->modalHeading('Deactivate selected admins')->successNotificationTitle('Deactivated'),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn ($record): bool => ! self::disabledForModification($record),
            );
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereDoesntHave('roles', function ($roles) {
            $roles->whereName('Client');
        })
            ->with([
                'roles',
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAdmins::route('/'),
        ];
    }

    protected static function disabledForModification($record): bool
    {
        return in_array($record->email, self::DISABLE_MODIFICATION_FOR_ADMIN);
    }
}
