<?php
class layoutBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "layouts";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'layout';
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
			default:
				$this -> listItem( $_GET['msg'] );
				break;
		}
	}


	function addItem(){
		$this -> buildForm();
	}
	
	function editItem(){
		$row = $this -> getById();
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{
		$this -> deleteImage($this -> id,"avatar",$this -> img_path);
		$this -> deleteByid();
		$this -> backPageList();
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
			$aData  = array(
				"site_id" => $this -> site_id,
				"name" => $_POST['name'],
				"content" => $_POST['content'],
				"status" => $_POST['status']
			);

			if( !$_POST['id'] ){
				$this -> id = $this -> insert($aData);
			}else {
				$this -> updateById($aData);
			}

			$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
		}

		$data['create_date'] = ($data['create_date']) ? ($data['create_date']) : date("Y-m-d H:i");
		$this -> assign('detail', $data);
		$category = $this -> getCategory($this -> type);
		$this -> assign('category', $category);
		$this -> display('layout.backend.tpl');
	}

	function getCategory($lang_id = 0 )
	{
		$cond = "site_id = {$this -> site_id}";
		$result = parent::multiLevel( "categories","id","parent_id","*",$cond,"ordering ASC");
		
		$category = array();
		foreach ($result as $value => $key)
		{
			if( $key['level'] > 0){
				$name = $this -> getPrefix( $key['level']).$key['name'];
			}
			else 
				$name = $key['name'];
			$category[$key['id']] = $name;
		}
		
		return $category;
	}
	
	function deleteImage($id,$field,$path){
		if($id == '')
			return;
		$imgpath = $path.$this -> db-> getOne("SELECT $field FROM ".$this -> table." WHERE id = $id");
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
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('news')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		$where = " 1 ";

		if(!$this -> isOOPS()) {
			$where .= "AND site_id = " . $this -> site_id;
		}
		
		$table = "(SELECT * FROM {$this -> table} WHERE {$where}) a";

		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => $this->getConfigVars('name'),
				'type' => 'text',
				'name' => 'filter_name',
				'selected' => $_REQUEST['filter_name'],
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
				"field" => "name",
				"display" => $this->getConfigVars('name'),
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),

			array(
				"field" => "site_id",
				"display" => $this->getConfigVars('categories'),
				"sql"=> "SELECT name FROM site WHERE id = site_id",
				"align"=> 'left',
				"datatype" => "text",
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
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}

?>