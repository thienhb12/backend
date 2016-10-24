<?php
class product_base extends Anpro_Module_Base 
{
	private $tbl_cate;
	private $tbl_attr;
	private $_list_attribute;
	public function __construct($smarty,$db,$datagird = null)
	{
		$table = "product";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'product';
		$this -> tbl_cate = 'product_type';
		$this -> tbl_attr = 'product2step2attribute';
	}

	function getDetailProductTypeById($id) {
		$sql = "SELECT * FROM {$this -> tbl_cate} WHERE id = {$id} AND status = 1 ";

		$detail = $this -> db -> getRow($sql);
		return $detail;
	}

	function getProductTypeBestFather(&$product_type_id) {
		if(!is_numeric($product_type_id)) {
			return;
		}

		$sql = "SELECT parent_id FROM {$this -> tbl_cate} WHERE status = 1 ";
		$sql .= " AND id = ".$product_type_id;
		//echo $sql;
		$parent_id = $this -> db -> getOne($sql);

		if($parent_id == 0) {
			return $product_type_id;
		}else {
			$product_type_id = $this -> getProductTypeBestFather($parent_id);
		}

		return $product_type_id;
	}

	function getListTitle ($condition = "status = 1", $limit = 10, $order = "ordering, create_date DESC")
	{
		if (MULTI_LANGUAGE)
			$condition .= " AND lang_id ={$this -> lang_id} ";

		$sql = "SELECT id, title, page_url, avatar, sale_price, price FROM {$this -> table} WHERE {$condition} ORDER BY {$order} limit {$limit}";
		$items = $this -> db -> getAll($sql);
		return $items;
	}


	function getListTitleHasCate ($condition = "status = 1", $limit = 10, $order = "a.ordering, a.create_date DESC")
	{
		if (MULTI_LANGUAGE)
			$condition .= " AND lang_id ={$this -> lang_id} ";

		$sql = "SELECT a.id, a.title, a.page_url, a.price , a.sale_price, a.avatar, c.name cateTitle, c.page_url cateUrl FROM articles a, categories c WHERE {$condition} AND c.id = a.product_type_id  ORDER BY {$order} limit {$limit}";
		//echo $sql;
		$items = $this -> db -> getAll($sql);
		return $items;
	}

	/**
	 *		@param id của ảnh
	 *		@return Url của ảnh
	 */  
	function getUrlImageById($image_id) {
		 $row = $this -> getById($image_id, 'images');
		 return $row['url'];
	}

	function getImageByProductId($product_id, $limit = 5) {
		$sql = "SELECT i.url, i.id, p2i.position FROM product2image p2i, images i  WHERE product_id = {$product_id} AND p2i.image_id = i.id ORDER BY p2i.id limit 5";
		return $this -> db -> getAll($sql);
	}

	function getContentByProductId ($product_id){
		return $this -> db -> getOne("SELECT content FROM product_content WHERE product_id = " . $product_id);
	}

	function multiLevel($parentId = 0, $level=0){
		$aResult = array();
		$this -> getCategoryMultiLevel($aResult, $parentId, $level);
		return  $aResult;
	}
	
	function getCategoryMultiLevel( &$aRef, $parentId, $level=0){
		if( $level == 0)
			$sql = "SELECT * FROM product_type WHERE (parent_id = '0' or `parent_id` is NULL)  AND status = 1 order by ordering";
		else
			$sql = "SELECT * FROM product_type WHERE parent_id = '{$parentId}' AND status = 1  order by ordering";

		//echo $sql;
		$result = $this -> db -> getAll( $sql );
		if( $result ){
			foreach ( $result as $key => $val){
				$val['level'] = $level;
				$aRef[] = $val;
				$this -> getCategoryMultiLevel( $aRef, $val['id'], $level + 1);
			}
		} 
	}

