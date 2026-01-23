<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class Quill extends Component
{
    public string $id;
    public string $model;
    public int $height;

    public function __construct(
        string $id = 'editor',
        string $model = '',
        int $height = 300
    ) {
        $this->id = $id;
        $this->model = $model;
        $this->height = $height;
    }

    public function render(): View
    {
        return view('components.quill');
    }
}
