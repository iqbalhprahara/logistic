<?php

namespace App\Http\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Http\Livewire\BaseComponent;
use Barryvdh\DomPDF\Facade\Pdf;
use Core\Logistic\Models\Awb;
use Illuminate\Support\Facades\File;

class PrintAwb extends BaseComponent
{
    protected $gates = ['logistic:pickup:input-pickup:print-awb'];

    public ?string $uuid = null;

    public function mount($uuid)
    {
        $this->uuid = $uuid;
    }

    public function render()
    {
        return view('livewire.core-system.logistic.pickup.input-pickup.print-awb');
    }

    public function print()
    {
        $awb = Awb::with([
            'shipmentType',
            'packing',
            'originCity',
            'destinationCity',
        ])->findOrFail($this->uuid);

        $logo = base64_encode(File::get(public_path('img/logo/logo-color-bg.svg')));

        $pdf = Pdf::loadView('exports.core-system.logistic.awb-pdf', compact('awb', 'logo'))
            ->setPaper('A6', 'landscape');

        $this->emitSelf('pdf.generated', base64_encode($pdf->output()));
    }
}
