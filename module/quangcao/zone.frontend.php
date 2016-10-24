<?php
class zone extends Anpro_Module_Base 
{
	private $tbl_cate;
	public function __construct($smarty,$db)
	{
		$table = "zone";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		$this -> mod = 'zone';
		$this -> type = 'zone';
	}

	function run($task= "", $other)
	{
		if($task==''){$task = $_GET['task'];}
		switch($task)
			{
				case 'listajax':
					$this -> getListReplaceLink();
				break;
				default:
			}
	}

	function getListReplaceLink()
	{
		$cond = '';
		$cate  = 0;
		$keyword  = "";
		$type = "all";
		if($_GET) {
			if($_GET['cate'] > 0)
			{
				$cond .= ' AND cate_id = ' . $_GET['cate'];
				$cate =  $_GET['cate'];
				$page_link .= "cate=".$cate;
			}

			if($_GET['type'] > 0)
			{
				$cond .= " AND type = " . $_GET['type'] . "'";
				$type =  $_GET['type'];
				$page_link .= "type=".$type;
				
			}

			if($_GET['keyword'])
			{
				$cond .= " AND LOWER(title) like LOWER('%" . $_GET['keyword'] . "%')";
				$keyword = $_GET['keyword'];
				$page_link .= "keyword=".$keyword;
			}		
		}

		$list_cate = $this -> getCategory($type);

		$where = " status=1 ";
		if ($cond != "")
			$where .= $cond;

		$page_link = $_SERVER['REQUEST_URI'];
		$page_link .= "&page=i++";

		$items = parent::Paging(20,'title, id', $where,$page_link);
		$this -> smarty -> assign('cate', $cate);
		$this -> smarty -> assign('keyword', $keyword);
		$this -> smarty -> assign('list_cate',$list_cate);
		$this -> smarty -> assign('items',$items);
		$this -> smarty -> display('replace_link.tpl');
	}

}
?>