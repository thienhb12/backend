<?php
class footer extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "footer";
		parent::__construct($smarty, $db, $datagird, $table);
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
		$this -> getPath("Thêm mới footer ");
		$this -> buildForm();
	}
	
	function editItem()
	{
		$id = $_REQUEST['id'];
		$this -> getPath("Chỉnh sửa footer");
		$row = $this -> aDb -> getRow( $id );
	    $this -> buildForm( $row );
	}

	function deleteItem()
	{

		$id = $_GET["id"];
		$this -> aDb -> deleteWithPk( $id );
		$msg = "Item has been deleted at ". date('Y-m-d h:i:s');
		$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");     
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
		$form -> addElement('text', 'Name', 'Name', array('size' => 50, 'maxlength' => 255));
		$form->addElement('textarea','Content','Nội dung',array('style'=>'width:600px;height:100px;'));
		$date_time = parent::createCalendar('CreateDate',($data['CreateDate']) ? ($data['CreateDate']) : date("Y-m-d h:i"));
		$form -> addElement('static',NULL,'Ngày tạo',$date_time);
		$form -> addElement('checkbox','Status','Kích hoạt');
		$btn_group[] = $form -> createElement('submit',null,'Save',array("style"=> "border:1px solid gray; padding:0 5px 0 5px;"));
        $btn_group[] = $form -> createElement('button',null,'Go Back',array('onclick'=>'window.location.href = \''.$_COOKIE['re_dir'].'\'', "style"=> "border:1px solid gray;"));      
        $form -> addGroup($btn_group);
      
		$form->addElement('hidden', 'id', $data['id']);
		if( $form -> validate())
		{
            $aData  = array(
                "Name" => $_POST['Name'],
                "Content" => $_POST['Content'],
                "CreateDate" => $_POST['CreateDate'],
                "Status"     => $_POST['Status'],
            );
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
				"field" => "name",
				"display" => "Name",
				"align"	=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "status",
				"display" => "Trạng thái",
				"datatype" => "publish",
				"sortable" => true
			)
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