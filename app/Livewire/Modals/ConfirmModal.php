<?php

namespace App\Livewire\Modals;

use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmModal extends Component
{
    public $show = false;

    public  $userId;


    #[On('showConfirmationModal')]
    public function showConfirmModal( $userId)
    {
        $this->userId = $userId;
        $this->show = true;
    }

    public function confirmDelete()
    {
        $this->dispatch('deleteConfirmed', id: $this->userId);
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.modals.confirm-modal');
    }
}
