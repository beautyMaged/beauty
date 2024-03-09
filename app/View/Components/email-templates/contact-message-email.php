<?php

namespace App\View\Components\email-templates;

use Illuminate\View\Component;

class contact-message-email extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.email-templates.contact-message-email');
    }
}
