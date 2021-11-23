<?php

namespace Mist\Controllers;

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
     * Get post
     *
     * @return void
     */
    public function getPost($id)
    {
        $posts = app()->get(Post::class);
        $posts = $posts->get($id);
        $this->json($posts);
    }
}
