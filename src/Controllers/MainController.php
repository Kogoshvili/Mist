<?php

namespace Mist\Controllers;

use Mist\Core\Core;
use Mist\Models\Post;

class MainController extends Controller
{
    /**
     * Home page
     *
     * @return void
     */
    public function Home()
    {
        $this->view('home');
    }

    /**
     * Home Api
     *
     * @return void
     */
    public function Api()
    {
        $posts = Core::get(Post::class);
        $posts = $posts->all();
        $this->json($posts);
    }

    /**
     * Get post
     *
     * @return void
     */
    public function getPost($id)
    {
        $posts = Core::get(Post::class);
        $posts = $posts->get($id);
        $this->json($posts);
    }
}
