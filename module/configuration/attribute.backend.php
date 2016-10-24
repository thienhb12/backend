<?php
class attributeBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "attribute";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'attribute';
		$this -> img_path = "/upload/attribute/";
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

	function pager() {
		$limit = 10;
		$page = isset($_GET['page'])?$_GET['page']:1;
		$where = "status = 1 AND lang_id = {$this -> lang_id}";
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
	
	function buildForm( $data=array() ,$msg = '')
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$aData  = array(
				"lang_id"=> $this -> lang_id,
				"site_id"=> $this -> site_id,
				"name" => $_POST['name'],
				"label" => $_POST['label'],
				"unit" => $_POST['unit'],
				"description" => $_POST['description'],
				"source" => $_POST['source'],
				"status" => $_POST['status'],
				"required" => ($_POST['required']== 'on')?1:0,
				"type" => ($_POST['type'])?$_POST['type']:"text"
			);

			$aData['source'] = str_replace("\n", '@@@@' , $_POST['source']);

			if( !$_POST['id'] ){
				$id = $this -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> updateById($aData);
			}
			$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
		}

		if(strpos($data['source'], '@@@@') > 0){
			$data['source'] = str_replace( "@@@@", '' , $data['source']);
		}

		$this -> assign('detail', $data);
		$this -> display('attribute.backend.tpl');
	}
	
	function deleteImage($id,$field,$path){
		if($id == '')
			return;
		$imgpath = $path.$this -> db->getOne("SELECT $field FROM ".$this -> table." WHERE id = $id");
		if(is_file($imgpath))
			@unlink($imgpath);
	}

	function listItem( $sMsg= '' )
	{
		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('fieldInfo')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table} WHERE site_id = {$this -> site_id}) a";
		
		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => $this -> getConfigVars('fieldInfo'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);

		if(MULTI_LANGUAGE)
			$arr_filter[] = array(
				'field' => 'lang_id',
				'display' => $this -> getConfigVars('language'),
				'name' => 'filter_cat',
				'selected' => $_REQUEST['filter_cat'],
				'options' => parent::getAssocLang(),
				'filterable' => true
			);

		$arr_filter[] = array(
				'field' => 'status',
				'display' => $this -> getConfigVars('status'),
				'name' => 'filter_show',
				'selected' => $_REQUEST['filter_show'],
				'options' => array($this -> getConfigVars('unpublic_all'),$this -> getConfigVars('public_all')),
				'filterable' => true
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
				"display" => $this -> getConfigVars('fieldInfo'),
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "type",
				"display" => "Kiểu dữ liệu",
				"datatype" => "text",
				"sortable" => false
			),
			array(
				"field" => "status",
				"display" => $this -> getConfigVars('status'),
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
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}

?>