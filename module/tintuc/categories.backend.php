<?php
/** 
	@nxan
	@Sửa 10/4
**/
class categoriesBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "categories";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> type = 'tin-tuc';
		$this -> mod = 'tintuc';
		$this -> img_path = "upload/categories";
		$this -> img_path_thumb = "/upload/categories/thumb";
		$this -> tbl_article = 'articles';
	}
	function run($task)
	{
		switch( $task ){
			case 'add':
				$this -> addItem();
				break;
			case 'edit':
				$this -> editItem();
				break;
			case 'delete':
				$this -> deleteItem();
				break;
			case 'editor':
				$this -> editorItem();
				break;
			case 'ajax_form':
				$this->ajax_form();
				break;
			default:
				$this -> listItem( $_GET['msg'] );
				break;
		}
	}

    function ajax_form()
    {
        $lang_id = $_REQUEST['lang_id'];
        $category = array(0 => "- - - Quản lý danh mục gốc - - -" ) + $this->getAssocCategory($lang_id);
        $option_str = parent::changeOption($category);
        echo $option_str;
    }

	function addItem()
	{
	
		$this -> buildForm();
	}
	
	function editItem()
	{
		$this -> buildForm($this -> getById());
	}
	
	function hasSubItem($parent_id)
	{
		$sub = $this -> db->getOne("SELECT count(*) FROM {$this->table} WHERE parent_id = {$parent_id}");
		$item = $this -> db->getOne("SELECT count(*) FROM {$this->table} WHERE parent_id = {$parent_id}");
		
		if ($sub>0 || $item>0)
			return true;
		else
			return false;
	}

	function deleteItem()
	{
		$id = $_GET["id"];

		if (!$this->hasSubItem($id)) {
			$this -> deleteByid();
			$msg = "Xóa Quản lý danh mục thành công!";
		}
		else 	
		
			$msg = "Không thể xóa danh mục này vì còn chứa danh mục con hoặc sản phẩm";
		$this -> listItem( $msg );
	}

	function buildForm( $data=array() ,$msg = '')
	{

		if( $this -> isPost())
		{
			if($_FILES['avatar']['name']!='')
			{
				$this -> deleteImage($this->id,"avatar",$this->img_path);
				$this -> deleteImage($this->id,"avatar",$this->img_path_thumb);
				$Filename = rand().'_'.$_FILES['avatar']['name'];
				parent::crop_image($_FILES['avatar']['tmp_name'],$Filename, SITE_DIR.$this -> img_path,800,600,false);
				$file_name = parent::crop_image($_FILES['avatar']['tmp_name'],$Filename, SITE_DIR.$this -> img_path_thumb,150,200,false);
				$_POST['avatar'] = $this -> img_path_thumb . '/' . $file_name;
			}

			if(!$_POST['lang_id']) {
				 $_POST['lang_id'] = 1;
			}

			$aData  = array(
				"name"      => $_POST['name'],
				"lang_id"   => $_POST['lang_id'],	
				"lead"      => $_POST['lead'],
				"title"     => $_POST['title'],
				"keyword"   => $_POST['keyword'],
				"parent_id" => $_POST['parent_id'],
				"is_home"   => $_POST['is_home'],
				"ordering"  => $_POST['ordering'],
				"status"    => $_POST['status'],
				"type"      => $this -> type,
			);

			$aData['root'] = $this -> getRootByParentId($_POST['parent_id'], $_POST['name']);

			if ($_POST['avatar']!='')
				$aData['avatar'] = $_POST['avatar'];

			if( !$_POST['id'] ){
				$id = $this -> insert($aData);
			}else {
				$this -> updateById($aData);
			}

			$aData  = array(
				"page_url" => '/'.strtolower(parent::removeMarks(stripcslashes($_POST['name']))).'-c'.$this -> id.'/'
			);
			$this -> clearCacheCate();
			$this -> updateById($aData);
			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}

		$this -> assign('detail', $data);
		$this -> assign('lang', parent::getAssocLang());
		$this -> assign('categories', $this -> getCategory());
		$this -> assign('ordering', range(1, 30));
		$this -> display('backend/categories.backend.tpl');
	}

	function clearCacheCate() {
		global $cache;
		$cache -> delete("news_cate_{$this -> id}", "cate");
		$cache -> delete("site_menu", "menu");
	}
	
	function deleteImage($id,$field,$path){
		if($id == '')
			return;
		$imgpath = $path.$this->db->getOne("SELECT $field FROM ".$this->table." WHERE id = $id");
		if(is_file($imgpath))
			@unlink($imgpath);
	}
	
	function getCategory($lang_id = 0 )
	{
		$cond = "site_id = {$this -> site_id}";
		$result = parent::multiLevel( $this->table,"id","parent_id","*",$cond,"ordering ASC");
		
		$category = array();
		foreach ($result as $value => $key)
		{
			if( $key['level'] > 0){
				$name = $this -> getPrefix( $key['level']).$key['name'];
			}
			else 
				$name = $key['name'];
			$category[$key['id']] = $name;
		}
		
		return $category;
	}

	function getAssocCategory($parent_id = -1, $type = -1)
	{
		$sql = "SELECT id, root,parent_id from " . $this -> table . " WHERE type = '" . $this -> type . "' AND status = 1 ";
		if ($type >= 0) {
			$sql .= " AND type = ".$type;
		}
		if ($parent_id > 0) {
			$sql .= "AND parent_id = ".$parent_id;
		}
		$sql .= " order by root, ordering";
		//echo $sql;
		$arr = $this -> db -> getAll($sql);
		$array[0] = "---------Chọn chuyên mục ----------";
		for($i = 0; $i < count($arr); $i++)
		{
			$array[$arr[$i]['id']] = $arr[$i]['root'];
		}
		return $array;
	}

	function getRootByParentId($parentId, $name)
	{
		$root = "";
		if ($parentId == 0) {
			$root = $name;
		}else {
			$str = $this -> db -> getOne("SELECT name FROM ". $this -> table ." WHERE id = {$parentId}");
			$root =  $str. ' >> ' . $name;
		}
		return $root;
	}

	function listItem( $sMsg= '' )
	{
		$this -> loadConfig();
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table} WHERE site_id = '{$this -> site_id}') a";

		$order = ($_GET['sort_by'])?($_GET['sort_by']):'ordering';
		$orderType = $_GET['sort_value'];
		if( $_GET['filter_title']!= '')
			$where[] = " name like '%{$_GET['filter_title']}%'";
		if( $_GET['filter_show']!= '')
			$where[] = " status = '{$_GET['filter_show']}'";

		if( $_GET['filter_lang']!= '')
			$where[] = " lang_id = '{$_GET['filter_lang']}'";

		if( is_array( $where) && count( $where )> 0)
			$cond = implode( " and ",$where );

		$aData = $result = parent::multiLevel($table,"id","parent_id","*",$cond,"ordering ASC");

		foreach ( $aData as $key => $row){
			if( $row['level'] > 0){
				$aData[$key]['name'] = $this -> getPrefix( $row['level']).$row['name'];
			}
		}
		
		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => 'Tên Quản lý danh mục',
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);
        if(MULTI_LANGUAGE)
            $arr_filter[] = array(
                    'field' => 'lang_id',
                    'display' => $this -> getConfigVars('language'),
                    'name' => 'filter_lang',
                    'selected' => $_REQUEST['filter_lang'],
                    'options' => parent::getAssocLang(),
                    'filterable' => true
                );
           
		$arr_cols= array(
			array(
				"field" => "root",
				"display" => $this -> getConfigVars('categories'),
				"align"    => 'left',
				"datatype" => "text",
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&task=edit',
				"sortable" => true,
				"order_default" => "asc"
			),
			array(
				"field" => "page_url",
				"display" => "Link",
				"datatype" => "link"
			)
        );
 		
        $arr_cols_more = array( 
        	array(
				"field" => "status",
				"display" => "Trạng thái",
				"datatype" => "publish",
				"sortable" => true,
				"align"=> 'center'
			)
        );
		$arr_cols = array_merge($arr_cols,$arr_cols_more);

		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($aData,$arr_cols,$arr_filter,"?".$_SERVER['QUERY_STRING'],$arr_action,null,$root_path,false);
	}

	function editorItem()
	{
		if(isset($_GET['ordering'])) {
			$aItem = $_GET['ordering'];
			$list_id = "0";
			if(is_array($aItem) && count( $aItem ) > 0){
				// save order for item.
				foreach( $aItem as $key => $value){
					if( !is_numeric($value)) $value = 30;
					$this -> updateById(array('ordering' => $value ),$key, $this -> tbl_article);
					$list_id .= "," .$key;
				}

				if($_GET['page'] == 1 && isset($_GET['id'])) {
					$this -> updateField("cate_id = {$_GET['id']} AND id not in ({$list_id})", array('ordering' =>  '30'), $this -> tbl_article);
				}
			}
		}

		$this -> loadConfig();
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];

		$table = "(SELECT * FROM {$this -> tbl_article} WHERE site_id = '{$this -> site_id}' AND cate_id = ".$_GET['id']." order by ordering ASC) a";

		$arr_filter= array(
			array(
				'field' => 'title',
				'display' => $this -> getConfigVars('title'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);

		$arr_cols= array(
			array(
				"field" => "id",
				"primary_key" =>true,
				"display" => "Id",
				"align" => "center",
				"sortable" => false,
				"order_default" => "DESC"
			),
			array(
				"field" => "title",
				"display" => $this -> getConfigVars('title'),
				'link' => SITE_URL.'index.php?mod=admin&amod=article&atask=article&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => false
			),
			array(
				"field" => "page_url",
				"display" => "Link",
				"datatype" => "link"
			),
			array(
				"field" => "ordering",
				"display" => $this -> getConfigVars('ordering'),
				"datatype" => "order",
				"sortable" => false
			),
			array(
				"field" => "status",
				"display" => $this -> getConfigVars('status'),
				"datatype" => "publish",
				"align"=> 'center'
			)
		);

		$this -> datagrid -> display_datagrid($table,$arr_cols,$arr_filter,"?".$_SERVER['QUERY_STRING'],$arr_action,null,$root_path,false);
	}
}

?>