<?php
class order extends VES_FrontEnd 
{
    var $table;
	var $mod;
	var $id;
	var $LangID;
	var $field;
	var $pre_table;	
	function __construct(){		
		$this->table = 'tbl_order';
		$this->pre_table = "";
		$this->mod = 'order';
		$this->id = mysql_real_escape_string(($_REQUEST['id']=='')?'0':$_REQUEST['id']);
		$this->field = "*";
		parent::__construct($this->table);
	}
	function run($task="")
	{
		global $oSmarty, $oDb;	
		$sql = "SELECT * FROM {$this->table} WHERE Status = 1";
        $order = $oDb->getRow($sql);
        $oSmarty->assign("order", $order);
		$oSmarty->display('showOrder.tpl');
	}
	function getPageinfo($task= "")
	{
	 	global $oSmarty, $oDb;
		switch ($task) {
			default:
				$aPageinfo=array('title'=> 'Liên hệ | '.PAGE_TITLE, 'keyword'=>PAGE_KEYWORD, 'description'=>PAGE_DESCRIPTION);
				$aPath[] = array('link' => '', 'path' => 'contact');
				break;
		}
		
		$oSmarty->assign('aPageinfo', $aPageinfo);
		$oSmarty->assign("aPath", $aPath);
	}
}
?>
