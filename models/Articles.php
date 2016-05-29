<?php
namespace MVC\Models;

class Articles extends \MVC\Classes\DB
{
    function __construct()
    {
        $rDB = new \PDO("mysql:host=localhost;dbname=database;charset=utf8", 'root', '');
        
        parent::__construct($rDB, 'articles');
    }
}