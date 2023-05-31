<?php

namespace App\Http\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Http\Livewire\BaseComponent;
use Barryvdh\DomPDF\Facade\Pdf;
use Core\Logistic\Models\Awb;

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

        $pdf = Pdf::loadView('exports.core-system.logistic.awb-pdf', compact('awb'))
            ->setPaper('A6', 'landscape');

        $this->emitSelf('pdf.generated', base64_encode($pdf->output()));
    }
}
