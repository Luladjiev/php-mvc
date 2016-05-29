<?php
namespace MVC\Classes;

class Template 
{
    private $cssScripts = [];
    private $jsScripts = [];
    
    protected function printScript() { }
    protected function printStyle() { }
    
    protected function addScript($type, $src) {
        switch ($type) {
            case 'js':
                $this->jsScripts[] = $src;
                break;
            
            case 'css':
                $this->cssScripts[] = $src;
                break;
        }
    }
    
    final public function html($content)
    {
        ?>
        
        <!DOCTYPE html>
        <html>
            <head>
                <title>MVC Example</title>
                <?php foreach($this->cssScripts as $script): ?>
                <link rel="stylesheet" href="<?php echo $script; ?>" type="text/css" />
                <?php endforeach; ?>
                <?php echo $this->printStyle(); ?>
            </head>
            <body>
                <?php echo $content; ?>
                
                <?php foreach($this->jsScripts as $script): ?>
                <script type="text/javascript" src="<?php echo $script; ?>"></script>
                <?php endforeach; ?>
                
                <?php echo $this->printScript(); ?>
            </body>
        </html>
        
        <?php
    }
}