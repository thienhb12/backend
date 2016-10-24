<?php
class search extends Anpro_Module_Base 
{
	private $tbl_cate;
	public function __construct($smarty,$db)
	{
		$table = "articles";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'search';
		$this -> type = 'tin-tuc';
		$this -> tbl_cate = 'categories';
	}

	function run($task= "", $other)
	{
		if($task==''){$task = $_GET['task'];}
		switch($task)
		{
			default:
				$this->listItem();
			break;
		}
	}
	/**
	 * Page info
	 *
	 * @param $task
	 */
	function getPageinfo($task= "")
	{
		switch ($task) {
			case 'list':
				$id = $_GET['id'];
				if ($id)
				{
					$sql = "SELECT name FROM " . CATEGORIES . " WHERE type = '{$this -> type}' AND id=$id";
					$row = $this -> db -> getOne($sql);
				}
				$aPageinfo=array('title'=> $row." | " . PAGE_TITLE, 'keyword'=>PAGE_KEYWORD.",".$row, 'description'=>PAGE_DESCRIPTION);
				$aPath[] = array('link' => '', 'path' => 'search');
				break;
            case 'details':
                $id = $_GET['id'];
                if ($id)
                {
                    $sql = "SELECT title FROM {$this->table} WHERE id=$id";
                    $row = $this -> db -> getOne($sql);
                }
                $aPageinfo=array('title'=> $row." | " . PAGE_TITLE, 'keyword'=>PAGE_KEYWORD.",".$row, 'description'=>PAGE_DESCRIPTION);
                $aPath[] = array('link' => '', 'path' => 'search');
                break;
			default:
				$aPageinfo=array('title'=>  PAGE_TITLE, 'keyword'=>PAGE_KEYWORD.",".$row, 'description'=>PAGE_DESCRIPTION);
				$aPath[] = array('link' => '', 'path' => 'search');
				break;
		}
		
		$this->smarty->assign('aPageinfo', $aPageinfo);
		$this->smarty->assign("aPath", $aPath);
	}
	/**
	 * Display in home
	 *
	 */



	function listItem($cate_id=0)
	{

		$this->smarty->assign("key",$_GET['key']);
		$this->smarty->display('form_search.tpl');

		if ($_GET['key']) {
			$url = str_replace('&page=' . $_GET['page'], '', $_SERVER['REQUEST_URI']);
			$url .= "&page=i++";

			$cond = "title like '%".$_GET['key']."%'";
			$this->getListItem($cate_id, $url, $cond);
		}
	}

	function getListItem($cate_id =0,$page_link,$cond="",$limit= 32,$template="list_search.tpl",$order="ORDER BY ordering, create_date DESC")
	{
		
		$where = " status=1 ";

		if ($cond != "")
			$where .= " AND ".$cond;

		$items = parent::Paging($limit,'title, type, page_url, lead, avatar', $where,$page_link, $order);
		$this->smarty->assign("items",$items);
		$this->smarty->display($template);
	}
}
?>