		/**
	 *		Lấy danh sách các trường dữ liệu có như cầu tìm kiếm
	 *
	 *		@param 
	 *		@return Trả về danh sách các trường dữ liệu
	 */  
	function getAttribueByProductType() {
		 $search_by_attribute = array();
		 $i = 0;
		if(is_numeric($_GET['id'])) {
			$sql = "SELECT step_id FROM product_type2step WHERE product_type_id = " . $_GET['id'];
		}

		$arr_step_id = $this -> db -> getCol($sql);
		$arr_attr_id = array();

		if(is_array($arr_step_id) && count($arr_step_id) > 0) {
			foreach ($arr_step_id as $key => $val) {
				$sql = "SELECT a.source, a.name, a.id FROM `step2attribute` sa, attribute a WHERE a.required = 1 AND source like '%@@@@%' AND a.id = sa.attribute_id AND `step_id` = " . $val . " order by sa.id ASC limit 0,1";
				//echo $sql;
				$attr = $this -> db -> getAll($sql);
				
				foreach ($attr as $k => $v) {
					$search_by_attribute[$i]['name'] = $v['name'];
					$search_by_attribute[$i]['id'] = $v['id'];
					$search_by_attribute[$i]['source'] = $this -> processSourceAttribute($v);
					$i ++;
				}
			}

			return $search_by_attribute;
		}

		return ;
	}

	function processSourceAttribute($attr) {
		$attr_task = 'a_'.$attr['id'];
		$sources = explode('@@@@', $attr['source']);
		$arr_source;
		if($_GET[ $attr_task] != '') {
			$sources_active = explode(',', $_GET[ $attr_task]);
		}
		foreach ($sources as $k => $v ) {
			$arr_source[$k]['val'] = trim($v);
			$arr_source[$k]['active'] = 'unchecked';
			if(isset($sources_active) && is_array($sources_active)) {
				$arr_source[$k]['sources_active'] = $sources_active;
				foreach ($sources_active as $key => $val ) {
					if($arr_source[$k]['val'] == $val) {
						$arr_source[$k]['active'] = 'check';
					}
				}
			}
			
		}
		return $arr_source;
	}

	function getProductIdByAttribute() {
		$arr_attr = $this -> getAttribueByProductType();
		$sql = "";
		$list_product_id = array();
		$source_active = array();
		foreach ($arr_attr as $attr) {
			$temp = "";
			
			foreach ($attr['source'] as $val) {
				if($val['active'] == 'check') {
					array_push($source_active, "'" . $val['val'] . "'");
				}
			}
			/*if(count( $source_active) > 0) {
				$temp =  "SELECT product_id FROM product2step2attribute WHERE 1 AND attribute_id = " . $attr['id'] . " AND value in (" . implode(",", $source_active) .")";
			}

			if($sql != "") {
				if($temp != "") {
					$sql = $temp . " AND product_id in ($sql)";
				}
			}
			else {
				$sql = $temp;
			}*/
		}
		//echo $sql;
		return implode(",", $source_active);
	}

