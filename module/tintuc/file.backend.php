<?php
class fileBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "file";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		parent::getRootPath();
		$this -> mod = 'file';
		$this -> type = 'file';
		$this -> img_path = "/upload/file/";
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
			case 'getlist':
				$this -> getListFile();
				break;
			case 'manage':
				$this -> manageImage();
				break;
			case 'upload':
				$this -> upload($_GET['id']);
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
		$result["delete"] = 1; 
		echo json_encode($result);
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

	function upload() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if($_FILES['Filedata']['name']!='')
			{
				$file_name = basename($_FILES["Filedata"]["name"]);
				$extension = array('doc', 'docx', 'pdf', 'zip', 'rar');
				$uploadOk = 1;

				if(!$this -> checkExtensionFileUpload($extension, $file_name)) {
					//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 202;
				}

				if($_FILES["Filedata"]["size"] > 10240000) {
					//echo "Sorry, your file is too large.";
					$uploadOk = 179;
				}

				if($uploadOk == 1) {
					$file_name = $this -> renameFile($file_name);
					$aData['description'] = $_POST['description'];
					$_POST['url'] = $this -> img_path.$file_name;
					move_uploaded_file($_FILES["Filedata"]["tmp_name"], SITE_DIR.$_POST['url']);
					$aData['url'] = $_POST['url'];
					$this -> aDb -> insert($aData);
				}

				echo '<script type="text/javascript">window.parent.OnUploadCompleted('.$uploadOk.',"' .$aData['url'].'","'.$aData['description'].'");</script>';
			}
		}
	}

	function getListFile()
	{

		if(isset($_GET['type']) && $_GET['type'] != '')
			$where .= " AND type = ". $_GET['type'];

		$limit = 12;

		$page = isset($_GET['page'])?$_GET['page']:1;

		if($_GET['filter']) {
			$where .= " AND description like '%".$_GET['filter']."%'";
		}
		//echo $where;
		$url = $_SERVER['REQUEST_URI'];
		if ($_GET['page'])
			$url = str_replace("&page=".$_GET['page'],"",$url);

		$url .= "&page=i++";
		
		$items = parent::Paging($limit, $field = '*',$where, $url, ' ORDER BY ID DESC');
		$result['next'] = "";
		$result['items'] = $items;
		$result['page'] = $page;
		//pre($result);
		if(count($items) == $limit) {
			$result['next'] = "1";
		}
		
		echo json_encode($result);
	}

	function buildForm( $data=array() ,$msg = '')
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$aData  = array(
				"name" => $_POST['name'],
				"status" => $_POST['status']
			);

			$aData['createdate'] = date("Y-m-d");
			$file_folder = $this -> img_path.date("Ymd").'/';

			if(!is_dir(SITE_DIR.$file_folder)) {
				 mkdir(SITE_DIR.$file_folder , 0777);
			}

			if($_FILES['avatar']['name']!='')
			{
				if(isset($_GET['id'])) {
					$this -> deleteFile($_GET['id'], 'avatar');
				}

				$avatar = basename($_FILES["avatar"]["name"]);
				$extension = array('jpg', 'png', 'gif');
				$uploadOk = 1;
				if(!$this -> checkExtensionFileUpload($extension, $avatar)) {
					//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 202;
				}

				if($_FILES["avatar"]["size"] > 1024000000) {
					//echo "Sorry, your file is too large.";
					$uploadOk = 179;
				}
				$avatar = $this -> renameFile($avatar);
				$_POST['avatar'] = $file_folder.$avatar;
				move_uploaded_file($_FILES["avatar"]["tmp_name"], SITE_DIR.$file_folder.$avatar);
			}


			if($_FILES['file_file']['name']!='')
			{
				if(isset($_GET['id'])) {
					$this -> deleteFile($_GET['id'], 'url');
				}
				$file_file = basename($_FILES["file_file"]["name"]);
				$extension = array('mp4', 'mp3', 'flv');
				$uploadOk = 1;
				if(!$this -> checkExtensionFileUpload($extension, $file_file)) {
					echo "Sorry, only mp4, mp3, flv files are allowed.";
					$uploadOk = 202;
				}

				if($_FILES["file_file"]["size"] > 1024000000) {
					echo "Sorry, your file is too large.";
					$uploadOk = 179;
				}

				$file_file = $this -> renameFile($file_file);

				$_POST['file_file'] = $file_folder.$file_file;

				move_uploaded_file($_FILES["file_file"]["tmp_name"], SITE_DIR.$file_folder.$file_file);
			}

			$aData['avatar'] = $_POST['avatar'];
			$aData['url'] = $_POST['file_file'];

			if( !$_POST['id'] ){
				$id = $this -> aDb -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id,$aData);
			}

			//$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
		}

		$this -> assign('detail', $data);
		$this -> display('creater_file_admin.tpl');
	}


	function deleteFile($id,$field){
		if($id == '')
			return;
		/*$imgpath = $this -> db-> getOne("SELECT $field FROM ".$this -> table." WHERE id = $id");
		if(file_exists(SITE_DIR.$imgpath)) {
			@unlink(SITE_DIR.$imgpath);
		}*/
	}
	function listItem( $sMsg= '' )
	{
		echo ($this -> renameFile('can .png tho.png'));
		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('images')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table}) a";
		
		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => $this -> getConfigVars('images'),
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
				"align" => "center",
				"sortable" => true,
				"order_default" => "DESC"
			),
			array(
				"field" => "name",
				"display" => $this -> getConfigVars('images'),
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			)
		);

		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );

		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}

?>