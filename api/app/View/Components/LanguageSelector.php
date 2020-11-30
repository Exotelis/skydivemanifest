<?php

namespace App\View\Components;

use Illuminate\View\Component;

/**
 * Class LanguageSelector
 * @package App\View\Components
 */
class LanguageSelector extends Component
{
    /**
     * The current language.
     *
     * @var string
     */
    public $language;

    /**
     * Create a new component instance.
     *
     * @param  string $language
     * @return void
     */
    public function __construct($language)
    {
        $this->language = $language;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.language-selector');
    }
}