	function buildAttribute($product_type_id, $product_id = null) {
		$list_step = $this -> getStepByProduct($product_type_id);
		$list_attr = $this -> getAttributeByProduct($product_type_id);
		$province_id = 0;
		$district_id = 0;
		
		for($i = 0; $i < count($list_attr); $i++){
			if($product_id != null && $product_id != '') {
				$attr = $this -> getProduct2Step2Attribute($list_attr[$i]['id'], $list_attr[$i]['step_id'], $product_id);
				$list_attr[$i]['value'] = $attr['value'];
				$list_attr[$i]['step_value'] = $attr['step_value'];
			}

			switch($list_attr[$i]['type']) {
				case 'select':
					switch($list_attr[$i]['source']) {
						case 'province':
							if($_GET['province']) {
								$province_id = $this -> db -> getOne("SELECT id FROM " . $list_attr[$i]['source'] . " WHERE  name = '".$_GET['province']."' order by name ASC");

								$list_attr[$i]['value'] = $province_id;
							}
							$list_attr[$i]['source'] = $this ->getAttributeTypeOfSelect( $list_attr[$i]['source']);
						break;
						case 'district':
							if($_GET['province'] != '') {
								$province_id = $this -> db -> getOne("SELECT id FROM province WHERE name = '".$_GET['province']."' order by name ASC");

								$list_attr[$i]['value'] =  $this -> db -> getOne("SELECT id FROM " . $list_attr[$i]['source'] . " WHERE lower(name) like lower('".$_GET['district']."') AND province_id = {$province_id}");
								$list_attr[$i]['source'] =  $this -> db -> getAssoc("SELECT id, name FROM " . $list_attr[$i]['source'] . " WHERE province_id = {$province_id}");
							}elseif($list_attr[$i]['value']) {
								$list_attr[$i]['source'] =  $this -> db -> getAssoc("SELECT id, name FROM " . $list_attr[$i]['source'] . " WHERE province_id = (SELECT province_id FROM " . $list_attr[$i]['source'] . " WHERE id = " . $list_attr[$i]['value'] . ")");
							}else {
								$list_attr[$i]['source'] = $this ->getAttributeTypeOfSelect( $list_attr[$i]['source']);
							}
						break;
						case 'ward':
							if($_GET['district'] ) {
								$district_id = $this -> db -> getOne("SELECT id FROM district WHERE name = '".$_GET['district']."' order by name ASC");

								$list_attr[$i]['value'] =  $this -> db -> getOne("SELECT id FROM " . $list_attr[$i]['source'] . " WHERE lower(name) like lower('".$_GET['ward']."') AND province_id = {$district_id}");

								$list_attr[$i]['source'] =  $this -> db -> getAssoc("SELECT id, name FROM " . $list_attr[$i]['source'] . " WHERE district_id = {$district_id}");
							}elseif($list_attr[$i]['value']) {
								$list_attr[$i]['source'] =  $this -> db -> getAssoc("SELECT id, name FROM " . $list_attr[$i]['source'] . " WHERE district_id = (SELECT district_id FROM " . $list_attr[$i]['source'] . " WHERE id = " . $list_attr[$i]['value'] . ")");
							}else{
								$list_attr[$i]['source'] = $this ->getAttributeTypeOfSelect( $list_attr[$i]['source']);
							}
						break;
						default:
							$list_attr[$i]['source'] = explode('@@@@', $list_attr[$i]['source']);
							for($j = 0; $j < count($list_attr[$i]['source']); $j++) {
								$list_attr[$i]['source'][$j] = rtrim($list_attr[$i]['source'][$j]);
								if(rtrim($list_attr[$i]['value']) == rtrim($list_attr[$i]['source'][$j]))
									$list_attr[$i]['value'] = $list_attr[$i]['source'][$j];
							}
					}
				break;
				case 'coordinates':
					$list_attr[$i]['source'] = explode('@@@@', $list_attr[$i]['value']);
					if($_GET['kinhdo'] && $_GET['vido']) {
						$list_attr[$i]['source'][0] = $_GET['kinhdo'];
						$list_attr[$i]['source'][1] = $_GET['vido'];
					}
				break;
				case 'file':
				case 'image':
					$list_attr[$i]['value'] = explode('@@@@', $list_attr[$i]['value']);
				break;
				case 'checkbox':
					$list_attr[$i]['source'] = $this ->getAttributeTypeOfCheckbox( $list_attr[$i]);
					if($list_attr[$i]['source'] != '') {
						if(strpos($list_attr[$i]['value'], '@@@@') > 0){
							$val= explode('@@@@', $list_attr[$i]['value']);
							for($j = 0; $j < count($list_attr[$i]['source']); $j++) {
								$val2[$j] = "";
								for($k = 0; $k < count($val); $k++) {
									if(rtrim($val[$k]) == rtrim($list_attr[$i]['source'][$j]))
										$val2[$j] = $list_attr[$i]['source'][$j];
								}
							}
							$list_attr[$i]['value'] = $val2;
						}
					}
				break;
				case 'radio':
					$list_attr[$i]['source'] = $this ->getAttributeTypeOfCheckbox( $list_attr[$i]);
					for($j = 0; $j < count($list_attr[$i]['source']); $j++) {
						if(rtrim($list_attr[$i]['value']) == rtrim($list_attr[$i]['source'][$j]))
							$list_attr[$i]['value'] = $list_attr[$i]['source'][$j];
					}
				break;
			}
		}

		$this -> assign('list_step', $list_step);
		$this -> assign('list_attr', $list_attr);
	}

