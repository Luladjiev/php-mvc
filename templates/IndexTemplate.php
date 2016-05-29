<?php
namespace MVC\Templates;

class IndexTemplate extends \MVC\Classes\Template
{
    public function index($params)
    {
        ?>
        
        <div>Index Template - <?php echo $params['message'];?></div>
        
        <?php
    }
}