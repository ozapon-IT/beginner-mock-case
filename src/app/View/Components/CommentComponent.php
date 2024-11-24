<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CommentComponent extends Component
{
    public $comment;
    public $profileImagePath;
    public $username;

    /**
     * Create a new component instance.
     */
    public function __construct($comment)
    {
        $this->comment = $comment;

        $this->profileImagePath = $comment->user->profile && $comment->user->profile->image_path ? asset('storage/' . $comment->user->profile->image_path) : '';

        $this->username = $comment->user->name;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.comment');
    }
}
