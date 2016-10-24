<?php
class advertBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "advert";
		parent::__construct($smarty, $db, $datagird, $table);
		$this->aDb->setTable($table);
		parent::getRootPath();
		$this -> img_path = "/upload/advert/";
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
		$this -> getPath("Quản lý quảng cáo > Thêm mới quảng cáo");
		$this -> buildForm();
	}
	
	function editItem()
	{
		$id = $_GET['id'];
		$this -> getPath("Quản lý quảng cáo > Chỉnh sửa quảng cáo");
		$row = $this -> aDb -> getRow( $id );
		$this -> buildForm( $row );
	}

	function deleteItem()
	{
		$id = $_GET["id"];
		$this -> aDb -> deleteWithPk( $id );
		$msg = "Xóa quảng cáo thành công!";
		$this -> listItem( $msg );
	}
	
	function deleteItems()
	{
		$aItems	 = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',', $aItems );
			$this -> aDb -> deleteWithPk( $sItems );
		}
		$msg = "Xóa (các) quảng cáo thành công!";
		$this -> listItem( $msg );
	}
	
	function changestatusMultiple( $status = 0 )
	{
		$aItems	 = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',', $aItems );
			$this -> aDb -> updateWithPk( $sItems, array("status" => $status) );
		}
		$msg = "Sửa trạng thái quảng cáo thành công!";
		$this -> listItem( $msg );
	}
	function saveOrder(){    
        $aItem = $_GET['ordering'];
        if(is_array($aItem) && count( $aItem ) > 0){
            // save order for item.
            foreach( $aItem as $key => $value){
                if( !is_numeric($value)) $value = 0;                
                $this -> aDb -> updateWithPk( $key, array('ordering' => $value ));
            }
        }    
        $msg = "Lưu thứ tự bài viết thành công!";
        $this -> listItem( $msg );
    }
	function buildForm( $data=array() , $msg = ''){
		
		$form = new HTML_QuickForm('frmAdmin','post',$_COOKIE['re_dir']."&task=".$_GET['task'], '', "style='padding:10px 15px 0 20px;'");
		
		$form -> setDefaults($data);

		if (MULTI_LANGUAGE)
		{
			$lang = parent::loadLang();
			$form -> addElement('select', 'lang_id', 'Ngôn ngữ', $lang);
		}
		
		$form -> addElement('text', 'name', 'Tiêu đề', array('size' => 50, 'maxlength' => 255));
		
		$form -> addElement('select','type','Kiểu',array('image'=>'Ảnh','flash'=>'Flash'));
		$position = array(
			'ad_left_1' => 'ad_left_1',
			'ad_left_2' => 'ad_left_2',
			'ad_left_3' => 'ad_left_3',
			'ad_right_1'=>'ad_right_1',
			'ad_right_2'=>'ad_right_2'
		);
		$form -> addElement('select','position','Vị trí', $position);
		$form -> addElement('text','height','Chiều cao',array('size' => 50, 'maxlength' => 255));
		
		$form -> addElement('text', 'link', 'Đường dẫn', array('size' => 50, 'maxlength' => 255));
		
		$form->addElement('file', 'photo', 'File ảnh');
		
		if($_GET['task']=='edit')
			$form->addElement('static', null, '',"<a href='".SITE_URL.$data['photo']."' onclick='return hs.expand(this)' class='highslide'><img src='".SITE_URL.$data['photo']."' width=100 hight=100 border=0></a>");

		$form -> addElement('text', 'ordering', 'Thứ tự', array('size' => 10, 'maxlength' => 50));
		
		$form -> addElement('checkbox', 'status', 'Kích hoạt');
		
		$btn_group[] = $form -> createElement('submit',null,'Hoàn tất',array("style"=> "border:1px solid gray; padding:0 5px 0 5px;"));
        $btn_group[] = $form -> createElement('button',null,'Quay lại',array('onclick'=>'window.location.href = \''.$_COOKIE['re_dir'].'\'', "style"=> "border:1px solid gray;"));      
        $form -> addGroup($btn_group);
		$form->addElement('hidden', 'height', $data['height']);
		$form->addElement('hidden', 'id', $data['id']);
		
		if( $form -> validate())
		{
			if($_FILES['photo']['name']!='')
			{
				$this->deleteImage($this->id, "photo", $this -> img_path);
				$FileName = rand().'_'.$_FILES['photo']['name'];
				parent::crop_image($_FILES['photo']['tmp_name'],$FileName, SITE_DIR.$this -> img_path, 0, 0, false);
				$_POST['photo'] = $this -> img_path.$FileName;
			}
			
			$aData  = array(
				"name" 		=> $_POST['name'],
				"lang_id" 	=> $_POST['lang_id'],
				"type" 		=> $_POST['type'],
				"position" 	=> $_POST['position'],
				"height" 	=> $_POST['height'],
				"link" 		=> $_POST['link'],
				"ordering" 	=> $_POST['ordering'],
				"status" 	=> $_POST['status'],
			);
			if ($_POST['photo']!='')
				$aData['photo'] = $_POST['photo'];
				
			if( !$_POST['id'] ){
				
				 $id = $this -> aDb -> insert($aData); 
				 $msg = "Thêm quảng cáo thành công! ";
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id, $aData);
				$msg = "Chỉnh sửa quảng cáo thành công ";
			}
			
			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}
		
		$form->display();
	}
	
	function deleteImage($id, $field, $path){
		if($id == '')
			return;
		$imgpath = $path.$this->db->getOne("SELECT $field FROM ".$this->table." WHERE id = $id");
		if(is_file($imgpath))
			@unlink($imgpath);
	}
	
	function changeStatus( $itemId , $status ){
		$aData = array( 'status' => $status );
		$this -> aDb -> updateWithPk( $itemId, $aData );
		return true;
	}
	
	function listItem( $sMsg= '' )
	{
		$root_path = "Quản lý quảng cáo > Danh sách quảng cáo";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];

		$table = $this -> table;

		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => 'Tiêu đề',
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			),
			array(
				'field' => 'status',
				'display' => 'Trạng thái',
				'name' => 'filter_show',
				'selected' => $_REQUEST['filter_show'],
				'options' => array('Vô hiệu','Kích hoạt'),
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
				"display" => "Tiêu đề",
				"align"	=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "type",
				"display" => "Kiểu",
				"align"	=> 'left',
				"datatype" => "value_set",
				"case"	=> array('image'=>'Ảnh','flash'=>'Flash'),
				"sortable" => true
			),
			array(
				"field" => "position",
				"display" => "Vị trí",
				"align"	=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "photo",
				"display" => "Ảnh",
				"datatype" 		=> "img",
				"img_path"		=> SITE_URL,
				"sortable" => true,
				"order_default" => "asc"
			),
			array(
				"field" => "ordering",
				"display" => "Thứ tự",
				"datatype" 		=> "order",
				"sortable" => true,
				"order_default" => "asc"
			),
			array(
				"field" => "status",
				"display" => "Trạng thái",
				"datatype" => "publish",
				"sortable" => true
			),
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
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
	
}

?>