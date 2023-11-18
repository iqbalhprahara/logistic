<?php

namespace App\Filament\AppPanel\Resources\Logistic;

use App\Dto\Awb\InputAwbStatusDto;
use App\Dto\Awb\UpdateAwbDto;
use App\Enums\AwbSource;
use App\Filament\AppPanel\Resources\Logistic\InputPickupResources\Pages;
use App\Models\Logistic\Awb;
use App\Models\MasterData\AwbStatus;
use App\Models\MasterData\City;
use App\Models\MasterData\Client;
use App\Models\MasterData\Packing;
use App\Models\MasterData\Province;
use App\Models\MasterData\Service;
use App\Models\MasterData\ShipmentType;
use App\Models\MasterData\Subdistrict;
use App\Models\MasterData\Transportation;
use App\Services\AwbService;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Exists;

class InputPickupResource extends Resource
{
    protected static ?string $model = Awb::class;

    protected static ?string $modelLabel = 'Input Pickup';

    protected static ?string $pluralModelLabel = 'Input Pickup';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('logistic:input-pickup');
    }

    public static function form(Form $form): Form
    {
        return $form->columns([
            'default' => 1,
            'lg' => 2,
        ])
            ->schema([
                self::awbInformationSchema(),
                self::originDetailSchema(),
                self::destinationDetailSchema(),
                self::serviceSchema(),
                self::packageSchema(),
            ]);
    }

    public static function getTableGroups(bool $isClient = false): array
    {
        $groups = [
            Tables\Grouping\Group::make('created_at')
                ->label('Tanggal Input')
                ->date(),
        ];

        if (! $isClient) {
            $groups[] = Tables\Grouping\Group::make('created_by')
                ->label('Diinput Oleh')
                ->getTitleFromRecordUsing(fn (Awb $record) => $record->createdBy->name)
                ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy(DB::raw('(select name from users where users.uuid = awbs.created_by)'), $direction));
        }
        $groups = array_merge($groups, [
            Tables\Grouping\Group::make('awb_status_id')
                ->label('Status')
                ->getTitleFromRecordUsing(fn (Awb $record) => $record->awbStatus->name)
                ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy(DB::raw('(select name from awb_statuses where awb_statuses.id = awbs.awb_status_id)'), $direction)),
        ]);

        if (! $isClient) {
            $groups[] = Tables\Grouping\Group::make('client_uuid')
                ->label('Client')
                ->getTitleFromRecordUsing(fn (Awb $record) => $record->client->company->name)
                ->getDescriptionFromRecordUsing(fn (Awb $record) => $record->client->user->name)
                ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy(DB::raw('(select name from clients join users on clients.user_uuid = users.uuid where clients.uuid = awbs.client_uuid)'), $direction));
        }

        $groups = array_merge($groups, [
            Tables\Grouping\Group::make('originCity.code')
                ->label('Asal')
                ->getTitleFromRecordUsing(fn (Awb $record) => $record->originCity->code)
                ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy(DB::raw('(select distinct code from cities where cities.id = awbs.origin_city_id)'), $direction)),
            Tables\Grouping\Group::make('destinationCity.code')
                ->label('Tujuan')
                ->getTitleFromRecordUsing(fn (Awb $record) => $record->destinationCity->code)
                ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy(DB::raw('(select distinct code from cities where cities.id = awbs.destination_city_id)'), $direction)),
            Tables\Grouping\Group::make('transportation_id')
                ->label('Transportasi Pickup'),
            Tables\Grouping\Group::make('shipping_type_id')
                ->label('Jenis Kiriman'),
            Tables\Grouping\Group::make('service_id')
                ->label('Jenis Layanan'),
            Tables\Grouping\Group::make('packing_id')
                ->getTitleFromRecordUsing(fn (Awb $record) => $record->packing_id ?? 'Tanpa tambahan packing')
                ->label('Tambahan Packing'),
        ]);

        return $groups;
    }

    public static function getTableFilters(bool $isClient = false): array
    {
        $filters = [
            Tables\Filters\Filter::make('created_at')
                ->form([
                    Forms\Components\Fieldset::make('Tanggal Input')
                        ->schema([
                            Forms\Components\DatePicker::make('created_from')->label('Dari')->prefixIcon('heroicon-o-calendar-days')->native(false)->maxDate(function (Get $get) {
                                if ($get('created_until')) {
                                    return Carbon::parse($get('created_until'));
                                }
                            }),
                            Forms\Components\DatePicker::make('created_until')->label('s/d')->prefixIcon('heroicon-o-calendar-days')->native(false)->maxDate(now())->minDate(function (Get $get) {
                                if ($get('created_from')) {
                                    return Carbon::parse($get('created_from'));
                                }
                            }),
                        ])
                        ->columns(2),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            optional($data)['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            optional($data)['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data) {
                    $indicators = [];

                    if ($data['created_from']) {
                        $indicators[] = Indicator::make('Diinput dari '.Carbon::parse($data['created_from'])->toFormattedDateString())->removeField('created_from');
                    }

                    if ($data['created_until']) {
                        $indicators[] = Indicator::make('Diinput sampai '.Carbon::parse($data['created_until'])->toFormattedDateString())->removeField('created_until');
                    }

                    return $indicators;
                })
                ->columnSpanFull(),
        ];

        if (! $isClient) {
            $filters[] = Tables\Filters\SelectFilter::make('created_by')
                ->label('Diinput Oleh')
                ->relationship('createdBy', 'name')
                ->searchable()
                ->preload();

        }
        $filters = array_merge($filters, [
            Tables\Filters\SelectFilter::make('source')
                ->options(AwbSource::asOptions())
                ->native(false),
            Tables\Filters\SelectFilter::make('awb_status_id')
                ->multiple()
                ->preload()
                ->native(false)
                ->label('Status')
                ->relationship('awbStatus', 'name'),
        ]);

        if (! $isClient) {
            $filters[] = Tables\Filters\SelectFilter::make('client')
                ->relationship('client.user', 'name', modifyQueryUsing: fn (Builder $query) => $query->client())
                ->searchable()
                ->preload();
        }

        $filters = array_merge($filters, [
            Tables\Filters\SelectFilter::make('origin_city_id')
                ->label('Asal')
                ->searchable()
                ->getSearchResultsUsing(function (string $search): array {
                    return static::cityFilterOptions($search);
                }),
            Tables\Filters\SelectFilter::make('destination_city_id')
                ->label('Tujuan')
                ->searchable()
                ->getSearchResultsUsing(function (string $search): array {
                    return static::cityFilterOptions($search);
                }),
            Tables\Filters\SelectFilter::make('transportation_id')
                ->label('Transportasi Pickup')
                ->multiple()
                ->options(Transportation::pluck('name', 'id')),
            Tables\Filters\SelectFilter::make('shipment_type_id')
                ->label('Jenis Kiriman')
                ->multiple()
                ->options(ShipmentType::pluck('name', 'id')),
            Tables\Filters\SelectFilter::make('service_id')
                ->label('Jenis Layanan')
                ->multiple()
                ->options(Service::pluck('id', 'id')),
            Tables\Filters\SelectFilter::make('packing_id')
                ->label('Packing')
                ->multiple()
                ->options(array_merge(['no_packing' => 'Tanpa tambahan packing'], Packing::pluck('name', 'id')->toArray()))
                ->query(function (array $data, Builder $query): Builder {
                    if (empty($data['values'])) {
                        return $query;
                    }

                    return $query->where(function (Builder $query) use ($data) {
                        $packings = Arr::where($data['values'], fn ($item) => $item !== 'no_packing');
                        $query->whereIn('packing_id', $packings);

                        if (in_array('no_packing', $data['values'])) {
                            $query->orWhereNull('packing_id');
                        }
                    });
                }),
            Tables\Filters\Filter::make('is_cod')
                ->label('COD')
                ->toggle(),
            Tables\Filters\Filter::make('is_insurance')
                ->label('Asuransi')
                ->toggle(),
        ]);

        return $filters;
    }

    public static function cityFilterOptions(string $search): array
    {
        if (empty($search)) {
            return [];
        }

        $keywords = array_values(explode(' ', $search));
        $searchables = ['type', 'name', 'code'];

        $query = City::select([DB::raw("concat(type, ' - ', name, ' (', code, ')') as text"), 'id as value']);

        foreach ($keywords as $keyword) {
            $query->where(function ($query) use ($searchables, $keyword) {
                foreach ($searchables as $column) {
                    $query->orWhere(DB::raw("lower({$column} :: text) :: text"), 'like', '%'.$keyword.'%');
                }
            });
        }

        return $query->pluck('text', 'value')->toArray();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup(null)
            ->groups(static::getTableGroups(auth()->user()->isClient()))
            ->defaultSort('uuid', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('source')
                    ->badge()
                    ->color(fn (Awb $record) => $record->source->color())
                    ->sortable(),
                BadgeableColumn::make('awb_no')
                    ->asPills()
                    ->label('No. AWB / No. Referensi')
                    ->description(fn (Awb $record) => $record->ref_no ?? 'Tidak ada nomor referensi')
                    ->suffixBadges([
                        Badge::make('awb_status')
                            ->label(fn (Awb $record) => $record->awbStatus->name)
                            ->color(fn (Awb $record) => $record->getStatusColor()),
                    ])
                    ->weight(FontWeight::Bold)
                    ->searchable(query: function (Builder $query, string $search) {
                        return $query->where('awb_no', 'like', '%'.$search.'%')
                            ->orWhere('ref_no', 'like', '%'.$search.'%');
                    })
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->orderBy('awb_no', $direction)
                            ->orderBy('ref_no', $direction);
                    }),
                Tables\Columns\TextColumn::make('client.company.name')
                    ->icon('heroicon-o-building-office')
                    ->label('Client')
                    ->weight(FontWeight::Bold)
                    ->description(fn (Awb $record) => $record->client->user->name)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRelation('client.user', DB::raw('lower(name)'), 'like', '%'.strtolower($search).'%')
                            ->orWhereRelation('client.company', DB::raw('lower(name)'), 'like', '%'.strtolower($search).'%');
                    })
                    ->hidden(fn (): bool => auth()->user()->isClient()),
                BadgeableColumn::make('origin_contact_name')
                    ->asPills()
                    ->label('Pengirim')
                    ->description(fn (Awb $record) => $record->origin_address_line1)
                    ->suffixBadges([
                        Badge::make('awb_status')
                            ->label(fn (Awb $record) => $record->originCity->code)
                            ->color('primary'),
                    ])
                    ->weight(FontWeight::Bold)
                    ->extraHeaderAttributes(['class' => 'min-w-[200px]'], true)
                    ->extraCellAttributes(['class' => 'break-all'], true)
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                BadgeableColumn::make('destination_contact_name')
                    ->asPills()
                    ->label('Penerima')
                    ->description(fn (Awb $record) => $record->destination_address_line1)
                    ->suffixBadges([
                        Badge::make('awb_status')
                            ->label(fn (Awb $record) => $record->destinationCity->code)
                            ->color('primary'),
                    ])
                    ->weight(FontWeight::Bold)
                    ->extraHeaderAttributes(['class' => 'min-w-[200px]'], true)
                    ->extraCellAttributes(['class' => 'break-all'], true)
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('transportation_id')
                    ->label('Transportasi Pickup')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('shipment_type_id')
                    ->label('Jenis Kiriman')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('service_id')
                    ->label('Jenis Layanan')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('packing_id')
                    ->label('Tambahan Packing')
                    ->placeholder('Tanpa tambahan packing.')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\IconColumn::make('is_cod')
                    ->label('COD')
                    ->boolean()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\IconColumn::make('is_insurance')
                    ->label('Asuransi')
                    ->boolean()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('package_koli')
                    ->label('Jumlah Koli')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('package_weight')
                    ->label('Total Berat (Kg)')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: ',',
                        thousandsSeparator: '.',
                    )
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('package_length')
                    ->icon('heroicon-o-cube')
                    ->label('Dimensi (cm)')
                    ->getStateUsing(fn (Awb $record): string => $record->package_dimension)
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('package_value')
                    ->label('Nilai Barang')
                    ->placeholder('-')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('package_desc')
                    ->label('Deskripsi Barang')
                    ->extraHeaderAttributes(['class' => 'min-w-[200px]'], true)
                    ->extraCellAttributes(['class' => 'break-all'], true)
                    ->placeholder('-')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('package_instruction')
                    ->label('Intruksi Khusus')
                    ->extraHeaderAttributes(['class' => 'min-w-[200px]'], true)
                    ->extraCellAttributes(['class' => 'break-all'], true)
                    ->placeholder('-')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Input')
                    ->description(fn (Awb $record) => $record->createdBy->name)
                    ->weight(FontWeight::Bold)
                    ->dateTime()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters(static::getTableFilters(auth()->user()->isClient()))
            ->filtersFormWidth('lg')
            ->filtersFormMaxHeight('400px')
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('download-awb')
                        ->label('Download AWB')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn (Awb $record, AwbService $awbService) => $awbService->downloadAwb($record)),
                    Tables\Actions\ViewAction::make()
                        ->modalHeading('View AWB Info')
                        ->modalWidth('full')
                        ->slideOver()
                        ->extraModalFooterActions(fn (): array => [
                            Tables\Actions\ViewAction::make('download-awb-modal-view')
                                ->label('Download AWB')
                                ->icon('heroicon-o-document-arrow-down')
                                ->action(fn (Awb $record, AwbService $awbService) => $awbService->downloadAwb($record)),
                        ])
                        ->authorize('logistic:input-pickup'),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn (Awb $record) => self::disabledForModification($record) || ! is_null($record->deleted_at))
                        ->authorize('logistic:input-pickup:update')
                        ->modalHeading('Edit Pickup Info')
                        ->modalWidth('full')
                        ->slideOver()
                        ->using(fn (AwbService $service, Awb $record, array $data) => DB::transaction(fn () => DB::transaction(fn () => $service->update($record->uuid, UpdateAwbDto::from($data))))),
                    Tables\Actions\Action::make('view-status')
                        ->label('Status History')
                        ->modalHeading(fn (Awb $record): string => "Status History - {$record->awb_no}")
                        ->icon('heroicon-o-clipboard-document-check')
                        ->modalContent(function (Awb $record): View {
                            $record->load([
                                'awbStatusHistories',
                                'awbStatusHistories.awbStatus',
                            ]);

                            return view('filament.app-panel.pages.logistic.awb-status-history', ['histories' => $record->awbStatusHistories]);
                        })
                        ->modalSubmitAction(false)
                        ->modalCancelAction(false)
                        ->modalIcon('heroicon-o-clipboard-document-check')
                        ->authorize('logistic:input-pickup')
                        ->slideOver()
                        ->modalWidth('xs'),
                    Tables\Actions\Action::make('input-status')
                        ->label('Input Status')
                        ->modalHeading(fn (Awb $record): string => "Input Status #{$record->awb_no}")
                        ->modalWidth('sm')
                        ->icon('heroicon-o-check')
                        ->form([
                            Forms\Components\Placeholder::make('current_status')
                                ->label('Status saat ini')
                                ->content(fn (Awb $record): string => $record->awbStatus->name)
                                ->columnSpanFull(),
                            Forms\Components\Select::make('awb_status_id')
                                ->label('Status')
                                ->placeholder('Pilih Status')
                                ->options(fn (Awb $record) => AwbStatus::whereNot('id', $record->awb_status_id)->pluck('name', 'id'))
                                ->native(false)
                                ->required()
                                ->columnSpanFull(),
                            Forms\Components\DateTimePicker::make('status_at')
                                ->format(config('data.date_format'))
                                ->prefixIcon('heroicon-o-clock')
                                ->label('Waktu')
                                ->required()
                                ->timezone('Asia/Jakarta')
                                ->seconds(false)
                                ->default(now())
                                ->native(false)
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('note')
                                ->string()
                                ->nullable()
                                ->maxLength(150)
                                ->extraInputAttributes(['maxlength' => 150])
                                ->label('Catatan')
                                ->columnSpanFull(),
                        ])
                        ->action(function (Tables\Actions\Action $action, Awb $record, array $data, AwbService $service) {
                            try {
                                DB::transaction(fn () => $service->inputStatus(InputAwbStatusDto::from(array_merge($data, ['awb_uuid' => $record->uuid]))));
                                Notification::make()
                                    ->title('Saved')
                                    ->success()
                                    ->send();
                            } catch (\Throwable $t) {
                                Log::error($t);
                                Notification::make()
                                    ->title('Failed to save')
                                    ->danger()
                                    ->send();

                                $action->halt();
                            }
                        })
                        ->hidden(fn (Awb $record) => self::disabledForModification($record) || ! is_null($record->deleted_at))
                        ->authorize('logistic:input-pickup:input-status'),
                    Tables\Actions\DeleteAction::make()->hidden(fn (Awb $record) => self::disabledForModification($record) || ! is_null($record->deleted_at))->authorize('logistic:input-pickup:delete'),
                    Tables\Actions\RestoreAction::make()->hidden(fn (Awb $record) => self::disabledForModification($record) && is_null($record->deleted_at))->authorize('logistic:input-pickup:restore'),
                ])->iconButton(),
            ], position: ActionsPosition::AfterColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->authorize('logistic:input-pickup:delete'),
                    Tables\Actions\RestoreBulkAction::make()->label('Restore selected')->authorize('logistic:input-pickup:restore'),
                ]),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn ($record): bool => ! self::disabledForModification($record),
            );
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'client:uuid,user_uuid,company_uuid',
            'client.company:uuid,name',
            'client.user:uuid,name',
            'awbStatus:id,name',
            'originCity:id,code',
            'destinationCity:id,code',
            'createdBy:uuid,name',
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInputPickups::route('/'),
        ];
    }

    public static function disabledForModification(Awb $record): bool
    {
        return $record->isDelivered();
    }

    private static function awbInformationSchema(): mixed
    {
        return Forms\Components\Section::make('Informasi AWB')
            ->description('Informasi mengenai AWB')
            ->icon('heroicon-o-information-circle')
            ->schema([
                Forms\Components\Select::make('client_uuid')
                    ->label('Pelanggan')
                    ->validationAttribute('Pelanggan')
                    ->options([
                        ...Client::join('users', 'clients.user_uuid', '=', 'users.uuid')
                            ->join('companies', 'clients.company_uuid', '=', 'companies.uuid')
                            ->select([
                                DB::raw('concat(users.name, \' - \', companies.name) as text'),
                                'clients.uuid as value',
                            ])
                            ->pluck('text', 'value'),
                    ])
                    ->hidden(fn () => auth()->user()->isClient())
                    ->disabledOn('edit')
                    ->required()
                    ->exists(Client::class, 'uuid')
                    ->native(false)
                    ->searchable()
                    ->placeholder('Pilih Pelanggan')
                    ->columnSpan(1)
                    ->columnStart(1),
                Forms\Components\Placeholder::make('awb_no')
                    ->label('No. AWB')
                    ->content(fn (?Awb $record): ?string => $record?->awb_no ?? 'Auto Generated')
                    ->columnSpan(1)
                    ->columnStart(1),
                Forms\Components\TextInput::make('ref_no')
                    ->label('No. Ref')
                    ->validationAttribute('ref_no')
                    ->nullable()
                    ->string()
                    ->maxLength(Awb::REF_NO_MAX_LENGTH)
                    ->extraInputAttributes(['maxlength' => Awb::REF_NO_MAX_LENGTH]),
            ])
            ->columns([
                'default' => 1,
                'lg' => 2,
            ])
            ->collapsible()
            ->collapsed(false);
    }

    private static function originDetailSchema(): mixed
    {
        return Forms\Components\Section::make('Pengirim')
            ->description('Detail informasi mengenai pengirim')
            ->icon('heroicon-o-map-pin')
            ->schema([
                ...self::baseLocationSchema('origin'),
            ])
            ->columnSpan([
                'default' => 'full',
                'lg' => 1,
            ])
            ->columns([
                'default' => 1,
                'lg' => 2,
            ])
            ->collapsible()
            ->collapsed(false);
    }

    private static function destinationDetailSchema(): mixed
    {
        return Forms\Components\Section::make('Penerima')
            ->description('Detail informasi mengenai penerima')
            ->icon('heroicon-o-map-pin')
            ->schema([
                ...self::baseLocationSchema('destination'),
            ])
            ->columnSpan([
                'default' => 'full',
                'lg' => 1,
            ])
            ->columns([
                'default' => 1,
                'lg' => 2,
            ])
            ->collapsible()
            ->collapsed(false);
    }

    private static function baseLocationSchema($type): array
    {
        return [
            Forms\Components\Select::make($type.'_province_id')
                ->label('Provinsi')
                ->validationAttribute('Provinsi')
                ->required()
                ->native(false)
                ->searchable()
                ->options(Province::pluck('name', 'id'))
                ->placeholder('Pilih Provinsi')
                ->afterStateUpdated(function (Set $set, ?int $state, ?int $old) use ($type) {
                    if ($state !== $old) {
                        self::resetLocationSelection($set, $type, ['city_id', 'subdistrict_id']);
                    }
                })
                ->exists(Province::class, 'id')
                ->dehydrateStateUsing(fn ($state): int => intval($state))
                ->live()
                ->columnSpan([
                    'default' => 'full',
                    'lg' => 1,
                ]),
            Forms\Components\Select::make($type.'_city_id')
                ->label('Kota')
                ->validationAttribute('Kota')
                ->required()
                ->native(false)
                ->searchable()
                ->options(function (Get $get) use ($type) {
                    $provinceId = $get($type.'_province_id');
                    if (! $provinceId) {
                        return [];
                    }

                    return City::whereProvinceId($provinceId)->select(
                        DB::raw('concat(type, \' - \', name, \'(\', code, \')\') as name'),
                        'id',
                    )
                        ->pluck('name', 'id');
                })
                ->placeholder('Pilih Kota/Kabupaten')
                ->afterStateUpdated(function (Set $set, ?int $state, ?int $old) use ($type) {
                    if ($state !== $old) {
                        self::resetLocationSelection($set, $type, ['subdistrict_id']);
                    }
                })
                ->exists(City::class, 'id', modifyRuleUsing: fn (Exists $rule, Get $get) => $rule->where('province_id', $get($type.'_province_id')))
                ->dehydrateStateUsing(fn ($state): int => intval($state))
                ->live()
                ->columnSpan([
                    'default' => 'full',
                    'lg' => 1,
                ]),
            Forms\Components\Select::make($type.'_subdistrict_id')
                ->label('Kecamatan')
                ->validationAttribute('Kecamatan')
                ->required()
                ->native(false)
                ->searchable()
                ->options(function (Get $get) use ($type) {
                    $cityId = $get($type.'_city_id');
                    if (! $cityId) {
                        return [];
                    }

                    return Subdistrict::whereCityId($cityId)->pluck('name', 'id');
                })
                ->placeholder('Pilih Kecamatan')
                ->exists(Subdistrict::class, 'id', modifyRuleUsing: fn (Exists $rule, Get $get) => $rule->where('city_id', $get($type.'_city_id')))
                ->dehydrateStateUsing(fn ($state): int => intval($state))
                ->live()
                ->columnSpan([
                    'default' => 'full',
                    'lg' => 1,
                ]),
            Forms\Components\TextInput::make($type.'_zipcode')
                ->label('Kode Pos')
                ->validationAttribute('Kode Pos')
                ->required()
                ->mask('99999')
                ->length(Awb::ZIPCODE_DIGIT)
                ->maxLength(Awb::ZIPCODE_DIGIT)
                ->extraInputAttributes(['maxlength' => Awb::ZIPCODE_DIGIT, 'minlength' => Awb::ZIPCODE_DIGIT])
                ->placeholder('misal: 15810')
                ->columnSpan([
                    'default' => 'full',
                    'lg' => 1,
                ]),
            Forms\Components\Textarea::make($type.'_address_line1')
                ->label('Alamat 1')
                ->validationAttribute('Alamat 1')
                ->required()
                ->string()
                ->maxLength(Awb::ADDRESS_MAX_LENGTH)
                ->extraInputAttributes(['maxlength' => Awb::ADDRESS_MAX_LENGTH])
                ->columnSpan('full'),
            Forms\Components\Textarea::make($type.'_address_line2')
                ->label('Alamat 2')
                ->validationAttribute('Alamat 2')
                ->string()
                ->maxLength(Awb::ADDRESS_MAX_LENGTH)
                ->extraInputAttributes(['maxlength' => Awb::ADDRESS_MAX_LENGTH])
                ->columnSpan('full'),
            Forms\Components\TextInput::make($type.'_contact_name')
                ->label('Nama '.($type === 'origin' ? 'Pengirim' : 'Penerima'))
                ->validationAttribute('Nama '.($type === 'origin' ? 'Pengirim' : 'Penerima'))
                ->required()
                ->string()
                ->maxLength(Awb::CONTACT_NAME_MAX_LENGTH)
                ->extraInputAttributes(['maxlength' => Awb::CONTACT_NAME_MAX_LENGTH])
                ->columnSpan([
                    'default' => 'full',
                    'lg' => 1,
                ]),
            Forms\Components\TextInput::make($type.'_contact_phone')
                ->label('Telepon')
                ->validationAttribute('Telepon')
                ->required()
                ->tel()
                ->minLength(Awb::PHONE_MIN_DIGIT)
                ->maxLength(Awb::PHONE_MAX_DIGIT)
                ->extraInputAttributes(['maxlength' => Awb::PHONE_MAX_DIGIT, 'minlength' => Awb::PHONE_MIN_DIGIT])
                ->mask('9999999999999')
                ->columnSpan([
                    'default' => 'full',
                    'lg' => 1,
                ])
                ->columnStart([
                    'lg' => 1,
                ]),
            Forms\Components\TextInput::make($type.'_contact_alt_phone')
                ->label('Telepon Alternatif')
                ->validationAttribute('Telepon Alternatif')
                ->tel()
                ->minLength(Awb::PHONE_MIN_DIGIT)
                ->maxLength(Awb::PHONE_MAX_DIGIT)
                ->extraInputAttributes(['maxlength' => Awb::PHONE_MAX_DIGIT, 'minlength' => Awb::PHONE_MIN_DIGIT])
                ->mask('9999999999999')
                ->columnSpan([
                    'default' => 'full',
                    'lg' => 1,
                ]),
        ];
    }

    private static function resetLocationSelection(Set $set, $type, array $fields = []): void
    {
        foreach ($fields as $field) {
            $set($type.'_'.$field, null);
        }
    }

    public static function serviceSchema(): mixed
    {
        return Forms\Components\Section::make('Layanan')
            ->description('Detail layanan pengiriman')
            ->icon('heroicon-o-truck')
            ->schema([
                Forms\Components\Select::make('transportation_id')
                    ->label('Transportasi Pickup')
                    ->validationAttribute('Transportasi Pickup')
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->options(Transportation::pluck('name', 'id'))
                    ->default(Transportation::value('id'))
                    ->exists(Transportation::class, 'id')
                    ->placeholder('Pilih Transportasi')
                    ->columnSpan([
                        'default' => 'full',
                        'lg' => 1,
                    ]),
                Forms\Components\Select::make('shipment_type_id')
                    ->label('Jenis Kiriman')
                    ->validationAttribute('Jenis Kiriman')
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->options(ShipmentType::pluck('name', 'id'))
                    ->default(ShipmentType::value('id'))
                    ->exists(ShipmentType::class, 'id')
                    ->placeholder('Pilih Jenis Kiriman')
                    ->columnSpan([
                        'default' => 'full',
                        'lg' => 1,
                    ]),
                Forms\Components\Select::make('service_id')
                    ->label('Jenis Layanan')
                    ->validationAttribute('Jenis Layanan')
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->options(Service::pluck('id', 'id'))
                    ->default(Service::value('id'))
                    ->exists(Service::class, 'id')
                    ->placeholder('Pilih Jenis Layanan')
                    ->columnSpan([
                        'default' => 'full',
                        'lg' => 1,
                    ]),
                Forms\Components\Select::make('packing_id')
                    ->label('Tambah Packing')
                    ->validationAttribute('Tambah Packing')
                    ->nullable()
                    ->native(false)
                    ->searchable()
                    ->options(Packing::pluck('name', 'id'))
                    ->exists(Packing::class, 'id')
                    ->placeholder('Pilih Packing')
                    ->columnSpan([
                        'default' => 'full',
                        'lg' => 1,
                    ]),
                Forms\Components\Toggle::make('is_cod')
                    ->label('COD ?')
                    ->validationAttribute('COD')
                    ->inline(false)
                    ->columnSpan(1),
                Forms\Components\Toggle::make('is_insurance')
                    ->label('Asuransi ?')
                    ->validationAttribute('Asuransi')
                    ->inline(false)
                    ->columnSpan(1),
            ])
            ->columnSpan([
                'default' => 'full',
                'lg' => 1,
            ])
            ->columns([
                'default' => 1,
                'lg' => 2,
            ])
            ->collapsible()
            ->collapsed();
    }

    public static function packageSchema(): mixed
    {
        return Forms\Components\Section::make('Barang')
            ->description('Detail barang yang dikirim')
            ->icon('heroicon-o-cube')
            ->schema([
                \Icetalker\FilamentStepper\Forms\Components\Stepper::make('package_koli')
                    ->label('Total Koli')
                    ->validationAttribute('Total Koli')
                    ->required()
                    ->default(Awb::MIN_KOLI)
                    ->minValue(Awb::MIN_KOLI)
                    ->maxValue(Awb::MAX_KOLI)
                    ->extraInputAttributes(['min' => Awb::MIN_KOLI, 'max' => Awb::MAX_KOLI])
                    ->columnSpan([
                        'default' => 'full',
                        'lg' => 1,
                    ]),
                \Icetalker\FilamentStepper\Forms\Components\Stepper::make('package_weight')
                    ->label('Total Berat (Kg)')
                    ->validationAttribute('Total Berat')
                    ->required()
                    ->default(Awb::MIN_WEIGHT)
                    ->step(0.01)
                    ->minValue(Awb::MIN_WEIGHT)
                    ->maxValue(Awb::MAX_WEIGHT)
                    ->extraInputAttributes(['min' => Awb::MIN_WEIGHT, 'max' => Awb::MAX_WEIGHT])
                    ->columnSpan([
                        'default' => 'full',
                        'lg' => 1,
                    ]),
                Forms\Components\Fieldset::make('volumetric')
                    ->label('Volumetric')
                    ->schema([
                        \Icetalker\FilamentStepper\Forms\Components\Stepper::make('package_length')
                            ->label('Panjang (cm)')
                            ->validationAttribute('Panjang')
                            ->required()
                            ->default(Awb::MIN_DIMENSION)
                            ->step(0.01)
                            ->minValue(Awb::MIN_DIMENSION)
                            ->maxValue(Awb::MAX_DIMENSION)
                            ->extraInputAttributes(['min' => Awb::MIN_DIMENSION, 'max' => Awb::MAX_DIMENSION])
                            ->columnSpanFull(),
                        \Icetalker\FilamentStepper\Forms\Components\Stepper::make('package_width')
                            ->label('Lebar (cm)')
                            ->validationAttribute('Lebar')
                            ->required()
                            ->default(Awb::MIN_DIMENSION)
                            ->step(0.01)
                            ->minValue(Awb::MIN_DIMENSION)
                            ->maxValue(Awb::MAX_DIMENSION)
                            ->extraInputAttributes(['min' => Awb::MIN_DIMENSION, 'max' => Awb::MAX_DIMENSION])
                            ->columnSpanFull(),
                        \Icetalker\FilamentStepper\Forms\Components\Stepper::make('package_height')
                            ->label('Tinggi (cm)')
                            ->validationAttribute('Tinggi')
                            ->required()
                            ->default(Awb::MIN_DIMENSION)
                            ->step(0.01)
                            ->minValue(Awb::MIN_DIMENSION)
                            ->maxValue(Awb::MAX_DIMENSION)
                            ->extraInputAttributes(['min' => Awb::MIN_DIMENSION, 'max' => Awb::MAX_DIMENSION])
                            ->columnSpanFull(),
                    ]),
                Forms\Components\TextInput::make('package_value')
                    ->label('Nilai Barang')
                    ->validationAttribute('Nilai Barang')
                    ->prefix('Rp')
                    ->numeric()
                    ->nullable()
                    ->maxValue(Awb::MAX_PACKAGE_VALUE)
                    ->extraInputAttributes(['max' => Awb::MAX_PACKAGE_VALUE])
                    ->maxLength(18)
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 0)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('package_desc')
                    ->label('Deskripsi Barang')
                    ->validationAttribute('Deskripsi Barang')
                    ->string()
                    ->maxLength(Awb::PACKAGE_DESC_MAX_LENGTH)
                    ->extraInputAttributes(['maxlength' => Awb::PACKAGE_DESC_MAX_LENGTH])
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('package_instruction')
                    ->label('Instruksi Khusus')
                    ->validationAttribute('Instruksi Khusus')
                    ->string()
                    ->maxLength(Awb::PACKAGE_INSTRUCTION_MAX_LENGTH)
                    ->extraInputAttributes(['maxlength' => Awb::PACKAGE_INSTRUCTION_MAX_LENGTH])
                    ->columnSpanFull(),
            ])
            ->columnSpan([
                'default' => 'full',
                'lg' => 1,
            ])
            ->columns([
                'default' => 1,
                'lg' => 2,
            ])
            ->collapsible()
            ->collapsed();
    }
}
