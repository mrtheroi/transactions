<?php

namespace App\Livewire\Ui;

use Livewire\Attributes\On;
use Livewire\Component;

class Notification extends Component
{
    public bool $visible = false;
    public string $message = '';
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

        // Esto garantiza que la notificación no interferirá con otros componentes
        if ($autoClose) {
            $this->dispatch('closeNotification')->self();
        }
    }

    #[On('closeNotification')]
    public function closeNotification()
    {
        // Usando un ligero retraso para asegurar que no interfiera con otras actualizaciones
        sleep(0.1); // Un pequeño retraso de 100ms
        $this->visible = false;
    }

    public function render()
    {
        return view('livewire.ui.notification');
    }
}
