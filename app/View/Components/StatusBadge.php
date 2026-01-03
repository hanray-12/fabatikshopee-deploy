<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $status;
    public string $color;

    public function __construct(string $status)
    {
        $this->status = $status;

        $this->color = match ($status) {
            'pending' => 'bg-yellow-500',
            'paid'    => 'bg-blue-600',
            'shipped' => 'bg-green-600',
            default   => 'bg-gray-500',
        };
    }

    public function render()
    {
        return view('components.status-badge');
    }
}
