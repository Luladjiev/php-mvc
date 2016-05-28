<?php
namespace MVC;

class IndexController extends Controller
{
    public function index()
    {
        $this->loadTemplate('Index', 'index');
    }
}