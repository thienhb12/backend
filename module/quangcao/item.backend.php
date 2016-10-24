<?php
/**
 * Sửa ngày 18/6/2013
 * Mô tả: module quản lý item quảng cáo
 * Chức năng:
 * - Thêm, sửa, xóa, tìm kiếm
 */
class itemBackEnd extends Anpro_Module_Base 
{
	private $type;
	public function __construct($smarty,$db,$datagird)
	{
		$table = "ad_item";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		parent::getRootPath();
		$this -> mod = 'item';
		$this -> img_path = "/upload/quangcao/";
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

	function addItem()
	{
		$this -> getPath("Quản lý sản phẩm > Quản lý danh mục > Thêm mới danh mục");
		$this -> buildForm();
	}
	
	function editItem()
	{
		$id = $_GET['id'];
		$this -> getPath("Quản lý sản phẩm > Quản lý danh mục > Sửa danh mục");
		$row = $this -> aDb -> getRow( $id );
		$this -> buildForm( $row );
	}

	function deleteItem()
	{
		$id = $_GET["id"];
		if (!$this->hasSubItem($id)) {
			$this -> aDb -> deleteWithPk( $id );
			$msg = "Xóa Quản lý danh mục thành công!";
		}
		else 	
			$msg = "Không thể xóa danh mục này vì còn chứa danh mục con hoặc sản phẩm";
		$this -> listItem( $msg );
	}
	
	function deleteItems()
	{
		$aItems = $_GET['arr_check'];
		$del = true;
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',',$aItems );
			if (!$this->hasSubItem($sItems)) {
				$this -> aDb -> deleteWithPk( $sItems );
			}
			else 
				$del = false;
		}
		if ($del)
			$msg = "Xóa (các) danh mục thành công!";
		else 
			$msg = "Không thể xóa toàn bộ danh mục vì còn chứa danh mục con hoặc sản phẩm";
		$this -> listItem( $msg );
	}
	
	function changestatusMultiple( $status = 0 )
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',',$aItems );
			$this -> aDb -> updateWithPk( $sItems,array("status" => $status) );
		}
		$msg = "Sửa trạng thái danh mục thành công!";
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
		$msg = "Lưu thứ tự danh mục thành công!";
		$this -> listItem( $msg );
	}
	
	function buildForm( $data=array() ,$msg = '')
	{
		$form = new HTML_QuickForm('frmAdmin','post',$_COOKIE['re_dir']."&task=".$_GET['task'],'',"style='padding:10px 15px 0 20px;'");
		$form -> setDefaults($data);
		$form -> addElement('text','name','Tên danh mục',array('size' => 50,'maxlength' => 255));
		$form -> addElement('select','type','Loại quảng cáo',array('Image'=>'Ảnh','Flash'=>'Flash', 'Script' => 'Script', 'Pre-roll' =>'Pre-roll', 'Video' => 'Video'), array('id' =>'type-advert'));
		if($data['type'] == 'Script') {
			$form->addElement('textarea','file', 'File',array('value' => $data['file'],'id' =>'file2', 'style'=>'width:600px;height:100px; display: block;'));
			$form->addElement('file','file1','File', array('id' =>'file1', 'style'=>'display: none'));
		}
		else {
			$form->addElement('textarea','file', 'File',array('id' =>'file2', 'style'=>'width:600px;height:100px; display: none;'));
			$form->addElement('file','file1', 'File', array('id' =>'file1'));
		}

		if($_GET['task']=='edit' && $data['type'] == 'Image'){
			$form->addElement('static',null,'',"<a href='".SITE_URL.$data['file']."' onclick='return hs.expand(this)' class='highslide'><img src='".SITE_URL.$data['file']."' width=100 hight=100 border=0></a>");

		}

		$start_date = parent::createCalendar('start_date',$data['start_date'] ? ($data['start_date']) : date("Y-m-d h:i"));
		$form -> addElement('static',NULL,'Giờ bắt đầu',$start_date);
		$end_date = parent::createCalendar('end_date',($data['end_date']) ? ($data['end_date']) : date("Y-m-d h:i"));
		$form -> addElement('static',NULL,'Giờ kết thúc',$end_date);
		$form -> addElement('checkbox','status','Kích hoạt');
		
		$btn_group[] = $form -> createElement('submit',null,'Hoàn tất',array("style"=> "border:1px solid gray;padding:0 5px 0 5px;"));
		$btn_group[] = $form -> createElement('button',null,'Quay lại',array('onclick'=>'window.location.href = \''.$_COOKIE['re_dir'].'\'',"style"=> "border:1px solid gray;"));
		$form -> addGroup($btn_group);
		$form->addElement('hidden','id',$data['id']);
		$form -> addRule('name','Tên Quản lý danh mục không được để trống','required',null,'client');

		if( $form -> validate())
		{
			if($_FILES['file1']['name']!='')
			{
				$name = rand().''.$_FILES['file1']['name'];
				$tmp_name = $_FILES["file1"]["tmp_name"];
				move_uploaded_file($tmp_name, SITE_DIR.$this -> img_path.$name);
				$_POST['file'] = $this -> img_path.$name;
			}

			if ($_POST['file2'] != '') {
				$_POST['file'] = $_POST['file2'];
			}

			$aData  = array(
				"name" => $_POST['name'],
				"type" => $_POST['type'],
				"start_date" => $_POST['start_date'],
				"end_date" => $_POST['end_date'],
				"status" => $_POST['status']
			);

			if ($_POST['file']!='')
				$aData['file'] = $_POST['file'];

			if( !$_POST['id'] ){
				 $id = $this -> aDb -> insert($aData);
				 $msg = "Thêm Item thành công! ";
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id,$aData);
				$msg = "Chỉnh sửa Item tin dành công ";
			}

			$this -> aDb -> updateWithPk($id,$aData);
			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}
		
		$form->display();
	}
	
	function deleteImage($id,$field,$path){
		if($id == '')
			return;
		$imgpath = $path.$this->db->getOne("SELECT $field FROM ".$this->table." WHERE id = $id");
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

		$root_path = "Quản lý sản phẩm > Quản lý danh mục";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table}) a";

		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => 'Tên quảng cáo',
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);

		$arr_filter[] = array(
				'field' => 'status',
				'display' => 'Trạng thái',
				'name' => 'filter_show',
				'selected' => $_REQUEST['filter_show'],
				'options' => array('Vô hiệu','Kích hoạt'),
				'filterable' => true
			);

		$arr_cols= array(
			array(
				"field" => "name",
				"display" => "Tên quảng cáo",
				"align"    => 'left',
				"datatype" => "text",
				"sortable" => true,
				"order_default" => "asc"
			),
			array(
				"field" => "type",
				"align" => 'left',
				"datatype" => "text",
				"sortable" => true,
				"order_default" => "asc"
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
				"task" => "delete_all",
				"confirm"	=> "Xác nhận xóa?",
				"display" => "Xóa"
			),
			array(
				"task" => "public_all",
				"confirm"	=> "Xác nhận thay đổi trạng thái?",
				"display" => "Kích hoạt"
			),
			array(
				"task" => "unpublic_all",
				"confirm"	=> "Xác nhận thay đổi trạng thái?",
				"display" => "Vô hiệu"
			)
		);
		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table,$arr_cols,$arr_filter,"?".$_SERVER['QUERY_STRING'],$arr_action,null,$root_path,false,$arr_check);
	}
}
 // end class
?>
