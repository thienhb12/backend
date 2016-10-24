<?php
class welcome extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "tbl_sys_menu_home";
		parent::__construct($smarty, $db, $datagird, $table);
		$this -> img_path = "/upload/menu_welcome/";
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
			case 'change_status':
				$this -> changestatus($_GET['id'],$_GET['status']);
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
		$this -> getActionForm();
		$this -> buildForm();
	}
	
	function editItem(){
		$id = $_GET['id'];
		$row = $this -> getById( );
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{
		$id = $_GET["id"];
		$this -> deleteImage($id,"avatar",$this -> img_path);
		$this -> deleteById( $id );
		$this -> listItem();
	}

    function saveOrder(){
        $aItem = $_GET['ordering'];
        if(is_array($aItem) && count( $aItem ) > 0){
            // save order for item.
            foreach( $aItem as $key => $value){
                if( !is_numeric($value)) $value = 0;
                $this -> updateById( $key,array('ordering' => $value ));
            }
        }
        $this -> listItem();
    }

	function indexOf($str, $seach ) {
		$pos = strrpos($str, $seach);
		if($pos===false) {
			return false;
		} else {
			return substr($str, $pos+1);
		}
	}
	
	function buildForm( $data=array() ,$msg = ''){
	
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$arr = array('png', 'gif', 'jpg', 'jpeg');
			if($_FILES['avatar']['name']!='' && in_array($this -> getFileExtension($_FILES['avatar']['name']), $arr))
			{
				$this -> deleteImage($this -> id,"avatar",$this -> img_path);
				$Filename = rand().'_'.$_FILES['avatar']['name'];
				parent::crop_image($_FILES['avatar']['tmp_name'],$Filename,SITE_DIR.$this -> img_path,300, 400, false);
				$_POST['avatar'] = $this -> img_path.$Filename;
			}

			$aData  = array(
				"title" => $_POST['title'],
				"title_en" => $_POST['title_en'],
				"link" => $_POST['link'],
				"status" => $_POST['status'],
				"ordering" => ($_POST['ordering'])?$_POST['ordering']:"1"
			);

			if ($_POST['avatar']!='')
				$aData['avatar'] = $_POST['avatar'];
				
			if( !$_POST['id'] ){
				$id = $this -> db -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> updateById($aData,$id);
			}

			parent::tagsCloud($_POST['tagcloud']);
			$this -> updateById($aData, $id);
			$this -> redirect($_COOKIE['re_dir']);
		}
		$this -> assign('detail', $data);
		
		$this -> clearCache('creater_menu_welcome_admin.tpl');
		$this -> display('creater_menu_welcome_admin.tpl');
	}
	
	function deleteImage($id,$field){
		if($id == '')
			return;
		$imgpath = SITE_DIR.$this -> db->getOne("SELECT $field FROM ".$this -> table." WHERE id = $id");
		if(is_file($imgpath))
			@unlink($imgpath);
	}

	function changestatus( $itemId ,$status ){
		$aData = array( 'status' => $status );
		$this -> db -> updateWithPk( $itemId,$aData );
		return true;
	}
	
	function listItem( $sMsg= '' )
	{
		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('welcome')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table}) a";
		
		$arr_filter= array(
			array(
				'field' => 'title',
				'display' => $this->getConfigVars('title'),
				'type' => 'text',
				'title' => 'filter_title',
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
				"display" => $this->getConfigVars('status'),
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

		if( $sMsg ) {
			$this -> datagrid -> setMessage( $sMsg );
			$this -> setCache();
		}
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}
?>
