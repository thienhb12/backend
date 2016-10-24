<?php
class provinceBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "province";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'province';
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
			case 'pager':
				$this -> pager();
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
		$this -> deleteById();
		$this -> listItem();
	}
	
	function buildForm( $data=array() ,$msg = '')
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$aData  = array(
				"name" => $_POST['name'],
				"type" => $_POST['type']
			);

			if( !$_POST['id'] ){
				$id = $this -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> updateById($aData);
			}

			$this -> backPageList();
		}

		$this -> assign('detail', $data);
		$this -> display('province.backend.tpl');
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
	
	function pager() {
		$limit = 10;
		$page = isset($_GET['page'])?$_GET['page']:1;
		$where = " 1 ";
		if($_GET['filter']) {
			$where .= " AND name like '%".$_GET['filter']."%'";
		}

		$num_rows = $this -> db -> getOne("SELECT count(id) from ".$this->table." WHERE {$where}");
		$eu = $limit*($page-1);

		$sql = "SELECT * FROM ".$this -> table;
		$sql .= " WHERE {$where} LIMIT $eu, $limit";
		//echo $sql;
		$items = $this -> db -> getAll($sql);
		$result['total_pages'] = ceil($num_rows/ $limit);
		$result['items'] = $items;
		echo json_encode($result);
	}

	function listItem( $sMsg= '' )
	{
		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('province')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table} order by type DESC, name DESC) a";

		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => $this -> getConfigVars('province'),
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
				"align" => "center"
			),
			array(
				"field" => "name",
				"display" => $this -> getConfigVars('province'),
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "type",
				"display" => $this -> getConfigVars('type'),
				"datatype" => "text"
			)
		);

		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false);
	}
}

?>