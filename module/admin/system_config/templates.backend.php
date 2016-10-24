<?php
class templatesBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "templates";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'templates';
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
		$this -> buildForm();
	}
	
	function editItem(){
		$row = $this -> getById();
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{
		$id = $_GET["id"];
		$this -> aDb -> deleteWithPk( $id );
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
				"title" => $_POST['title'],
				"content" => $_POST['content'],
				"comment" => $_POST['comment'],
				"use_cate" => $_POST['use_cate'],
				"site_id" => 1,
				"status" => $_POST['status']
			);

			if( !$_POST['id'] ){
				$id = $this -> aDb -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id,$aData);
			}

			$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']));
		}

		$this -> assign('detail', $data);
		$this -> display('templates.backend.tpl');
	}

	function changestatus( $itemId ,$status ){
		$aData = array( 'status' => $status );
		$this -> aDb -> updateWithPk( $itemId,$aData );
		return true;
	}

	function deleteImage($id,$field){
		echo "SELECT $field FROM ".$this -> table." WHERE id = $id";
		if($id == '')
			return;
		$imgpath = SITE_DIR.$this -> db->getOne("SELECT $field FROM ".$this -> table." WHERE id = $id");
		

		if(is_file($imgpath)) {
			@unlink($imgpath);
			@unlink(str_replace('/templates/', '/templates/thumb', $imgpath));
		}
	}

	function listItem( $sMsg= '' )
	{

		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('templates')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table}) a";
		$arr_filter= array(
			array(
				'field' => 'title',
				'display' => $this -> getConfigVars('templates'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);

		$arr_cols= array(
			array(
				"field" => "title",
				"display" => $this -> getConfigVars('templates'),
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