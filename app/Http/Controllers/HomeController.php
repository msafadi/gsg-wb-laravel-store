<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $news = [
        1 => 'News Title 1',
        2 => 'News Title 2',
        3 => 'News Title 3',
        4 => 'News Title 4',
    ];

    // Actions
    public function index()
    {
        return view('welcome');
    }

    public function news($category, $id = 0)
    {
        echo '<h1>' . $category . '</h1>';
        if ($id) {
            echo '<h1>' . $this->news[$id] . '</h1>';
        } else {
            echo '<ul>';
            foreach ($this->news as $news) {
                echo '<li>' . $news . '</li>';
            }
            echo '</ul>';
        }
        //return view('news');
    }
}
