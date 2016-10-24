<?php
class townBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "ward";
		parent::__construct($smarty,$db,$datagird,$table);
		parent::getRootPath();
		$this -> mod = 'town';
		$this -> type = 'town';
		$this -> img_path = "/upload/town/";
		$this -> img_path_thumb = "/upload/town/thumb/";
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
			case 'district':
				$this -> getListTownByDistrictId($_GET['district_id']);
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
		$id = $_GET['id'];
		$row = $this -> aDb -> getRow( $id );
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{
		$id = $_GET["id"];
		$this -> aDb -> deleteWithPk( $id );
		$this -> listItem();
	}
	
	function deleteItems()
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			for($i = 0 ; $i < count($aItems); $i++) {
				$this -> aDb -> deleteWithPk( $aItems[$i] );
			}
		}
		$this -> listItem();
	}
	
	function changestatusMultiple( $status = 0 )
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',',$aItems );
			$this -> aDb -> updateWithPk( $sItems,array("status" => $status) );
		}
		$this -> listItem();
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
        $this -> listItem();
    }
	
	function buildForm( $data=array() ,$msg = '')
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$aData  = array(
				"name" => $_POST['name'],
				"district_id" => $_POST['district_id'],
				"type" => $_POST['type']
			);

			if( !$_POST['id'] ){
				$id = $this -> aDb -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id,$aData);
			}

			$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
		}
		$sql = "SELECT id, name from district";
		$district = $this -> db -> getAssoc($sql);
		$this -> assign('district', $district);
		$this -> assign('detail', $data);
		$this -> clearCache('creater_town_admin.tpl');
		$this -> display('creater_town_admin.tpl');
	}

	function changestatus( $itemId ,$status ){
		$aData = array( 'status' => $status );
		$this -> aDb -> updateWithPk( $itemId,$aData );
		return true;
	}

	function getListTownByDistrictId($district_id) {
		$town = $this -> db -> getAll("SELECT id, name FROM {$this -> table} where district_id = " . $district_id );
		echo json_encode($town);
	}
	
	function listItem( $sMsg= '' )
	{

		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('town')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table} WHERE district_id in (SELECT id FROM district WHERE province_id in ({$this -> provincePermission()}) )) a";
		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => $this -> getConfigVars('town'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);

		$arr_cols= array(
			array(
				"field" => "name",
				"display" => $this -> getConfigVars('town'),
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			)
		);

		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false);
	}
}

?>