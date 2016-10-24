<?php
class slideshow extends Anpro_Module_Base 
{
	private $tbl_cate;
	public function __construct($smarty,$db)
	{
		$table = "articles";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'tintuc';
                $this -> type = 'tin-tuc';
		$this -> tbl_cate = 'categories';
	}

	function run($task= "")
	{
		if($task==''){$task = $_GET['task'];}
	    switch($task)
			{
				default:
					$this->listItem();                    
				break;	
			}
	}
	function listItem()
	{		
		global $oSmarty;
		if (CHECK_DEVICE == 2) {
			$this->display("tabslideshow.tpl");
		}
		if (CHECK_DEVICE == 1) {
			$this->display("pc/slideshow.tpl");
		}
	}	
}