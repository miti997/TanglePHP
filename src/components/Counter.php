<?php

declare(strict_types=1);

namespace src\components;

use core\Component;

class Counter extends Component
{
    public $counter = 0;

    public function mount()
    {
        return $this->render('counter');
    }

    public function increment()
    {
        $this->counter++;
    }

    public function decrement()
    {
        $this->counter--;
    }
}
