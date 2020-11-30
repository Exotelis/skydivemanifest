<?php

namespace App\View\Components;

use Illuminate\View\Component;

/**
 * Class SignaturePad
 * @package App\View\Components
 */
class SignaturePad extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.signature-pad');
    }
}
