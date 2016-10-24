<?php
/** 
	@nxan
	@Sửa 10/4
**/
class menu extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "site_menu";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'menu';
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
			default:
				$this -> listItem( $_GET['msg'] );
				break;
		}
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
			$this -> deleteById();
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
			if(!$_POST['lang_id']) {
				 $_POST['lang_id'] = 1;
			}

			$aData  = array(
				"title" => $_POST['title'],
				"lang_id" => $_POST['lang_id'],
				"site_id" => $this -> site_id,
				"page_url" => $_POST['page_url'],
				"parent_id" => $_POST['parent_id'],
				"avatar" => $_POST['avatar'],
				"ordering" => $_POST['ordering'],
				"status" => $_POST['status']
			);

			if( !$_POST['id'] ){
				 $this -> id = $this -> insert($aData);
			}else {
				$this -> updateById($aData);
			}

			$this -> backPageList();
		}

		$this -> assign('detail', $data);
		$this -> assign('lang', parent::getAssocLang());
		$this -> assign('menu', $this -> getCategory());
		$this -> display('menu.backend.tpl');
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
				$title = $this -> getPrefix( $key['level']).$key['title'];
			}
			else 
				$title = $key['title'];
			$category[$key['id']] = $title;
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

	function getRootByParentId($parentId, $title)
	{
		$root = "";
		if ($parentId == 0) {
			$root = $title;
		}else {
			$str = $this -> db -> getOne("SELECT title FROM ". $this -> table ." WHERE id = {$parentId}");
			$root =  $str. ' >> ' . $title;
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
			$where[] = " title like '%{$_GET['filter_title']}%'";
		if( $_GET['filter_show']!= '')
			$where[] = " status = '{$_GET['filter_show']}'";

		if( $_GET['filter_lang']!= '')
			$where[] = " lang_id = '{$_GET['filter_lang']}'";

		if( is_array( $where) && count( $where )> 0)
			$cond = implode( " and ",$where );

		$aData = $result = parent::multiLevel($table,"id","parent_id","*",$cond,"ordering ASC");

		foreach ( $aData as $key => $row){
			if( $row['level'] > 0){
				$aData[$key]['title'] = $this -> getPrefix( $row['level']).$row['title'];
			}
		}
		
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
				"field" => "title",
				"display" => $this -> getConfigVars('categories'),
				"align"    => 'left',
				"datatype" => "text",
				"sortable" => true,
				"order_default" => "asc"
			),
			array(
				"field" => "page_url",
				"display" => "Link",
				"datatype" => "text"
			)
        );
 		
        $arr_cols_more = array( 
            array(
				"field" => "ordering",
				"display" => $this -> getConfigVars('ordering'),
				"datatype" 		=> "order",
				"sortable" => true
			),
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
}

?>