<?php
class siteBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "anpro_site";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'site';
		$this -> type = 'site';
		$this -> img_path = "/upload/site/";
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
		$this -> buildForm($this -> getById());
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
		$arr = array('png', 'gif', 'jpg', 'jpeg');
		if($_FILES['avatar']['name']!='' && in_array($this -> getExtensionFile($_FILES['avatar']['name']), $arr))
		{
			$this -> deleteImage($this -> id,"avatar",$this -> img_path);
			$Filename = time().'_'.$_FILES['avatar']['name'];
			parent::crop_image($_FILES['avatar']['tmp_name'],$Filename,SITE_DIR.$this -> img_path,300, 400, false);
			parent::crop_image($_FILES['avatar']['tmp_name'],$Filename,SITE_DIR.$this -> img_path_thumb,150, 200, false);
			$_POST['avatar'] = $this -> img_path.$Filename;
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$aData  = array(
				"title" => $_POST['title'],
				"domain" => $_POST['domain'],
				"avatar" => $_POST['avatar'],
				"status" => $_POST['status']
			);

			if( !$_POST['id'] ){
				$id = $this -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> updateById($aData);
			}

			$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']));
		}

		$this -> assign('detail', $data);
		$this -> display($_GET['atask'].".backend.tpl");
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
			@unlink(str_replace('/site/', '/site/thumb', $imgpath));
		}
	}

	function listItem( $sMsg= '' )
	{

		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('site')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table}) a";
		$arr_filter= array(
			array(
				'field' => 'title',
				'display' => $this -> getConfigVars('site'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);

		$arr_cols= array(
			array(
				"field" => "title",
				"display" => $this -> getConfigVars('site'),
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