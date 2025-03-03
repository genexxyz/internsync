<?php
namespace App\Livewire\Components;
use LivewireUI\Modal\ModalComponent;

class PdfViewerModal extends ModalComponent
{
    public $pdfUrl;
    
    public function mount($url)
    {
        $this->pdfUrl = $url;
    }
    
    // Set modal to be static (prevents closing when clicking outside)
    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
    
    // Set modal to a higher z-index
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }
    
    // Prevent closing parent modals
    protected function getListeners()
    {
        return array_merge(parent::getListeners(), [
            'openPdf' => 'openPdf',
        ]);
    }
}