<?php
namespace MVC\Controllers;

use \MVC\Models\Articles as Article;

class IndexController extends \MVC\Classes\Controller
{
    public function index()
    {
        //$articles = new Article();
        
        $this->loadTemplate('Index', 'index', ['message' => 'Test']);
    }
}