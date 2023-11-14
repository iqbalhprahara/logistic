<?php

namespace App\Jobs;

use App\Models\Utility\Import;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ImportJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Import $import)
    {
        $this->onQueue('data');
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->import->uuid;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $success = false;
        try {
            $this->import->markOnProcess();
            $this->import->start();
            $this->import->markComplete();

            $success = true;
        } catch (\Throwable $t) {
            Notification::make()
                ->title('Proses import data gagal')
                ->body("Terjadi kesalahan saat import {$this->import->import_type->value} untuk file {$this->import->filename}.")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->button()
                        ->url($this->import->import_type->url().'/'.$this->import->uuid, shouldOpenInNewTab: true)
                        ->markAsRead(),
                ])
                ->danger()
                ->sendToDatabase($this->import->createdBy);

            $this->import->message = 'Some error occured when processing data.';
            $this->import->exception = $t;
            $this->import->markFailed();
        }

        if ($success) {
            Notification::make()
                ->info()
                ->title('Proses import data telah selesai')
                ->body("Import {$this->import->import_type->value} untuk file {$this->import->filename} telah selesai.")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('view')
                        ->button()
                        ->url($this->import->import_type->url().'/'.$this->import->uuid, shouldOpenInNewTab: true)
                        ->markAsRead(),
                ])
                ->sendToDatabase($this->import->createdBy);
        }

        $this->handleFileDeletion();
    }

    private function handleFileDeletion()
    {
        Storage::delete($this->import->getImportFileStoragePath());
    }
}