	function getProduct2Step2Attribute($attribute_id, $step_id, $product_id) {
		$sql = "SELECT * FROM {$this -> tbl_attr} WHERE product_id = {$product_id} AND  step_id = {$step_id} AND  attribute_id = {$attribute_id}";
		//echo $sql;
		$detail = $this -> db -> getRow($sql);
		return $detail;
	}

	function getAttributeByProductId($product_id) {
		$sql = "SELECT pst.*, a.* FROM {$this -> tbl_attr} pst, attribute a WHERE pst.product_id = {$product_id} AND pst.attribute_id = a.id";
		$items = $this -> getAll($sql);
		return $items;
	}

	function getAttributeTypeOfSelect($table) {

		if($table == 'province')
			$sql = "SELECT id, name FROM " . $table . " order by name ASC";
		else 
			$sql = "SELECT id, name FROM " . $table . " order by name ASC";
		$source = $this -> db -> getAssoc($sql);

		return $source;
	}

	function getAttributeTypeOfCheckBox($item) {
		if($item['source'] != '')
			return explode('@@@@', $item['source']);

		return "";
	}

	// Danh sách các loại hình
	function getAssoscTicketType() {
		$sql = "SELECT id, name FROM ticket_type WHERE lang_id = {$this -> lang_id} AND status = 1";
		$list_ticket_type = $this -> db -> getAssoc($sql);
		return $list_ticket_type;
	}

	function getStepByProduct($product_type_id) {
		$sql = "SELECT * FROM product_type2step ts, step s WHERE ts.product_type_id = {$product_type_id} AND  s.id = ts.step_id order by ts.id";

		$list_step = $this -> db -> getAll($sql);
		return $list_step;
	}

	function getAttributeByProduct($product_type_id) {
		$sql = "SELECT a.*, sa.step_id FROM step2attribute sa, attribute a WHERE step_id in(SELECT s.id FROM product_type2step ts, step s WHERE  ts.product_type_id = {$product_type_id} AND s.id = ts.step_id) AND a.id = sa.attribute_id order by sa.id";
		//echo $sql;

		$list_attribute = $this -> db -> getAll($sql);
		
		return $list_attribute;
	}

	/** 

	* Lấy tất cả các kết con của một chuyên mục.
	* Param $parent_id, $first
	*/
	function getFullCatId($parent_id, $first = true)
	{
		$where = "status=1";

		if (MULTI_LANGUAGE)
			$where .= " AND lang_id = {$this->lang_id} ";

		$str_cat = "";
		$sql = "SELECT id FROM {$this->tbl_cate} WHERE {$where} AND parent_id={$parent_id} ORDER BY ordering";
		$cat = $this->db->getCol($sql);
		if ($cat)
		{
			if( is_array($cat) && count( $cat )> 0)
				$str_cat = implode(',',$cat);
			$temp_str_cat = "";
			foreach ($cat as $key=>$value)
			{
				$temp_str_cat .= $this->getFullCatId($value,false);
			}
			$str_cat .=",".$temp_str_cat;
		}

		return $str_cat;
	}

	function getRootNav($cat_id)
	{
		$where = "status=1";

		$sql = "SELECT name, page_url, parent_id FROM {$this->tbl_cate} WHERE {$where} AND id={$cat_id}";
		$row = $this -> db -> getRow($sql);

	/*	if ($row['parent_id'] != 0)
			$result = $this -> getRootNav($row['parent_id']).'<li><a href="'.$row['page_url'].'" class="second">'.$row['name'].'</a></li>';*/
		else 
			$result = '<li class="active"><a href="'.$row['page_url'].'">'.$row['name'].'</a></li>';
		return $result;
	}
}
?>