<?php
namespace MVC;

class Articles extends DB
{
    function __construct()
    {
        $rDB = new \PDO("mysql:host=localhost;dbname=database;charset=utf8", 'root', '');
        
        parent::__construct($rDB, 'articles');
    }
}