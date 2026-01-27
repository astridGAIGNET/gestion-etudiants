<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Quill extends Component
{
    public string $id;
    public string $model;
    public string $name;
    public string $label;
    public int $height;
    public string $value;

    public function __construct(
        string $id = '',
        string $model = '',
        string $name = '',
        string $label = '',
        int $height = 300,
        string $value = ''
    ) {
        // Si name est fourni mais pas id, utilise name comme id
        $this->id = $id ?: ($name ?: 'editor');
        $this->model = $model;
        $this->name = $name ?: $this->id;
        $this->label = $label;
        $this->height = $height;
        $this->value = $value;
    }

    public function render(): View
    {
        return view('components.quill');
    }
}
