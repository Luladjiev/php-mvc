<?php
namespace MVC;

class Template 
{
    final public function html($content)
    {
        ?>
        
        <!DOCTYPE html>
        <html>
            <head>
                <title>MVC Example</title>
            </head>
            <body>
                <?php echo $content; ?>
            </body>
        </html>
        
        <?php
    }
}