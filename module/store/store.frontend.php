<?php
class store extends Anpro_Module_Base 
{
	private $tbl_cate;
	public function __construct($smarty,$db)
	{
		$table = "articles";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		$this -> mod = 'store';
		$this -> tbl_type = 'product_type';
		$this -> tbl_cate = 'categories';
	}

	function run($task= "", $other)
	{
		if($task==''){$task = $_GET['task'];}
		switch($task)
			{
				case 'menu_left':
					$this -> getMenuLeft();
				break;
				case 'hot_news':
					$this -> getHotNews();
				break;
				case 'lastest':
					$this -> getLastest();
				break;
				case 'details':
					$this -> details();
				break;
				case 'list':
					$this->listItem($_GET['id']);
				break;
				default:
					$this->modDefault();
				break;
			}
	}

	function modDefault() {
		echo 1;
		echo $this -> smarty->getTemplateDir();



		$this -> display('detail.tpl');
	}

	//get menu trai.
	function getMenuLeft()
	{
		$sql = "SELECT c.name, c.page_url, c.id, c.parent_id FROM `categories` c WHERE  c.status = 1 AND  type = '{$this-> type}' order by c.ordering";
		$cates = $this -> db -> getAll($sql);
		if(count($cates) > 0) {
			$this -> smarty -> assign('cates',$cates);
			$this -> smarty -> display('menu_left_'.$this -> mod.'.tpl');
		}
	}
	/**
	 * Display in home
	 *
	 */
	function getHotNews()
	{
		$template = 'hotnews_tintuc.tpl';
		if($this->isCache($template)) {
			$where = " status = 1 AND is_hot = 1 AND (type = '{$this-> type}' OR type = 'live')";
			$items = $this -> getListTitle($where, 5, 'create_date DESC');
			for($i = 0; $i < count($item); $i++) {
				$item[$i]['avatar'] = str_replace('/thumb/', '/', $item[$i]['avatar']);
			}
			$this -> smarty -> assign('items',$items);
		}

		$this -> smarty -> display($template, 0);
	}

	function getLastest()
	{
		$template = 'lastest_tintuc.tpl';
		if($this->isCache($template)) {
			$where = " status = 1 AND type = '{$this-> type}'";
			$items = $this -> getListTitle($where, 5, 'id DESC');
			$this -> smarty -> assign('items',$items);
		}
		$this -> smarty -> display($template, 0);
	}

	function getCateLastest($task, $cateId)
	{
		$template = $task.'_lastest_tintuc.tpl';
		if($this->isCache($template)) {
			$where = " a.status = 1 AND a.type = '{$this-> type}' AND c.id = {$cateId}";
			$items = $this -> getListTitleHasCate($where, 6, "id DESC");
			$this -> smarty -> assign('items',$items);
		}

		$this -> smarty -> display($template, 0 );
	}

	function details()
	{
		$id = $_GET['id'];
		$template = 'detail_tintuc.tpl';
		if ($id) $this->updateNumberView($id);
		//if($this->isCache($template, $id)) {
			$where = "status=1";
			if (MULTI_LANGUAGE)
				$where .= " AND lang_id = {$this->lang_id} ";

			$sql = "SELECT * FROM {$this->table} WHERE {$where} AND id={$id}";
			$detail = $this -> db -> getRow($sql);
			$this->smarty->assign('detail',$detail);
			
			if ($detail['cate_id'] != '')
				$where .= " AND cate_id = {$detail['cate_id']}";

			$sql = "SELECT * FROM {$this->table} WHERE {$where} AND id < {$id} ORDER BY create_date DESC LIMIT 10";
			$other_item = $this->db->getAll($sql);
			$str_nav = $this -> getNav($detail['cate_id']);
			$this->smarty->assign('str_nav',$str_nav);
			$this->smarty->assign('other',$other_item);
			$this->smarty->assign('mod',$this->mod);
		//}
		$this->smarty->clearCache($template);
		$this->smarty->display($template, $id);
	}

