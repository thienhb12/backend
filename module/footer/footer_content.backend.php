<?php
class footer_contentBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "footer_content";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		$this ->db = $db;
		parent::getRootPath();
		$this -> mod = 'footer_content';
		$this -> type = 'tin-tuc';
		$this -> img_path = "/upload/footer/";
		$this -> img_path_thumb = "/upload/footer/";
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
			case 'delete_all':
				$this -> deleteItems();
				break;
			case 'change_status':
				$this -> changestatus($_GET['id'],$_GET['status']);
				break;
			case 'public_all':
				$this -> changestatusMultiple( 1 );
				break;
			case 'unpublic_all':
				$this -> changestatusMultiple( 0 );
				break;
			case 'save_order':
				$this -> saveOrder();
				break;
			default:
				$this -> listItem( $_GET['msg'] );
				break;
		}
	}


	function addItem(){
		$this -> buildForm();
	}
	
	function editItem(){
		$id = $_GET['id'];
		$row = $this -> aDb -> getRow( $id );
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{
		$id = $_GET["id"];
		$this -> deleteImage($id,"avatar",$this -> img_path);
		$this -> aDb -> deleteWithPk( $id );
		$msg = "Xóa  footer link thành công!";
		$this -> listItem( $msg );
	}
	
	function deleteItems()
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			for($i = 0 ; $i < count($aItems); $i++) {
				$this -> deleteImage($aItems[$i], "avatar", $this -> img_path);
				$this -> aDb -> deleteWithPk( $aItems[$i] );
			}
		}
		$msg = "Xóa (các)  footer link thành công!";
		$this -> listItem( $msg );
	}
	
	function changestatusMultiple( $status = 0 )
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',',$aItems );
			$this -> aDb -> updateWithPk( $sItems,array("status" => $status) );
		}
		$msg = "Sửa trạng thái  footer link thành công!";
		$this -> listItem( $msg );
	}

    function saveOrder(){
        $aItem = $_GET['ordering'];
        if(is_array($aItem) && count( $aItem ) > 0){
            // save order for item.
            foreach( $aItem as $key => $value){
                if( !is_numeric($value)) $value = 0;
                $this -> aDb -> updateWithPk( $key,array('ordering' => $value ));
            }
        }
        $msg = "Lưu thứ tự bài viết thành công!";
        $this -> listItem( $msg );
    }
	function buildForm( $data=array() ,$msg = ''){
	
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if($_FILES['avatar']['name']!='')
			{
				$this -> deleteImage($this -> id,"avatar",$this -> img_path);
				$Filename = rand().'_'.$_FILES['avatar']['name'];
				$_POST['avatar'] = SITE_URL.$this -> img_path.$Filename;
				move_uploaded_file($_FILES["avatar"]["tmp_name"], SITE_DIR.$this -> img_path.$Filename);
			}

			if($this ->indexOf($_POST['create_date'], ' AM') || $this ->indexOf($_POST['create_date'], ' PM')) {
				$_POST['create_date'] = str_replace(" AM", "", $_POST['create_date']);
				$_POST['create_date'] = str_replace(" PM", "", $_POST['create_date']);
				$_POST['create_date'] = $_POST['create_date'] . ':00';
			}
			
			$aData  = array(
				"lang_id"=> ($_POST['lang_id'])?$_POST['lang_id']:"1",
				"cat_id" => $_POST['cat_id'],
				"title" => $_POST['title'],		
				"lead" => $_POST['lead'],		
				"content" => $_POST['content'],	
				"create_date" => $_POST['create_date'],				
				"status" => $_POST['status'],
			);
			
			//pre($aData); exit();
			
			if ($_POST['avatar']!='')
				$aData['avatar'] = $_POST['avatar'];
			
			if( !$_POST['id'] ){
				$id = $this -> aDb -> insert($aData);
				$msg = "Thêm  footer link thành công! ";
			}else {
				$id = $_POST['id'];				
				$this -> aDb -> updateWithPk($id,$aData);
				$msg = "Chỉnh sửa  footer link thành công ";
			}

			$aData = array(
				"page_url" => '/' . $this -> type . '/'.$id.'/'.strtolower(parent::removeMarks(stripcslashes($_POST['title']))).'.html'
			);
			$this -> aDb -> updateWithPk($id,$aData);
			$this->setCache();

			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}
		$this -> assign('detail',$data);
		$parent = $this -> getCategory(0);
		$this -> assign('parent', $parent);
		$this -> clearCache('footer_admin_content.tpl');
		$this -> display('footer_admin_content.tpl');
	}
	function indexOf($str, $seach ) {
		$pos = strrpos($str, $seach);
		if($pos===false) {
			return false;
		} else {
			return substr($str, $pos+1);
		}
	}
	function getCategory($lang_id=0){
		$table = 'footer_cat';
        $cond = '';
        if ($lang_id!=0)
            $cond .= " lang_id = {$lang_id} ";
		$result = parent::multiLevel( $table, "id", "ParentID", "*", $cond, "Ordering ASC");
		
		$category = array();
		foreach ($result as $value => $key)
		{
			if( $key['level'] > 0){				
				$name = $this -> getPrefix( $key['level']).$key['title'];
			}
			else 
				$name = $key['title'];
			$category[$key['id']] = $name;
		}
		
		return $category;
	}
	function getParent()
	{
		$cat = $this->db->getAll("SELECT id,title FROM footer_cat WHERE ParentID = 0 AND status = 1 order by ordering");
		return $cat;
	}
	function deleteImage($id,$field,$path){
		if($id == '')
			return;
		$imgpath = $path.$this -> db->getOne("SELECT $field FROM ".$this -> table." WHERE id = $id");
		if(is_file($imgpath))
			@unlink($imgpath);
	}

	function changestatus( $itemId ,$status ){
		$aData = array( 'status' => $status );
		$this -> aDb -> updateWithPk( $itemId,$aData );
		return true;
	}
	
	function listItem( $sMsg= '' )
	{
		$this -> loadConfig();		
		$root_path = "Quản lý footer link > Danh sách footer link";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = $this -> table;
		$order = ($_GET['sort_by'])?($_GET['sort_by']):'ordering';
		$orderType = $_GET['sort_value'];
		if( $_GET['filter_title']!= '')
			$where[] = " title like '{$_GET['filter_title']}'";
		if( $_GET['filter_show']!= '')
			$where[] = " status = '{$_GET['filter_show']}'";
		//$where[] = " Editable = '1'";
		if( is_array( $where) && count( $where )> 0)
			$condition = implode( " and ", $where );
		
		
		$arr_filter= array(
			array(
				'field' => 'title',
				'display' => $this->getConfigVars('title'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);
		if(MULTI_LANGUAGE)
		$arr_filter[] = array(
				'field' => 'lang_id',
				'display' => $this->getConfigVars('language'),
				'name' => 'filter_cat',
				'selected' => $_REQUEST['filter_cat'],
				'options' => parent::getAssocLang(),
				'filterable' => true
			);
		$arr_cols= array(
			array(
				"field" => "id",
				"primary_key" =>true,
				"display" => "Id",
				"align" => "center",
				"sortable" => true,
			),
			array(
				"field" => "title",
				"display" => $this->getConfigVars('title'),
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "avatar",
				"display" => $this->getConfigVars('avatar'),
				"datatype" => "img"
			),
			array(
				"field" => "ordering",
				"display" => $this->getConfigVars('ordering'),
				"datatype" => "order",
				"sortable" => true
			),
			array(
				"field" => "status",
			),
			array(
				"field" => "create_date",
				"display" => "Create Date",
				"datatype" => "date",
				"sortable" => true
			)
		);
		
		$arr_check = array(
			array(
				"task" => "delete_all",
				"confirm"=> "Xác nhận xóa?",
				"display" => "Xóa"
			),
			array(
				"task" => "public_all",
				"confirm"=> "Xác nhận thay đổi trạng thái?",
				"display" => "Kích hoạt"
			),
			array(
				"task" => "unpublic_all",
				"confirm"=> "Xác nhận thay đổi trạng thái?",
				"display" => "Vô hiệu"
			)
		);

		if( $sMsg ) {
			$this -> datagrid -> setMessage( $sMsg );
			$this -> setCache();
		}
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}

?>