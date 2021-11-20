<?php

namespace Vitra\Controllers;

class MainController extends BaseController
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
}
