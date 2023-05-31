<div>
    <script>
        document.addEventListener('livewire:load', function () {
            @this.on('pdf.generated', function (data) {
                var byteCharacters = atob(data);
                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var file = new Blob([byteArray], { type: 'application/pdf;base64' });
                var fileURL = URL.createObjectURL(file);
                window.open(fileURL);
            });
        });
    </script>
    <a href="javascript:void(0)" wire:click.prevent="print" class="btn btn-outline-primary btn-icon btn-sm">
        <i class="fas fa-print"></i> Print AWB
    </a>
</div>

