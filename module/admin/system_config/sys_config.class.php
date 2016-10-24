<?php
class sys_config extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "tbl_sys_config";
		parent::__construct($smarty, $db, $datagird, $table);
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
	
	function buildForm( $data=array() ,$msg = ''){
	
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
                    $aData  = array(
                            "name" => $_POST['name'],
                            "value" => $_POST['value'],
                            "description" => $_POST['description'],
                            "site_id" => $this -> site_id
                    );

                    if( !$_POST['id'] ){
                            $id = $this -> insert($aData);
                    }else {
                            $id = $_POST['id'];
                            $this -> updateById($aData,$id);
                    }
                    global $cache;
                    $cache->delete('site_config', '/config/');

                    $this -> redirect($_COOKIE['re_dir']);
		}
                
		$this -> assign('detail', $data);
		$this -> clearCache('create_site_config_admin.tpl');
		$this -> display('create_site_config_admin.tpl');
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
		$root_path = "<li>{$this -> getConfigVars('sys_config')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		$where = " 1 ";
		if(!$this -> isOOPS()) {
			$where .= "AND site_id = " . $this -> site_id;
		}
                $table = "(SELECT * FROM {$this -> table} WHERE {$where}) a";

		$arr_cols= array(
			array(
				"field" => "id",
				"datatype" => "text",
				"primary_key" => true,
				"visible" => "hidden",
				"sortable" => true,
				"searchable" => true
			),		
			array(
				"field" => "name",
				"display" => "Name",
				"datatype" => "text",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "description",
				"display" => 'Description',
				"datatype" => "text",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "value",
				"display" => 'Value',
				"datatype" => "text",
				"sortable" => true,
				"searchable" => true
			)		
		);
		
		
		$arr_check = array(
			array(
			'task' => 'delete_all',
			'confirm'	=> 'Are you sure?',
			'display' => 'Xóa'
			)
		);
		
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, "?".$_SERVER['QUERY_STRING'], $arr_action, null, $root_path, false,$arr_check);

	}
}
?>
