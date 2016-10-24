<?php
class footer extends Anpro_Module_Base 
{	
    public function __construct($smarty,$db)
    {
        $datagird = null;
        parent::__construct($smarty,$db,$datagird);
    }

    function run($task= "")
    {
        $task = $_GET['task'];
        switch($task)
            {
                default:
                    $this->getDefault();
                break;
            }
    }
    
    function getDefault() {
        global $smarty;
        if (CHECK_DEVICE == 1) {
            $this->display("pc/footer.tpl");
        }    
    }
}

?>