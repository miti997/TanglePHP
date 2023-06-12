<?php

declare(strict_types=1);

namespace app\src\components;

use app\core\Component;

class Home extends Component
{
    public function mount()
    {
        return $this->render('home');
    }
}
