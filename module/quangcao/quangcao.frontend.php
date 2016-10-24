<?php
class quangcao extends Anpro_Module_Base 
{
	private $tbl_zone;
	private $tbl_item;
	public function __construct($smarty,$db)
	{
		$table = "ad_layout";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		$this -> mod = 'quangcao';
		$this -> type = 'quangcao';
		$this -> tbl_zone = 'ad_zone';
		$this -> tbl_item = 'ad_item';
	}

	function run($task= "", $other)
	{
		if($task==''){$task = $_GET['task'];}
		switch($task)
		{
			case 'list_zone':
				$this -> getListZone();
			break;
			case 'list_item':
				$this -> getHotNews();
			break;
			case 'list':
				$this->listItem($_GET['id']);
			break;
			default:
				$this->listItem();
			break;
		}
	}

	//get menu trai.
	function getListItems()
	{
		$sql = "SELECT * FROM {$this -> tbl_item} WHERE status = 1";
		$items = $this -> db -> getAll($sql);
		if(count($items) > 0) {
			$this -> smarty -> assign('items',$items);
			$this -> smarty -> display('list_zone.tpl');
		}
	}
}
?>