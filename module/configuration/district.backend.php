<?php
class districtBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "district";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'district';
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
			case 'province':
				$this -> getListDistrictByProvinceId($_GET['province_id']);
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
				"province_id" => $_POST['province_id'],
				"type" => $_POST['type']
			);

			if( !$_POST['id'] ){
				$id = $this -> insert($aData);
			}else {
				$this -> updateById($aData);
			}
			$this -> backPageList();
		}
		$sql = "SELECT id, name from province";
		$province = $this -> db -> getAssoc($sql);
		$this -> assign('province', $province);
		$this -> assign('detail', $data);
		$this -> assign('list_attr_of_step', $list_attr_of_step);
		$this -> display('creater_district_admin.tpl');
	}

	function getListDistrictByProvinceId($provice_id) {
		$district = $this -> db -> getAll("SELECT id, name FROM {$this -> table} where province_id = " . $provice_id );

		echo json_encode($district);
	}
	
	function listItem( $sMsg= '' )
	{
		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('district')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table}) a";
		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => $this -> getConfigVars('district'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);

		$arr_cols= array(
			array(
				"field" => "name",
				"display" => $this -> getConfigVars('district'),
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