<?php
class quangcaoBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "ad_layout";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		parent::getRootPath();
		$this -> mod = 'quangcao';
		$this -> type = 'quangcao';
		$this -> img_path = "/upload/quangcao/";
		$this -> img_path_thumb = "/upload/quangcao/thumb/";
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
			default:
				$this -> listItem( $_GET['msg'] );
				break;
		}
	}


	function addItem(){
		$this -> getPath("Quản lý layout > Thêm mới bài layout");
		$this -> buildForm();
	}
	
	function editItem(){
		$id = $_GET['id'];
		$this -> getPath("Quản lý layout > Chỉnh sửa bài layout");
		$row = $this -> aDb -> getRow( $id );
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{

		$id = $_GET["id"];
		$this -> aDb -> deleteWithPk( $id );
		$msg = "Xóa bài layout thành công!";
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
		$msg = "Xóa (các) bài layout thành công!";
		$this -> listItem( $msg );
	}
	
	function changestatusMultiple( $status = 0 )
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',',$aItems );
			$this -> aDb -> updateWithPk( $sItems,array("status" => $status) );
		}
		$msg = "Sửa trạng thái bài layout thành công!";
		$this -> listItem( $msg );
	}

	function buildForm( $data=array() ,$msg = ''){
		
		$form = new HTML_QuickForm('frmAdmin','post',$_COOKIE['re_dir']."&task=".$_GET['task'],'',"style='padding:10px 15px 0 20px;'");
		
		$form -> setDefaults($data);

		$form -> addElement('text','title','Tiêu đề',array('size' => 50,'maxlength' => 255));
		$form -> addElement('button','button','Thêm zone',array('id' => 'link','size' => 50,'maxlength' => 255, 'onclick'=>"selectRelated(this)"));

		if($data['content']) {
			$rl_items = explode( '@@', $data['content']);
			$related_links = '<ul id="related_link">';
			for($i = 0; $i < count($rl_items) ; $i++) {
				$sql = "SELECT title FROM ad_zone WHERE id = {$rl_items[$i]}";
				$zone_title = $this -> db -> getAll($sql);
				$related_links .= '<li id="link_' . $rl_items[$i] . '"><span class="delete" onclick="deleteRelatedLink(this)">Xóa</span>' . $zone_title . '<input type="hidden" name="club_id[]" class="clear"  value="' . $rl_items[$i] . '" /></li>';
			}

			$related_links .= '</ul>';
		}
	
		$form->addElement('static',null,'', $related_links);
		$form -> addElement('checkbox','status','Kích hoạt');
		$btn_group[] = $form -> createElement('submit',null,'Hoàn tất',array("style"=> "border:1px solid gray;padding:0 5px 0 5px;"));
		$btn_group[] = $form -> createElement('button',null,'Quay lại',array('onclick'=>'window.location.href = \''.$_COOKIE['re_dir'].'\'',"style"=> "border:1px solid gray;"));
		$form -> addGroup($btn_group);
	  
		$form->addElement('hidden','id',$data['id']);
		
		$form -> addRule('name','Tiêu đề không được để trống','required',null,'client');
		
		if( $form -> validate())
		{
			$aData  = array(
				"title" => $_POST['title'],
				"status" => $_POST['status']
			);

			if( !$_POST['id'] ){
				$id = $this -> aDb -> insert($aData);
				$msg = "Thêm bài layout thành công! ";
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id,$aData);
				$msg = "Chỉnh sửa bài layout thành công ";
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

		$root_path = "Quản lý layout > Danh sách layout";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		$arr_filter= array(
			array(
				'field' => 'title',
				'display' => 'Tiêu đề',
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
				"sortable" => true,
				"order_default" => "DESC"
			),
			array(
				"field" => "title",
				"display" => "Tiêu đề",
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "status",
				"display" => "Trạng thái",
				"datatype" => "publish"
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
		$this -> datagrid -> display_datagrid($this -> table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}

?>