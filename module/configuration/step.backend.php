<?php
class stepBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "step";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'step';
		$this -> type = 'step';
		$this -> img_path = "/upload/step/";
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
		$this -> updateById();
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
				"status" => $_POST['status'],
				"ordering" => ($_POST['ordering'])?$_POST['ordering']:"1"
			);

			if( !$_POST['id'] ){
				$id = $this -> aDb -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> updateById($aData);
			}
			$this -> step2attribute($id, $_POST['attribute_id']);
			$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
		}

		if($data['id'] != '') {
			$sql = "SELECT a.id, a.name  FROM step2attribute sa, attribute a WHERE sa.step_id = {$data[id]} AND  a.id = sa.attribute_id order by sa.id";
			$list_attr_of_step = $this -> db -> getAll($sql);
			$this -> assign('list_attr_of_step', $list_attr_of_step);
		}

		$this -> assign('detail', $data);
		$this -> display('step.backend.tpl');
	}


		//function add tags
	function step2attribute($step_id, $arr_attribute_id){
		if(count($arr_attribute_id) > 0){
			$this -> delete("step_id = {$step_id}", 'step2attribute');
			foreach($arr_attribute_id as $attribute_id)
			{
				$data = array(
					'step_id' => $step_id,
					'attribute_id' => $attribute_id
				);
				$this -> insert($data, 'step2attribute');
			}
		}
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
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('step')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table} WHERE lang_id = {$this -> lang_id} AND status != 9) a";
		
		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => $this -> getConfigVars('step'),
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
				"display" => $this -> getConfigVars('step'),
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "status",
				"display" => $this -> getConfigVars('status'),
				"datatype" => "publish"
			)
		);

		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false);
	}
}

?>