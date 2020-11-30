<?php

namespace App\View\Components;

use Illuminate\View\Component;

/**
 * Class ReCaptcha3Button
 * @package App\View\Components
 */
class ReCaptcha3Button extends Component
{
    /**
     * The form id.
     *
     * @var string
     */
    public $formID;

    /**
     * Create a new component instance.
     *
     * @param  string $formID
     * @return void
     */
    public function __construct($formID)
    {
        $this->formID = $formID;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.re-captcha3-button');
    }
}
