<?php
class contactBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "contact";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		parent::getRootPath();
		$this -> mod = 'contact';
		$this -> img_path = "/upload/contact/";
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
		$this -> getPath("Quản lý tin bài > Thêm mới bài tin bài");
		$this -> buildForm();
	}
	
	function editItem(){
		$id = $_GET['id'];
		$this -> getPath("Quản lý tin bài > Chỉnh sửa bài tin bài");
		$row = $this -> aDb -> getRow( $id );
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{
		$id = $_GET["id"];
		$this -> aDb -> deleteWithPk( $id );
		$msg = "Xóa bài tin bài thành công!";
		$this -> listItem( $msg );
	}
	
	function deleteItems()
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			for($i = 0 ; $i < count($aItems); $i++) {
				$this -> aDb -> deleteWithPk( $aItems[$i] );
			}
		}
		$msg = "Xóa (các) bài tin bài thành công!";
		$this -> listItem( $msg );
	}
	
	function changestatusMultiple( $status = 0 )
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',',$aItems );
			$this -> aDb -> updateWithPk( $sItems,array("status" => $status) );
		}
		$msg = "Sửa trạng thái bài tin bài thành công!";
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
		
		$form = new HTML_QuickForm('frmAdmin','post',$_COOKIE['re_dir']."&task=".$_GET['task'],'',"style='padding:10px 15px 0 20px;'");
		
		$form -> setDefaults($data);
		$form -> addElement('text','subject','Tiêu đề',array('size' => 50,'maxlength' => 255));
		$form -> addElement('text','fullname','Họ tên',array('size' => 50,'maxlength' => 255));
		$form -> addElement('text','address','Địa chỉ',array('size' => 50,'maxlength' => 255));
		$form -> addElement('text','phone','Sỗ điện thoại',array('size' => 50,'maxlength' => 255));
		$form -> addElement('text','email','Email',array('size' => 50,'maxlength' => 255));
		$form->addElement('textarea','content','Nội dung',array('style'=>'width:600px;height:100px;'));
		$date_time = parent::createCalendar('create_date',($data['create_date']) ? ($data['create_date']) : date("Y-m-d h:i"));
		$form -> addElement('static',NULL,'Ngày tạo',$date_time);
		$form -> addElement('checkbox','status','Kích hoạt');

		$btn_group[] = $form -> createElement('submit',null,'Hoàn tất',array("style"=> "border:1px solid gray;padding:0 5px 0 5px;"));
		$btn_group[] = $form -> createElement('button',null,'Quay lại',array('onclick'=>'window.location.href = \''.$_COOKIE['re_dir'].'\'',"style"=> "border:1px solid gray;"));
		$form -> addGroup($btn_group);
		$form -> addGroup($img_group);
	  
		$form->addElement('hidden','id',$data['id']);
		
		$form -> addRule('name','Tiêu đề không được để trống','required',null,'client');
		
		if( $form -> validate())
		{
			$aData  = array(
				"lang_id"=> ($_POST['lang_id'])?$_POST['lang_id']:"1",
				"subject" => $_POST['subject'],
				"address" => $_POST['address'],
				"fullname" => $_POST['fullname'],
				"email" => $_POST['email'],
				"phone" => $_POST['phone'],
				"content" => $_POST['content'],
				"create_date" => $_POST['create_date'],
				"type" => $this -> mod,
				"status" => $_POST['status'],
			);

			if( !$_POST['id'] ){
				
				 $id = $this -> aDb -> insert($aData);
				 $msg = "Thêm bài tin bài thành công! ";
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id,$aData);
				$msg = "Chỉnh sửa bài tin bài thành công ";
			}
			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}
		
		$form->display();
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

		$root_path = "Quản lý tin bài > Danh sách tin bài";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table} WHERE type = '{$this -> mod}') a";
		
		$arr_filter= array(
			array(
				'field' => 'subject',
				'display' => 'Tiêu đề',
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
        );
        if(MULTI_LANGUAGE)
            $arr_filter[] = array(
                    'field' => 'lang_id',
                    'display' => 'Ngôn ngữ',
                    'name' => 'filter_cat',
                    'selected' => $_REQUEST['filter_cat'],
                    'options' => parent::loadLang(),
                    'filterable' => true
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
				"field" => "id",
				"primary_key" =>true,
				"display" => "Id",
				"align" => "center",
				"sortable" => true
			),
			array(
				"field" => "subject",
				"display" => "Tiêu đề",
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
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

		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}

?>