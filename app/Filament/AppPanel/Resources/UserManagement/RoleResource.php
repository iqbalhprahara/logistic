<?php

namespace App\Filament\AppPanel\Resources\UserManagement;

use App\Dto\Role\UpdateRoleDto;
use App\Filament\AppPanel\Resources\UserManagement\RoleResource\Pages;
use App\Services\RoleService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $modelLabel = 'Roles & Permissions';

    protected static ?string $pluralModelLabel = 'Roles & Permissions';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected const DISABLE_MODIFICATION_FOR_ROLES = [
        'Super Admin',
        'Client',
    ];

    public static function canViewAny(): bool
    {
        return auth()->user()->can('user-management:role');
    }

    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
                Forms\Components\Fieldset::make('Role')
                    ->schema([
                        Forms\Components\TextInput::make('name')->string()->required()->unique(ignoreRecord: true)->autofocus(),
                    ]),
                Forms\Components\Fieldset::make('Permissions')
                    ->schema([
                        ...self::assignPermissionForm(config('menu.items.app_panel', [])),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable()->forceSearchCaseInsensitive(false),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('users_count')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('permissions')
                    ->multiple()
                    ->options(static::buildPermissionsTitleOption(config('menu.items.app_panel')))
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['values'], function (Builder $query, $permissions) {
                            $query->where(function (Builder $query) use ($permissions) {
                                $query->where('name', 'Super Admin');
                                $query->orWhereHas('permissions', fn ($permission) => $permission->select(DB::raw(1))->whereIn('permissions.name', $permissions)->where('permissions.guard_name', 'web'));
                            });
                        });
                    })
                    ->indicateUsing(function (array $data) {
                        $permissions = Arr::collapse(static::buildPermissionsTitleOption(config('menu.items.app_panel')));
                        $indicator = [];
                        foreach ($data['values'] as $selected) {
                            if (isset($permissions[$selected])) {
                                $indicator[] = $permissions[$selected];
                            }
                        }

                        if (empty($indicator)) {
                            return null;
                        }

                        return 'Permissions : '.implode(', ', $indicator);
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->hidden(fn (Role $record) => self::disabledForModification($record))->authorize('user-management:role:update')
                        ->mutateRecordDataUsing(function (Role $record, array $data): array {
                            $data = array_merge($data, self::buildPermissionsData(config('menu.items.app_panel', []), $record));

                            return $data;
                        })
                        ->mutateFormDataUsing(function (array $data) {
                            $data['permissions'] = Arr::collapse(Arr::except($data, 'name'));

                            return $data;
                        })
                        ->using(fn (Role $record, RoleService $roleService, array $data) => DB::transaction(fn () => $roleService->updateRole(UpdateRoleDto::from(array_merge($record->toArray(), $data))))),
                    Tables\Actions\DeleteAction::make()->hidden(fn (Role $record) => self::disabledForModification($record) || self::disabledForDelete($record))->authorize('user-management:role:delete'),
                ])->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->authorize('user-management:role:delete'),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn ($record): bool => ! self::disabledForModification($record) && ! self::disabledForDelete($record),
            );
    }

    private static function buildPermissionsTitleOption($menus, array $permissions = []): array
    {
        foreach ($menus as $menu) {
            if (isset($menu['gate']) && $menu['type'] !== 'group') {
                $permissions[$menu['name']][$menu['gate']] = 'View '.$menu['name'];
            }

            if (isset($menu['permissions']) && is_array($menu['permissions'])) {
                foreach ($menu['permissions'] as $permission) {
                    if (isset($permission['gate'])) {
                        $permissions[$menu['name']][$permission['gate']] = $permission['title'];
                    }
                }
            }

            if (isset($menu['submenus']) && is_array($menu['submenus'])) {
                $permissions = static::buildPermissionsTitleOption($menu['submenus'], $permissions);
            }
        }

        return $permissions;
    }

    protected static function buildPermissionsData(array $menus, Role $record): array
    {
        $permissions = [];

        foreach ($menus as $menu) {
            if (! isset($menu['gate']) && $menu['type'] !== 'group') {
                continue;
            }

            if ($menu['type'] === 'group') {
                $permissions = array_merge($permissions, self::buildPermissionsData($menu['submenus'], $record));
            } else {
                if (isset($menu['gate']) && $record->hasPermissionTo($menu['gate'])) {
                    $permissions[$menu['name']][] = $menu['gate'];
                }

                foreach ($menu['permissions'] ?? [] as $permission) {
                    if ($record->hasPermissionTo($permission['gate'])) {
                        $permissions[$menu['name']][] = $permission['gate'];
                    }
                }
            }
        }

        return $permissions;
    }

    protected static function assignPermissionForm(array $menus): array
    {
        $form = [];

        foreach ($menus as $menu) {
            if (! isset($menu['gate']) && $menu['type'] !== 'group') {
                continue;
            }

            if ($menu['type'] === 'group') {
                $submenuSchema = self::assignPermissionForm($menu['submenus'] ?? []);

                if (! empty($submenuSchema)) {
                    $form[] = Forms\Components\Section::make($menu['name'])
                        ->description('Setup '.strtolower($menu['name']).' permissions')
                        ->collapsed()
                        ->compact()
                        ->columns(2)
                        ->schema([
                            ...$submenuSchema,
                        ]);
                }
            } else {
                $options = [];
                $descriptions = [];
                if (isset($menu['gate'])) {
                    $options[$menu['gate']] = 'View';
                    $descriptions[$menu['gate']] = $menu['description'];
                }

                $options = $options + collect($menu['permissions'])->mapWithKeys(function ($item) {
                    return [$item['gate'] => $item['title']];
                })->toArray();

                $descriptions = $descriptions + collect($menu['permissions'])->mapWithKeys(function ($item) {
                    return [$item['gate'] => $item['description']];
                })->toArray();

                if (! empty($options)) {
                    $form[] = Forms\Components\CheckboxList::make($menu['name'])
                        ->options([
                            ...$options,
                        ])
                        ->descriptions([
                            ...$descriptions,
                        ])
                        // ->exists(Permission::class, 'name', modifyRuleUsing: fn (Exists $rule) => $rule->where('guard_name', 'web'))
                        ->bulkToggleable();
                }
            }
        }

        return $form;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('users');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }

    public static function disabledForModification(Role $record): bool
    {
        return in_array($record->name, self::DISABLE_MODIFICATION_FOR_ROLES);
    }

    public static function disabledForDelete(Role $record): bool
    {
        return $record->users_count > 0;
    }
}
