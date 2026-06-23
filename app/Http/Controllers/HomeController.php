<?php

declare(strict_types=1);

namespace Http\Controllers;

use Http\Models\Image;
use Http\Models\User;

class HomeController
{
    public function index(\Base $hive)
    {
        view('pages/home');
    }
}
