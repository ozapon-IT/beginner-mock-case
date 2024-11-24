<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public $item;
    public $context;

    /**
     * Create a new component instance.
     */
    public function __construct($item, $context = 'default')
    {
        $this->item = $item;
        $this->context = $context;
    }

    public function getLabel()
    {
        if ($this->context === 'index' && ($this->item->status === 'sold' || $this->item->status === 'transaction')) {
            return 'SOLD';
        }

        if ($this->context === 'mypage' && $this->item->status === 'transaction') {
            return '未確定';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-card', ['label' => $this->getLabel()]);
    }
}
