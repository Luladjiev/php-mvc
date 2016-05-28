<?php
namespace MVC;

class IndexTemplate extends Template
{
    public function index($params)
    {
        ?>
        
        <div>Index Template - <?php echo $params['message'];?></div>
        
        <?php
    }
}