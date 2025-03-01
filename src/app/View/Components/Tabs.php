<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tabs extends Component
{
    public $tabs;
    public $currentTab;

    /**
     * Create a new component instance.
     */
    public function __construct($tabs, $currentTab = null)
    {
        $this->tabs = $tabs;
        $this->currentTab = $currentTab;
        $this->processTabs();
    }

    /**
     * Process tabs data (optional).
     */
    public function processTabs()
    {
        foreach ($this->tabs as $index => $tab) {
            $this->tabs[$index]['isActive'] = $tab['key'] === $this->currentTab;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tabs');
    }
}
