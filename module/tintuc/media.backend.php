<?php

class mediaBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "media";
		parent::__construct($smarty,$db,$datagird,$table);
		parent::getRootPath();
		$this -> mod = 'media';
		$this -> type = 'media';
		$this -> img_path = "/upload/media/";
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
				$this -> getListImage();
				break;
			case 'manage':
				$this -> manageImage();
				break;
			case 'upload':
				$this -> buildForm($_GET['id'],$_GET['type']);
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
		$this -> aDb -> deleteWithPk( $id);
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

	function getListImage()
	{
		$to_date = date('d-m-Y') ;
		$from_date = date('d-m-Y', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		$where = 'status = 1 ';
		if(isset($_GET['type']) && $_GET['type'] != '')
			$where .= " AND type = ". $_GET['type'];

		/*if($_POST['filter'] != "")
			$where .= " AND name like '%". strtolower(trim($_POST['filter'])) . "%'";

		if($_POST['from_date'] != "") {
			$from_date = $_POST['from_date'];
		}
		
		if($_POST['to_date'] != "") {
			$to_date = $_POST['to_date'];
		}

		$where .= " AND createdate >= '". preg_replace('#(\d{2})-(\d{2})-(\d{4})#', '$3-$2-$1', $from_date) . "'";

		$where .= " AND createdate <= '". preg_replace('#(\d{2})-(\d{2})-(\d{4})#', '$3-$2-$1', $to_date) . "'";*/

		$limit = 12;

		$page = isset($_GET['page'])?$_GET['page']:1;

		if($_GET['filter']) {
			$where .= " AND name like '%".$_GET['filter']."%'";
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
					$error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 202;
				}

				if($_FILES["avatar"]["size"] > 1024000) {
					$error = "Sorry, your file image is too large.";
					$uploadOk = 179;
				}
				$avatar = $this -> renameFile($avatar);
				$_POST['avatar'] = $file_folder.$avatar;
				move_uploaded_file($_FILES["avatar"]["tmp_name"], SITE_DIR.$file_folder.$avatar);
			}

			if($_FILES['file_media']['name']!='')
			{
				if(isset($_GET['id'])) {
					$this -> deleteFile($_GET['id'], 'url');
				}
				$file_media = basename($_FILES["file_media"]["name"]);
				$extension = array('mp4', 'mp3', 'flv');
				$uploadOk = 1;
				if(!$this -> checkExtensionFileUpload($extension, $file_media)) {
					$error = "Sorry, only mp4, mp3, flv files are allowed.";
					$uploadOk = 202;
				}

				if($_FILES["file_media"]["size"] > 1024000000) {
					$error = "Sorry, your file is too large.";
					$uploadOk = 179;
				}

				$file_media = $this -> renameFile($file_media);

				$_POST['file_media'] = $file_folder.$file_media;
				if($uploadOk == 1) {
					move_uploaded_file($_FILES["file_media"]["tmp_name"], SITE_DIR.$file_folder.$file_media);
				}
			}
			
			if($uploadOk == 1) {
				$aData['avatar'] = $_POST['avatar'];
				$aData['url'] = $_POST['file_media'];
				if( !$_POST['id'] ){
					$id = $this -> aDb -> insert($aData);
				}else {
					$id = $_POST['id'];
					$this -> aDb -> updateWithPk($id,$aData);
				}
				$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
			}else {
				echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
			}
		}

		$this -> assign('detail', $data);
		$this -> display('creater_media_admin.tpl');
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