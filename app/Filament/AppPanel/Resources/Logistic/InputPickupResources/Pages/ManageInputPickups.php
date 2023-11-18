<?php

namespace App\Filament\AppPanel\Resources\Logistic\InputPickupResources\Pages;

use App\Dto\Awb\CreateAwbDto;
use App\Filament\AppPanel\Resources\Logistic\InputPickupResource;
use App\Models\Logistic\Awb;
use App\Services\AwbService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ManageInputPickups extends ManageRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = InputPickupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->color('success')
                ->icon('heroicon-o-document-arrow-up')
                ->modalHeading('Import Pickup Data')
                ->authorize('logistic:input-pickup:create')
                ->modalWidth('xl')
                ->form([
                    Forms\Components\FileUpload::make('import_file')
                        ->label('File')
                        ->validationAttribute('file')
                        ->storeFiles(false)
                        ->required()
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                        ->maxSize(100000),
                ])
                ->action(function (Actions\Action $action, AwbService $awbService, array $data) {
                    try {
                        $import = DB::transaction(fn () => $awbService->import($data['import_file']));

                        Notification::make()
                            ->title('Info')
                            ->body('Import data pickup anda telah di masukan kedalam antrian. Check progress pada halaman import awb log atau dengan klik tombol di bawah')
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->button()
                                    ->url($import->import_type->url().'/'.$import->uuid, shouldOpenInNewTab: true),
                            ])
                            ->info()
                            ->send();
                    } catch (\Throwable $t) {
                        Log::error($t);
                        Notification::make()
                            ->title('Failed')
                            ->body('Terjadi kesalah. Silahkan coba lagi dalam beberapa menit.')
                            ->danger()
                            ->send();

                        $action->halt();
                    }
                })
                ->extraModalFooterActions([
                    Actions\Action::make('template')
                        ->label('Download Template')
                        ->color('info')
                        ->icon('heroicon-o-document-arrow-down')
                        ->authorize('logistic:input-pickup:create')
                        ->action(fn (AwbService $awbService) => $awbService->downloadImportTemplate(auth()->user()->isClient())),
                ]),
            Actions\CreateAction::make()
                ->label('Request Pickup')
                ->modalHeading('Request Pickup')
                ->authorize('logistic:input-pickup:create')
                ->using(fn (array $data, AwbService $service) => DB::transaction(fn () => $service->create(CreateAwbDto::from($data))))
                ->successNotificationTitle(fn (Awb $record) => $record->awb_no.' created')
                ->modalWidth('full')
                ->slideOver(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [
            'on-process' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->notDelivered())->badge(Awb::withoutTrashed()->notDelivered()->count())->badgeColor('secondary'),
            'delivered' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->withoutTrashed()->delivered())->badge(Awb::withoutTrashed()->delivered()->count())->badgeColor('success'),
        ];

        if (Gate::allows('logistic:input-pickup:restore')) {
            $tabs['void'] = Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())->icon('heroicon-o-trash')->badge(Awb::onlyTrashed()->count())->badgeColor('danger');
        }

        return $tabs;
    }
}
