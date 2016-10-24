<?php
class tintuc extends Anpro_Module_Base 
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

	function run($task= "", $other)
	{

		if($task==''){$task = $_GET['task'];}
		switch($task)
			{
				case 'getTinMoiHorizontal':
					$this -> getTinMoiHorizontal();
				break;
				case 'hot_news':
					$this -> getHotNews();
				break;
				case 'lastest':
					$this -> getLastest();
				break;
				case 'tinmoi':
					$this -> getTinMoi();
				break;
				case 'tinanh':
					$this -> getCateLastest($task, $other);
				break;
				case 'details':
					$this -> details();
				break;
				case 'list':
					$this->listItem($_GET['id']);
				break;
				default:
					$this->listItem();
				break;
			}
	}
        
        
    function getTinMoiHorizontal()
	{
		$template = 'tinmoi_horizontal_tintuc.tpl';
		if(!$this->isCache($template)) {
			$where = " status = 1 AND is_hot = 1 AND site_id = {$this -> site_id}";
			$items = $this -> getListTitle($where, 8, 'create_date DESC');
			for($i = 0; $i < count($item); $i++) {
				$item[$i]['avatar'] = str_replace('/thumb/', '/', $item[$i]['avatar']);
			}
			$this -> smarty -> assign('itemsHorizontal',$items);
		}

		$this -> smarty -> display($template, 0);
	}

	//get menu trai.
	function getMenuLeft()
	{
		$sql = "SELECT c.name, c.page_url, c.id, c.parent_id FROM `categories` c WHERE  c.status = 1 AND  site_id = '{$this-> site_id}' AND lang_id = '{$this-> lang_id}' order by c.ordering";
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
	function getHotNews($num_row = 5)
	{
		$template = 'hotnews_tintuc.tpl';
		if(!$this->isCache($template)) {
			$where = " status = 1 AND is_hot = 1 AND (site_id = '{$this-> site_id}' AND lang_id = '{$this-> lang_id}' OR type = 'live')";
			$items = $this -> getListTitle($where, $num_row, 'create_date DESC');
			for($i = 0; $i < count($item); $i++) {
                            $item[$i]['avatar'] = str_replace('/thumb/', '/', $item[$i]['avatar']);
			}
			$this -> smarty -> assign('items',$items);
		}

		$this -> smarty -> display($template, 0);
	}

	function getLastest($num_row = 5)
	{
            $template = 'lastest_tintuc.tpl';
            $where = " status = 1 AND site_id = '{$this-> site_id}' AND lang_id = '{$this-> lang_id}'";
            $items = $this -> getListTitle($where, $num_row, 'id DESC');
            $this -> smarty -> assign('items',$items);
            $this -> smarty -> display($template, 0);
	}

	function getTinMoi()
	{
		if(CHECK_DEVICE == 1){
			$this -> smarty -> display('pc/news_footer.tpl');
		}
	/*	if(!$this->isCache($template)) {
			$where = " status = 1 AND site_id = '{$this-> site_id}' AND lang_id = '{$this-> lang_id}'";
			$items = $this -> getListTitle($where, $limit,  $order);
			$this -> smarty -> assign('itemsMoi',$items);

			$cond = " status = 1 AND  DATEDIFF( DATE( create_date ) , NOW( ) ) > -5";

			$mostread = $this -> getListTitle($cond, $limit, " count DESC");
			$this -> smarty -> assign('mostread',$mostread);
		}
		$this->smarty->clearCache($template);*/

	}


	function getCateLastest($task, $cateId, $limit = 6, $order = "id DESC")
	{
            $template = $task.'_lastest_tintuc.tpl';
            if(!$this->isCache($template)) {
                    $where = " a.status = 1 AND a.lang_id = '{$this-> lang_id}' AND c.id = {$cateId}";
                    $items = $this -> getListTitleHasCate($where, $limit, $order);
                    $this -> smarty -> assign('itemsCateLastestNews',$items);
            }

            $this -> smarty -> display($template, 0 );
	}

	function details()
	{
		$id = (int)$_GET['id'];
		$template = 'detail_tintuc.tpl';
		if ($id) $this->updateNumberView($id);
		if(!$this->isCache($template, $id)) {
			$where = "status=1 AND site_id = {$this -> site_id}";
			if (MULTI_LANGUAGE)
				$where .= " AND lang_id = {$this->lang_id} ";

			$sql = "SELECT * FROM {$this->table} WHERE {$where} AND id={$id}";
			$detail = $this -> db -> getRow($sql);
			$this->smarty->assign('detailNews',$detail);
			
			if ($detail['cate_id'] != '')
				$where .= " AND cate_id = {$detail['cate_id']}";

			$sql = "SELECT * FROM {$this->table} WHERE {$where} AND id < {$id} ORDER BY create_date DESC LIMIT 10";
			$other_item = $this->db->getAll($sql);
			$str_nav = $this -> getNav($detail['cate_id']);
			$this->smarty->assign('str_nav',$str_nav);
			$this->smarty->assign('otherNews',$other_item);
                        
			$this->smarty->assign('mod',$this->mod);
                        $this->smarty->assign('related',$this->getRelatedById());
		}
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

	function getListItem($cate_id =0,$page_link,$cond="",$limit= 14,$template="list_tintuc.tpl",$order="ORDER BY id DESC")
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
		$this->smarty->assign("itemsListNews",$items);
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
        
    function getCateById($id) {
        global $cache;
        $id = (int) $id;
        $cache_id = "news_cate_{$id}";
        if (!($cate_detail = $cache->get($cache_id, "cate")))
        {
            $sql = "SELECT * FROM {$this -> tbl_cate} where id = {$id} AND site_id = {$this ->site_id} ";
            $cate_detail = $this -> db -> getRow($sql);
            $cache->save($cache_id, $cate_detail, "cate");
        }
        
        return $cate_detail;
    }
        
    function getArticleById($id) {
        global $cache;
        $id = (int) $id;
        $cache_id = "news_article_{$id}";
        if (!($detail = $cache->get($cache_id, "article")))
        {
            $sql = "SELECT title, id, site_id, cate_id, lead, avatar, page_url, tagcloud, page_source, source, ext, public_date, is_cache  FROM {$this -> table} where id = {$id} AND site_id = {$this ->site_id} ";
            $detail = $this -> db -> getRow($sql);
            $cache->save($cache_id, $detail, "article");
        }
        
        return $detail;
    }
}
?>