<?php
class manage_module extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
             if(!$this ->isOOPS()) {alert(ERROR_PERMISSION_MODULE);exit();}

		$table = "tbl_sys_menu";
		parent::__construct($smarty, $db, $datagird, $table);
		parent::getRootPath();
		$this->img_path = SITE_DIR."upload/admin/";
		$this->img_path_short = SITE_DIR."/upload/admin/";
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
				$this -> changestatus($_GET['id'], $_GET['status']);
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
	
	function getPageInfo()
	{
		return true;
	}

	function addItem()
	{
		$this -> buildForm();
	}
	
	function editItem()
	{
		$id = $_GET['id'];
		$row = $this ->getById( $id );
		$this -> buildForm( $row );
	}

	function deleteItem()
	{

		$id = $_GET["id"];
		/*$sql = "delete from tbl_usertype_moduleroll where module_roll_id in (select id from tbl_sys_module_roll where module_id ='{$id}')";
		$res = $this -> db -> query( $sql );
		$sql = "delete from tbl_sys_module_roll where module_id ='{$id}'";
		$res = $this -> db -> query( $sql );*/
        $sql = "SELECT id FROM {$this->table} WHERE parent_id=$id";
        $arr_id_to_del = $this -> db->getCol($sql);
        $id_to_del = implode(",",$arr_id_to_del);
        if($id_to_del != "")
            $id_to_del .= ",".$id;
        else
            $id_to_del = $id;
        $this -> db->query("DELETE FROM tbl_sys_users_module WHERE ModuleID IN ($id_to_del)");
        $this -> db->query("DELETE FROM tbl_sys_module_roll WHERE module_id IN ($id_to_del)");
		$this -> aDb -> deleteWithPk( $id_to_del );
		$msg = "Item has been deleted at ". date('Y-m-d h:i:s');
		$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
	}
	
	function deleteItems()
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',', $aItems );
            $sql = "SELECT id FROM {$this->table} WHERE parent_id IN ($sItems)";
            $arr_id_to_del = $this -> db->getCol($sql);
            $id_to_del = implode(",",$arr_id_to_del);
            $id_to_del .= ",".$sItems;
            $this -> db->query("DELETE FROM tbl_sys_users_module WHERE ModuleID IN ($id_to_del)");
            $this -> db->query("DELETE FROM tbl_sys_module_roll WHERE module_id IN ($id_to_del)");
			$this -> aDb -> deleteWithPk( $id_to_del );
		}
		$msg = "Item(s) has been deleted successfull!";
		$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
	}
	
	function changestatusMultiple( $status = 0 )
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',', $aItems );
			$this -> aDb -> updateWithPk( $sItems, array("status" => $status) );
		}
		$msg = "Item(s) has been change status successfull!";
		$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
	}
	
	function saveOrder(){
		$aItem = $_GET['zindex'];
		if(is_array($aItem) && count( $aItem ) > 0){
			// save order for item.
			foreach( $aItem as $key => $value){
				if( !is_numeric($value)) $value = 0;
				$this -> aDb -> updateWithPk( $key, array('zindex' => $value ));
			}
		}
		$msg = "Item(s) has been save order successfull!";
		$this -> listItem( $msg );
	}
	
	function getRoll($moduleId){

		$sResult = "";
		$stbl1 = 'tbl_sys_roll';
		$aChecked = array();
		if( $moduleId ){
			$stbl2 = 'tbl_sys_module_roll';
			if($moduleId ) $where = " and module_id = '{$moduleId}'";
			$sql = "SELECT t1.id FROM {$stbl1} t1 join (SELECT * FROM {$stbl2} WHERE 1 {$where}) t2 on(t1.id = t2.roll_id) WHERE 1";
			$aChecked = $this -> db -> getCol( $sql );
		}
		
		$sql = "SELECT  id, name FROM {$stbl1} WHERE 1 ORDER BY ordered";
		$result = $this -> db -> getAssoc( $sql );
		if(count($result) > 0){
			foreach ( $result as $key => $val){
				if( in_array( $key, $aChecked )) $sChecked = "checked=\"checked\"";
				else $sChecked = "";
				$sResult .= "<input type=\"checkbox\" name=\"module_roll[]\" value=\"{$key}\" {$sChecked}>{$val} &nbsp;&nbsp;&nbsp;";
			}
		}
		
		return $sResult;
	}
	
	function removeRoll( $moduleId ){
		$stbl ="tbl_sys_module_roll";
		if( $moduleId ){
			$sql = "DELETE FROM {$stbl} WHERE module_id = '{$moduleId}'";
			$this -> db -> query ( $sql );
		}
	}
	
	function addRoll( $moduleId, $aRollId ){
		$stbl = "tbl_sys_module_roll";
		foreach( $aRollId as $key => $val ){
			$sql = "INSERT INTO {$stbl}(module_id, roll_id) VALUES ('{$moduleId}', '{$val}')";
			$this -> db -> query ( $sql );
		}
	}
	
	function buildForm( $data=array() , $msg = ''){
		
		$form = new HTML_QuickForm('frmAdmin','post',$_COOKIE['re_dir']."&task=".$_GET['task'], '', "style='padding:10px 15px 0 20px;'");
		
		$form -> setDefaults($data);
		$form -> addElement('text', 'name', 'name', array('size' => 50, 'maxlength' => 255));
		$form -> addElement('text', 'name_en', 'name_en', array('size' => 50, 'maxlength' => 255));
		$form -> addElement('text', 'link', 'link', array('size' => 50, 'maxlength' => 255));
		$aParent = array(0 => "- - - Root Module - - -" ) + $this->getParentModule();
		
		$form -> addElement('select', 'parent_id', 'Parent', $aParent);
		$form->addElement('file', 'photo', 'photo');
		
		if($_GET['task']=='edit' && $data['photo'])
			$form->addElement('static', null, '',"<a href='".SITE_URL.$data['photo']."' onclick='return hs.expand(this)' class='highslide'><img src='".SITE_URL.$data['photo']."' width=70 hight=70 border=0></a>");
		$form -> addElement('text', 'zindex', 'Order', array('size' => 10, 'maxlength' => 50));
		$form -> addElement("static", null, "Select Roll", $this -> getRoll( $data['id']));
		$form -> addElement('checkbox', 'ishome', 'Show Home Admin Page');
		$form -> addElement('checkbox', 'status', 'status');
		
		$btn_group[] = $form -> createElement('submit',null,'Save',array("style"=> "border:1px solid gray; padding:0 5px 0 5px;"));
        $btn_group[] = $form -> createElement('button',null,'Go Back',array('onclick'=>'window.location.href = \''.$_COOKIE['re_dir'].'\'', "style"=> "border:1px solid gray;"));      
        $form -> addGroup($btn_group);
      
		$form->addElement('hidden', 'id', $data['id']);
		
        $form -> addRule('title','Title cannot be blank','required',null,'client');
		$reg = "/[a-zA-Z0-9\_]+$/";
		$form -> addRule('title', "Title can not contain special character or space", 'regex', $reg);
		$form -> addRule('order', 'Order must be a number', 'numeric');
		
		if( $form -> validate())
		{
			if($_FILES['photo']['name']!='')
			{
				$this->deleteImage($this->id, "photo", SITE_DIR);
				$Filename = rand().'_'.$_FILES['photo']['name'];
				parent::crop_image($_FILES['photo']['tmp_name'],$Filename, $this->imgPath,"70");
				$_POST['photo'] = $this->imgPathShort.$Filename;
			}
			
			$aData  = array(
				"name" => $_POST['name'],
				"link" => $_POST['link'],
				"parent_id" => $_POST['parent_id'],
				"zindex" 	=> $_POST['zindex'],
				"status" 	=> $_POST['status']
			);
			if( !$_POST['id'] ){
				
				 $id = $this -> insert($aData);
				 if( is_array($_POST['module_roll']) && count( $_POST['module_roll']) > 0){
				 	$this -> addRoll( $id, $_POST['module_roll']);
				 }
				 $msg = "Item has been inserted at ". date('Y-m-d h:i:s');
			}else {
				$id = $_POST['id'];
				$this ->updateById($aData, $id, $this -> table);
				$this -> removeRoll( $id );
				if( is_array($_POST['module_roll']) && count( $_POST['module_roll']) > 0){
				 	$this -> addRoll( $id, $_POST['module_roll']);
				}
				$msg = "Item has been updated at ". date('Y-m-d h:i:s');
			}
			
			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}

		$form -> display();
	}
	
	function getParentModule(){
		$sTbl = $this -> table;
		
		$query = "SELECT id, CONCAT('&nbsp;&nbsp;&nbsp;',name) FROM {$sTbl} WHERE parent_id=0";
		$result = $this -> db -> getAssoc( $query );
		
		return $result;
	}
	
	function changestatus( $itemId , $status ){
		$aData = array( 'status' => $status );
		$this -> aDb -> updateWithPk( $itemId, $aData );
		return true;
	}
	
	function listItem( $sMsg= '' )
	{
		$root_path = "System config > Manage Module > List Module";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = $this -> table;
		$order = ($_GET['sort_by'])?($_GET['sort_by']):'zindex';
		$orderType = $_GET['sort_value'];
		if( $_GET['filter_title']!= '')
			$where[] = " name like '{$_GET['filter_title']}'";
		if( $_GET['filter_show']!= '')
			$where[] = " status = '{$_GET['filter_show']}'";
		
		//$where[] = " Editable = '1'";
		if( is_array( $where) && count( $where )> 0)
			$condition = implode( " and ", $where );

		$aData = $this -> multiLevel( $table, "id", "parent_id", "*", "{$condition}", "{$order} {$orderType}");

		foreach ( $aData as $key => $row){
			if( $row['level'] > 0){
				$aData[$key]['name'] = $this -> getPrefix( $row['level']).$row['name'];
			}
		}
		

		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => 'name',
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			),
			array(
				'field' => 'status',
				'display' => 'status',
				'name' => 'filter_show',
				'selected' => $_REQUEST['filter_show'],
				'options' => array('No','Yes'),
				'filterable' => true
			)
			
		); 
		
		$arr_cols= array(
			
			array(
				"field" => "id",
				"primary_key" =>true,
				"display" => "id",
				"align" => "center",
				"sortable" => true
			),
			array(
				"field" => "name",
				"display" => "name",
				"align"	=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" 		=> "photo",
				"display" 		=> 'photo',
				"datatype" 		=> "img",
				"img_path"		=> SITE_URL,
				"width"			=> "70",
			),
			array(
				"field" => "zindex",
				"display" => "Order",
				"datatype" 		=> "order",
				"sortable" => true,
				"order_default" => "asc"
			),
			array(
				"field" => "status",
				"display" => "status",
				"datatype" => "publish",
				"sortable" => true
			),
			array(
				"field" => "createDate",
				"display" => "Create Date",
				"datatype" => "date",
				"sortable" => true
			)
		);
		
		$arr_check = array(
			array(
				"task" => "delete_all",
				"display" => "Xóa"
			),
			array(
				"task" => "public_all",
				"display" => "Kích hoạt"
			),
			array(
				"task" => "unpublic_all",
				"display" => "Vô hiệu"
			)
		);

		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($aData, $arr_cols, $arr_filter, $submit_url, $arr_action ,120, $root_path, false ,$arr_check);
		
	}

	function loadLang()
	{
		$lang = $this -> db -> getAssoc('select id, name from tbl_sys_lang order by id');
		return  $lang;
	}
}

?>