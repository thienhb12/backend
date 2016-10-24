<?php


class product extends Anpro_Module_Base 
{
	public function __construct($smarty,$db)
	{
		parent::__construct($smarty,$db);
	}

	function run($task= "", $other)
	{
		if($task==''){$task = $_GET['task'];}
		switch($task)
			{
				case 'home':
					$this -> home();
				break;
				case 'category_product':
					$this -> category_product();
				break;
				case 'hot':
					$this -> hot();
				break;
				case 'nav':
					$this -> getNav();
				break;
				case 'product_type':
					$this -> getProductType();
				break;
				case 'detail':
					$this -> detail();
				break;
				case 'list':
					$this->listItem($_GET['id']);
				break;
				default:	
					$this->listItem();
				break;
			}
	}

	function home(){
		 if (CHECK_DEVICE == 1) {
		 	$this -> display('pc/home.tpl');
		 }
		
	}

	function category_product(){
		if (CHECK_DEVICE == 1) {
		 	$this -> display('pc/category_product.tpl');
		}
	}

	function getLastest()
	{
		$template = 'lastest_product.tpl';
		$product_hot = $this -> getListTitle("status = 1 AND (product_type_id = {$cates[$i]['id']} OR product_type_id in (SELECT id FROM product_type WHERE parent_id = {$cates[$i]['id']})) ", 7);
		$this -> assign('product_hot', $product_hot);
		$this -> display($template, 0);
	}

	function getCateLastest($task, $product_type_id)
	{
	
		$template = $task.'_lastest_product.tpl';
		$full_id = $this->getFullCatId($product_type_id);
		$product_lastest_list = $this -> getListTitle("status = 1 AND product_type_id in ({$full_id}) ", 10);
		$product_type = $this -> getDetailProductTypeById($product_type_id);
		$this -> assign('product_type', $product_type);
		
		if(count($product_lastest_list) > 0) {
			$this -> assign('product_lastest_list', $product_lastest_list);
			$this -> display($template );
		}
		
	}
	function getFullCatId($cat_id,$first = true)
	{
        $where = "status=1";
		$str_cat = "";
		$sql = "SELECT id FROM product_type WHERE {$where} AND parent_id={$cat_id} ORDER BY ordering";
		//echo $sql;
		$cat = $this -> db -> getCol($sql);
		if ($cat)
		{
			$str_cat = implode(',',$cat);
			$temp_str_cat = "";
			foreach ($cat as $key=>$value)
			{
				$temp_str_cat .= $this->getFullCatId($value,false);
			}
			$str_cat .=",".$temp_str_cat;
		}
		if ($first)  
			$str_cat .=$cat_id;
		return $str_cat;
	}
	function hot()
	{
		$template = 'hot_product.tpl';
		if(isMobile()) {
			$template = 'hot_product_mobile.tpl';
		}
		$product_hot = $this -> getListTitle("status = 1 AND is_hot ", 10);
		$this -> assign('product_hot_list', $product_hot);
		$this -> display($template);
	}


	/**
	 *		
	 *
	 *		@param 
	 *		@return 
	 */  
	function getProductType() {
		$template = 'search_product_type_product.tpl';
		$best_farther_id = $_GET['id'];
		$best_farther_id = $this -> getProductTypeBestFather($best_farther_id);
		$product_type = $this -> multiLevel($best_farther_id,  1);
		$detail = $this -> getDetailProductTypeById($best_farther_id);
		$this -> assign('detail', $detail);
		$this -> assign('sub_cates', $product_type);
		$this -> display($template, 0);
	}

	/**
	 *		
	 *
	 *		@param 
	 *		@return 
	 */  
	function searchPrice() {
		$template = 'search_price_product.tpl';
		$this -> display($template, 0);
	}


	function detail()
	{
		$id = $_GET['id'];
		$template = 'detail_product.tpl';
		if(isset($id) && is_numeric($id)) {
			$where = "status=1";

			if (MULTI_LANGUAGE)
				$where .= " AND lang_id = {$this->lang_id} ";

			$sql = "SELECT * FROM {$this->table} WHERE {$where} AND id={$id}";
			$detail = $this -> db -> getRow($sql);
		
			$this -> assign('str_nav', $this -> getRootNav($detail['product_type_id']));

			

			if($detail['product_type_id']) {
				$this -> buildAttribute($detail['product_type_id'], $detail['id']);
				$where .= " AND product_type_id = {$detail['product_type_id']}";
			}
			$sql = "SELECT * FROM {$this->table} WHERE {$where} AND id < {$id} ORDER BY create_date DESC LIMIT 4";

			$other_item = $this -> db-> getAll($sql);

			$this->assign('other',$other_item);

			$detail['content'] = $this -> getContentByProductId($id);
			$this -> assign('image_list', $this -> getImageByProductId($id));
			$this -> assign('detail',$detail);
			if(isMobile()) $template = 'detail_product_mobile.tpl';
			$this->display($template, $id);
		}
	}

	function listItem($cate_id=0)
	{
		$url = $_SERVER['REQUEST_URI'];
		if ($_GET['page'])
			$url = str_replace("page=".$_GET['page'],"page=i++",$url);
		else {
			if(strpos($url, '?') !== FALSE) {
				$url .= '&page=i++';
			}
			else {
				$url .= '?page=i++';
			}
		}
		//$this -> assign('str_nav', $this -> getRootNav($cate_id));
		$this->getListItem($cate_id, $url);
	}

	function getListItem($cate_id =0,$page_link,$cond="",$limit= 20,$template="list_product.tpl", $order="ORDER BY id DESC")
	{
		
		
		$where = " status=1 ";

		if (MULTI_LANGUAGE)
			$where .= " AND lang_id = {$this->lang_id} ";

		if ($cate_id != 0)
		{
			$list_cate_id = $this -> getFullCatId($cate_id);
			$where .="  AND product_type_id IN ({$list_cate_id})";
		}

		if ($cond != "")
			$where .= " AND ".$cond;

		if($_GET['fprice'] && is_numeric($_GET['fprice']) && $_GET['tprice'] && is_numeric($_GET['tprice'])) {
			$where .= " AND sale_price > {$_GET['fprice']} AND  sale_price < {$_GET['tprice']}";
		}

		//$sql_attr = $this -> getProductIdByAttribute();

		if ($sql_attr != "")
			$where .= " AND brand in ({$sql_attr})";

		$items = parent::Paging($limit,'title, page_url, price, sale_price, avatar', $where,$page_link, $order);
		$this->assign("product_list",$items);
		if(isMobile()) $template = 'list_product_mobile.tpl';
		$this->display($template);
	}
}
?>