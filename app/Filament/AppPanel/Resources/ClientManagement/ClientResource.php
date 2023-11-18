<?php

namespace App\Filament\AppPanel\Resources\ClientManagement;

use App\Dto\User\UpdateClientUserDto;
use App\Filament\AppPanel\Resources\ClientManagement\ClientResources\Pages;
use App\Models\Auth\User;
use App\Models\MasterData\Client;
use App\Models\MasterData\Company;
use App\Services\UserService;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $modelLabel = 'client';

    protected static ?string $pluralModelLabel = 'clients';

    protected static ?string $navigationLabel = 'Client';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('client-management:client');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('User Data')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxLength(100)
                            ->extraInputAttributes(['maxlength' => 100])
                            ->string()
                            ->required()
                            ->placeholder('John Doe')
                            ->autofocus(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->rule('email:rfc,dns,spoof,filter')
                            ->required()
                            ->unique(table: 'users', ignoreRecord: true, ignorable: function (Client $record = null): ?User {
                                return $record?->user;
                            })
                            ->placeholder('john.doe@mail.com'),
                        Forms\Components\Select::make('company')
                            ->options(Company::select(DB::raw("concat(code, ' - ', name) as text"), 'uuid as value')->pluck('text', 'value'))
                            ->exists(Company::class, 'uuid')
                            ->dehydrateStateUsing(fn ($state) => Company::find($state))
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->placeholder('Select client company'),
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
            ->defaultGroup('company')
            ->groups([
                Tables\Grouping\Group::make('company')
                    ->getTitleFromRecordUsing(fn (Client $record): string => $record->company->code)
                    ->getDescriptionFromRecordUsing(fn (Client $record): string => "Client in {$record->company->name}")
                    ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy(DB::raw('(select name from companies where companies.uuid = clients.company_uuid)'), $direction)),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('uuid')->label('Client Uuid')->searchable()->sortable()->toggleable()->copyable(),
                Tables\Columns\TextColumn::make('user.uuid')->label('User Uuid')->searchable()->sortable()->toggleable()->toggledHiddenByDefault()->copyable(),
                Tables\Columns\TextColumn::make('user.name')->label('Name')->searchable()->sortable()->toggleable()->copyable(),
                Tables\Columns\TextColumn::make('user.email')->label('Email')->searchable()->sortable()->toggleable()->copyable(),
                BadgeableColumn::make('company.name')
                    ->asPills()
                    ->label('Company')
                    ->icon('heroicon-o-building-office')
                    ->prefixBadges([
                        Badge::make('company.code')
                            ->label(fn (Client $record) => $record->company->code)
                            ->color('primary'),
                    ])
                    ->searchable()->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->multiple()
                    ->relationship('company', 'name', fn (Builder $query) => $query->orderBy('name'))
                    ->getOptionLabelFromRecordUsing(fn (Company $record) => "{$record->code} - {$record->name}")
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->authorize('client-management:client:update')
                        ->mutateRecordDataUsing(function (Client $record, array $data): array {
                            $data['name'] = $record->user->name;
                            $data['email'] = $record->user->email;
                            $data['company'] = $record->company_uuid;

                            return $data;
                        })
                        ->using(fn (Client $record, UserService $userService, array $data) => DB::transaction(fn () => $userService->updateClientUser(UpdateClientUserDto::from(array_merge(['uuid' => $record->user_uuid], $data))))),
                    Tables\Actions\RestoreAction::make()->hidden(fn (Client $record) => is_null($record->deleted_at))->authorize('client-management:client:restore'),
                    Tables\Actions\DeleteAction::make()->hidden(fn (Client $record) => ! is_null($record->deleted_at))->authorize('client-management:client:delete'),
                ])->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()->authorize('client-management:client:restore'),
                    Tables\Actions\DeleteBulkAction::make()->authorize('client-management:client:delete'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'user',
            'company',
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageClients::route('/'),
        ];
    }
}