	function getFullCatId($cate_id,$first = true)
	{
		$where = "status=1";
		if (MULTI_LANGUAGE)
			$where .= " AND lang_id = {$this->lang_id} ";
		$str_cat = "";
		$sql = "SELECT id FROM {$this->tbl_cate} WHERE {$where} AND parent_id={$cate_id} AND type = '{$this -> type}' ORDER BY ordering";
		$cat = $this->db->getCol($sql);
		if ($cat)
		{
			if( is_array( $cat) && count( $cat )> 0)
				$str_cat = implode(',',$cat);
			$temp_str_cat = "";
			foreach ($cat as $key=>$value)
			{
				$temp_str_cat .= $this->getFullCatId($value,false);
			}
			$str_cat .=",".$temp_str_cat;
		}
		if ($first)  
			$str_cat .=$cate_id;
		return $str_cat;
	}

	function getFullCatName($cate_id)
	{
		$sql = "SELECT name, page_url, parent_id, id FROM {$this->tbl_cate} WHERE status = 1 AND id={$cate_id} ORDER BY ordering";
		$cate = $this-> db -> getRow($sql);

		if($cate['parent_id'] > 0) {
			$cate_id = $cate['parent_id'];
			$sql = "SELECT name, page_url, parent_id, id FROM {$this->tbl_cate} WHERE status = 1 AND id={$cate_id} ORDER BY ordering";
			$cate = $this-> db -> getRow($sql);
		}
		$cond = " status = 1 AND id in (SELECT id FROM {$this->tbl_cate} WHERE parent_id = {$cate_id})";
		$sql = "SELECT name,page_url, id FROM {$this->tbl_cate} WHERE {$cond} ORDER BY ordering";

		$listCate = $this -> db -> getAll($sql);

		$this -> smarty -> assign('listCate', $listCate);
		$this -> smarty -> assign('cate', $cate);
	}

	function listItem($cate_id=0)
	{
		$url = $_SERVER['REQUEST_URI'];
		if ($_GET['page'])
			$url = str_replace("trang-".$_GET['page'].".html","",$url);

		$url .= "trang-i++.html";
		$this->getListItem($cate_id, $url);
	}

	function getListItem($cate_id =0,$page_link,$cond="",$limit= 10,$template="list_tintuc.tpl",$order="ORDER BY id DESC")
	{
		$where = " status=1 AND type = '{$this -> type}' ";

		if (MULTI_LANGUAGE)
			$where .= " AND lang_id = {$this->lang_id} ";

		if ($cate_id != 0)
		{
			$str_cate_id = $this->getFullCatId($cate_id);
			$where .=" AND cate_id IN ({$str_cate_id})";
			$this->getFullCatName($cate_id);
		}

		if ($cond != "")
			$where .= " AND ".$cond;
		$str_nav = $this -> getNav($cate_id);
		$this->smarty->assign('str_nav',$str_nav);
		$items = parent::Paging($limit,'title, page_url, lead, avatar', $where,$page_link, $order);
		$this->smarty->assign("items",$items);
		$this->smarty->clearCache($template);
		$this->smarty->display($template);
	}

	function getNav($cat_id)
	{
		$where = "status=1";

		if (MULTI_LANGUAGE)
			$where .= " AND langid = {$this->langid} ";

		$sql = "SELECT name, page_url FROM {$this->tbl_cate} WHERE {$where} AND id={$cat_id} ORDER BY ordering";
		$row = $this -> db -> getRow($sql);

		$sql = "SELECT parent_id FROM {$this->tbl_cate} WHERE {$where} AND id={$cat_id} ORDER BY ordering";
		$parent = $this -> db ->getOne($sql);

		if ($parent)
			$result = $this->getNav($parent).'<li><a href="'.$row['page_url'].'" class="second">'.$row['name'].'</a></li>';
		else 
			if($row['page_url']) {
				$result = '<li class="active"><a href="'.$row['page_url'].'">'.$row['name'].'</a></li>';
			}

		return $str.$result;
	}
}
?>