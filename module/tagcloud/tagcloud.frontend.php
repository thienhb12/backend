<?php
class tagcloud extends Anpro_Module_Base 
{
	private $tbl_cate;
	public function __construct($smarty,$db)
	{
		$table = "tagcloud";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> type = 'tagcloud';
		$this -> mod = 'tagcloud';
	}

	function run($task= "", $other)
	{
		if($task==''){$task = $_GET['task'];}
		switch($task)
			{
				case 'article':
					$this -> getArticle();
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
				$aPath[] = array('link' => '', 'path' => 'phimtruyenhinh');
				break;
            case 'details':
                $id = $_GET['id'];
                if ($id)
                {
                    $sql = "SELECT name FROM {$this->table} WHERE id=$id";
                    $row = $this -> db -> getOne($sql);
                }
                $aPageinfo=array('title'=> $row." | " . PAGE_TITLE, 'keyword'=>PAGE_KEYWORD.",".$row, 'description'=>PAGE_DESCRIPTION);
                $aPath[] = array('link' => '', 'path' => 'phimtruyenhinh');
                break;
			default:
				$aPageinfo=array('title'=>  PAGE_TITLE, 'keyword'=>PAGE_KEYWORD.",".$row, 'description'=>PAGE_DESCRIPTION);
				$aPath[] = array('link' => '', 'path' => 'phimtruyenhinh');
				break;
		}
		
		$this->smarty->assign('aPageinfo', $aPageinfo);
		$this->smarty->assign("aPath", $aPath);
	}

	function getArticle()
	{
            global $cache;
		$article_id = (int)$_GET['id'];
		$template = 'article_tagcloud.tpl';
		$cache_id = "news_article_{$article_id}";
                $article = $cache->get($cache_id, "article");
                if ($article !== FALSE)
                {
                    $tags = $this -> getTagCloud($article['tagcloud']);
                    $color = array('label-default','label-success','label-warning','label-danger','label-info');
                    $this -> smarty -> assign('color',$color);
                    if(count($tags) > 0) {
                            $this->smarty->assign('tags',$tags);
                            $this->smarty->clearCache($template);
                            $this->smarty->display($template);
                    }
                }
	}

	function details()
	{
		$id = $_GET['id'];
		$template = 'detail_tagcloud.tpl';
		$sql = "SELECT * FROM tagcloud WHERE id = {$id}";
		$detail = $this -> db -> getRow($sql);

		$where = "status=1 AND site_id = {$this -> site_id}";
		if (MULTI_LANGUAGE)
			$where .= " AND lang_id = {$this->lang_id} ";

		$sql = "SELECT * FROM articles WHERE {$where} AND tagcloud like '%{$detail['name']}%' ORDER BY create_date DESC LIMIT 10";
		$items = $this->db->getAll($sql);
		$this->smarty->assign('items',$items);
		$this->smarty->assign('detail',$detail);
		$this->smarty->clearCache($template);

		$this->smarty->display($template);
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

	function listItem()
	{
		$url = $_SERVER['REQUEST_URI'];
		if ($_GET['page'])
			$url = str_replace("trang-".$_GET['page'].".html","",$url);

		$url .= "trang-i++.html";
		$this->getListItem($url);
	}

	function getListItem($page_link,$cond="",$limit= 100,$template="list_tagcloud.tpl",$order="ORDER BY count DESC")
	{

		$items = parent::Paging($limit,'name, page_url, count', "" ,$page_link, $order);
		$this->smarty->assign("items",$items);
		$this->smarty->clearCache($template);
		$this->smarty->display($template);
	}

}
?>