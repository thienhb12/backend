<?php
class manage_task extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
            if(!$this ->isOOPS()) {alert(ERROR_PERMISSION_MODULE);exit();}

		$table = "tbl_sys_roll";
		parent::__construct($smarty, $db, $datagird, $table);
		parent::getRootPath();
		$this->img_path = SITE_DIR."core/datagrid/templates/images/";
		$this->img_path_short = "/core/datagrid/templates/images/";
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
				$this -> changeStatus($_GET['id'], $_GET['status']);
				break;
			case 'public_all':
				$this -> changeStatusMultiple( 1 );
				break;
			case 'unpublic_all':
				$this -> changeStatusMultiple( 0 );
				break;
			case 'save_order':
				$this -> saveOrder();
				break;
			default:
				$this -> listItem( $_GET['msg'] );
				break;
		}
	}

	function addItem()
	{
		$this -> getPath("System Config >> Manage Module >> Add Module ");
		$this -> buildForm();
	}
	
	function editItem()
	{
		$id = $_REQUEST['id'];
		$this -> getPath("System Config >> Manage Module >> Edit Module with id: {$id}");
		$row = $this -> aDb -> getRow( $id );
        if($row["editable"] == 0)
            $this -> redirect($_COOKIE['re_dir']. "&msg=Không thể sửa task này");
        else
		    $this -> buildForm( $row );
	}

	function deleteItem()
	{

		$id = $_GET["id"];
        $row = $this -> aDb -> getRow( $id );
        if($row["editable"] == 0)
            $this -> redirect($_COOKIE['re_dir']. "&msg=Không thể xóa task này");
        else
        {
            $this -> db->query("DELETE FROM tbl_sys_module_roll WHERE roll_id IN ($id)");
            $this -> db->query("DELETE FROM tbl_sys_groups_module WHERE RollID IN ($id)");
            $this -> db->query("DELETE FROM tbl_sys_users_module WHERE RollID IN ($id)");
            $this->deleteImage($id,"icon",SITE_DIR.$this->img_path_short);
            $this -> aDb -> deleteWithPk( $id );
            $msg = "Item has been deleted at ". date('Y-m-d h:i:s');
            $this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
        }        
	}
	
	function deleteItems()
	{
		$aItems	 = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',', $aItems );
            $sql = "SELECT count(id) FROM tbl_sys_roll WHERE editable=0 AND id IN ($sItems)";
            $check = $this -> db->getOne($sql);
            if($check)
            {
                $msg = "Không thể xóa 1 trong số task này!";
            }else
            {
                $this->deleteImage($sItems,"icon",SITE_DIR.$this->img_path_short);
                $this -> aDb -> deleteWithPk( $sItems );
                $msg = "Item(s) has been deleted successfull!";
            }            
		}
		$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
	}
	
	function changeStatusMultiple( $status = 0 )
	{
		$aItems	 = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',', $aItems );
			$this -> aDb -> updateWithPk( $sItems, array("Status" => $status) );
		}
		$msg = "Item(s) has been change status successfull!";
		$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
	}
	
	function saveOrder(){
		$aItem = $_GET['ordered'];
		if(is_array($aItem) && count( $aItem ) > 0){
			// save order for item.
			foreach( $aItem as $key => $value){
				if( !is_numeric($value)) $value = 0;
				$this -> aDb -> updateWithPk( $key, array('ordered' => $value ));
			}
		}
		$msg = "Item(s) has been save order successfull!";
		$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
	}
	
	function buildForm( $data=array() , $msg = ''){
		$form = new HTML_QuickForm('frmAdmin','post',$_COOKIE['re_dir']."&task=".$_GET['task'], '', "style='padding:10px 15px 0 20px;'");
		
		$form -> setDefaults($data);
		$form -> addElement('text', 'name', 'Name', array('size' => 50, 'maxlength' => 255));
		$form -> addElement('text', 'task', 'Task', array('size' => 50, 'maxlength' => 255));
        $form -> addElement('text', 'confirm', 'Confirm', array('size' => 50, 'maxlength' => 255));
		$form->addElement('file', 'icon', 'Icon');
		
		if($_GET['task']=='edit')
			$form->addElement('static', null, '',"<a href='".SITE_URL.$this->img_path_short.$data['icon']."' onclick='return hs.expand(this)' class='highslide'><img src='".SITE_URL.$this->img_path_short.$data['icon']."' width=70 hight=70 border=0></a>");
		$form -> addElement('text', 'ordered', 'Order', array('size' => 10, 'maxlength' => 50));
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
            $aData  = array(
                "name" => $_POST['name'],
                "task" => $_POST['task'],
                "confirm" => $_POST['confirm'],
                "ordered"     => $_POST['ordered'],
            );
			if($_FILES['icon']['name']!='')
			{
				$this->deleteImage($this->id, "icon", SITE_DIR.$this->img_path_short);
				$FileName = rand().'_'.$_FILES['icon']['name'];
				parent::crop_image($_FILES['icon']['tmp_name'],$FileName, $this->img_path,"","",false);
				$aData['icon'] = $FileName;
			}
			if( !$_POST['id'] ){
				
				 $id = $this -> aDb -> insert($aData);
				 $msg = "Item has been inserted at ". date('Y-m-d h:i:s');
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id, $aData);
				$msg = "Item has been updated at ". date('Y-m-d h:i:s');
			}
			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}
		$form->display();
	}
	
	function changeStatus( $itemId , $status ){
		$aData = array( 'Status' => $status );
		$this -> aDb -> updateWithPk( $itemId, $aData );
		return true;
	}
	
	function listItem( $sMsg= '' )
	{
		
		$root_path = "System config > Manage Module > List Module";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => 'Name',
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			),
		); 
		
		$arr_cols= array(
			
			array(
				"field" => "id",
				"primary_key" =>true,
				"display" => "Id",
				"align" => "center",
				"sortable" => true
			),
			array(
				"field" => "name",
				"display" => "Name",
				"align"	=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
            array(
                "field" => "task",
                "display" => "Task",
                "align"    => 'left',                
                "datatype" => "text",
                "sortable" => true
            ),
			array(
				"field" 		=> "icon",
				"display" 		=> 'Icon',
				"datatype" 		=> "img",
				"img_path"		=> SITE_URL.$this->img_path_short,
                "width"			=> "70",
			),
			array(
				"field" => "ordered",
				"display" => "Order",
				"datatype" 		=> "order",
				"sortable" => true,
				"order_default" => "asc"
			),
		);
		
		$arr_check = array(
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
		$this -> datagrid->display_datagrid($this->table, $arr_cols, $arr_filter, $submit_url, $arr_action ,120, $root_path, false ,$arr_check);

	}
}

?>