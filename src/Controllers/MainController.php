<?php

namespace Mist\Controllers;

use Mist\Core\Request;

class MainController extends Controller
{
    /**
     * Home page
     *
     * @return void
     */
    public function Home()
    {
        self::view('home');
    }

    /**
     * Home page
     *
     * @return void
     */
    public function Api()
    {
        self::json(['message' => 'Hello World!']);
    }
}
