<?php

namespace App\Livewire\Ui;

use Livewire\Attributes\On;
use Livewire\Component;

class Notification extends Component
{
    public bool $visible = false;
    public string $message = '';  // Asegúrate de que esta propiedad esté definida
    public string $type = 'success'; // success, error, warning, info
    public ?string $title = null;
    public bool $autoClose = true;
    public int $duration = 3000;

    #[On('notify')]
    public function showNotification(
        string $message,
        string $type = 'success',
        ?string $title = null,
        bool $autoClose = true,
        int $duration = 3000
    ) {
        $this->message = $message;
        $this->type = $type;
        $this->title = $title;
        $this->autoClose = $autoClose;
        $this->duration = $duration;
        $this->visible = true;
    }

    public function render()
    {
        return view('livewire.ui.notification');
    }
}
