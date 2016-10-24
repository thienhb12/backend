<?php
class imagesBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "images";
		parent::__construct($smarty,$db,$datagird,$table);
		parent::getRootPath();
		$this -> mod = 'images';
		$this -> type = 'images';
		$this -> img_path = "/upload/images/";
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
			case 'list':
				$this -> getListImage();
				break;
			case 'manage':
				$this -> manageImage();
				break;
			case 'upload':
				$this -> upload($_GET['id'],$_GET['type']);
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
		$this -> aDb -> updateWithPk( $id, array("status" => 9) );
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

	function manageImage()
	{
		$to_date = date('d-m-Y') ;
		$from_date = date('d-m-Y', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$aData  = array(
				"description" => $_POST['description'],
				"user_id" => $_SESSION['user_id']
			);
			if($_FILES['avatar']['name']!='')
			{
				$file_name = basename($_FILES["avatar"]["name"]);
				$extension = array('jpg', 'png', 'gif');
				$uploadOk = 1;
				if(!$this -> checkExtensionFileUpload($extension, $file_name)) {
					echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";exit();
					$uploadOk = 0;
				}

				if($_FILES["avatar"]["size"] > 1024000) {
					echo "Sorry, your file is too large."; exit();
					$uploadOk = 0;
				}

				if($uploadOk) {
					$file = getimagesize($_FILES["avatar"]["tmp_name"]);
					$aData['width'] = $file[0];
					$aData['height'] = $file[1];
					$aData['createdate'] = date("Y-m-d");
					$aData['name'] = $this -> renameFile($file_name);
					$file_folder = $this -> img_path.date("Ymd").'/';
                                        $file_folder_thumb = $file_folder.'thumb/';
					$_POST['url'] = $file_folder.$aData['name'];

					if(!file_exists($file_folder)) {
						 mkdir(SITE_DIR.$file_folder , 0777);
					}
                                        if(!file_exists($file_folder_thumb)) {
						 mkdir(SITE_DIR.$file_folder_thumb , 0777);
					}
                                        echo 1; exit();
                                        parent::crop_image($_FILES['avatar']['tmp_name'],$aData['name'],SITE_DIR.$file_folder_thumb, 150, 200);
                                        $_POST['avatar'] = SITE_URL.$this -> img_path.$Filename;
					move_uploaded_file($_FILES["avatar"]["tmp_name"], SITE_DIR.$file_folder.$aData['name']);
					$aData['url'] = $_POST['url'];
					$image_id = $this -> aDb -> insert($aData);
				}
			}
		}
		$where = " url != '' ";

		if($_POST['scope'] == 0)
			$where .= " AND user_id = ". $_SESSION['user_id'];

		if($_POST['filter'] != "")
			$where .= " AND description like '%". strtolower(trim($_POST['filter'])) . "%'";

		if($_POST['from_date'] != "") {
			$from_date = $_POST['from_date'];
		}
		
		if($_POST['to_date'] != "") {
			$to_date = $_POST['to_date'];
		}
		$where .= " AND createdate >= '". preg_replace('#(\d{2})-(\d{2})-(\d{4})#', '$3-$2-$1', $from_date) . "'";
		$where .= " AND createdate <= '". preg_replace('#(\d{2})-(\d{2})-(\d{4})#', '$3-$2-$1', $to_date) . "'";

		$limit = 10;

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
		$this -> getActionForm();
		$this -> assign('images', $items);
		$this -> assign('to_date', $to_date);
		$this -> assign('from_date', $from_date);
		$this -> display('manage_image_admin.tpl');
	}

	function getListImage()
	{
		$to_date = date('d-m-Y') ;
		$from_date = date('d-m-Y', mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")));
		$where = " url != '' ";
		if($_POST['scope'] == 0)
			$where .= " AND user_id = ". $_SESSION['userid'];

		if($_POST['filter'] != "")
			$where .= " AND description like '%". strtolower(trim($_POST['filter'])) . "%'";

		if($_POST['from_date'] != "") {
			$from_date = $_POST['from_date'];
		}
		
		if($_POST['to_date'] != "") {
			$to_date = $_POST['to_date'];
		}
		$where .= " AND createdate >= '". preg_replace('#(\d{2})-(\d{2})-(\d{4})#', '$3-$2-$1', $from_date) . "'";
		$where .= " AND createdate <= '". preg_replace('#(\d{2})-(\d{2})-(\d{4})#', '$3-$2-$1', $to_date) . "'";

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

		if(count($items) == $limit) {
			$result['next'] = "1";
		}
		
		echo json_encode($result);
	}

	function upload() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if($_FILES['Filedata']['name']!='')
			{
				$file_name = basename($_FILES["Filedata"]["name"]);
				$extension = array('jpg', 'png', 'gif');
				$uploadOk = 1;
				if(!$this -> checkExtensionFileUpload($extension, $file_name)) {
					//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 202;
				}

				if($_FILES["Filedata"]["size"] > 1024000) {
					//echo "Sorry, your file is too large.";
					$uploadOk = 179;
				}
				if($uploadOk == 1) {
					$file = getimagesize($_FILES["Filedata"]["tmp_name"]);
					$aData['width'] = $file[0];
					$aData['height'] = $file[1];
					$aData['createdate'] = date("Y-m-d");
					$aData['name'] = $this -> renameFile($file_name);
					$aData['description'] = $_POST['description'];
					$file_folder = $this -> img_path.date("Ymd").'/';
					$_POST['url'] = $file_folder.$aData['name'];

					if(!is_dir(SITE_DIR.$file_folder)) {
						 mkdir(SITE_DIR.$file_folder , 0777);
					}
                                        
                                        $file_folder_thumb = $file_folder.'thumb/';
                                        if(!file_exists(SITE_DIR.$file_folder_thumb)) {
						 mkdir(SITE_DIR.$file_folder_thumb , 0777);
					}
                                                                                
                                        //parent::crop_image($_FILES['avatar']['tmp_name'],$aData['name'],SITE_DIR.$file_folder_thumb, 150, 200);
					move_uploaded_file($_FILES["Filedata"]["tmp_name"], SITE_DIR.$file_folder.$aData['name']);
					$aData['url'] = $_POST['url'];
					$image_id = $this -> insert($aData);
				}

				echo '<script type="text/javascript">window.parent.OnUploadCompleted('.$uploadOk.',"' .$aData['url'].'","'.$aData['url'].'","'.$aData['name'].'");</script>';
			}
		}
	}
	
	function buildForm( $data=array() ,$msg = '')
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$aData  = array(
				"description" => $_POST['description']
			);

			if($_FILES['avatar']['name']!='')
			{
				$file_name = basename($_FILES["avatar"]["name"]);
				$extension = array('jpg', 'png', 'gif');
				$uploadOk = 1;
				if(!$this -> checkExtensionFileUpload($extension, $file_name)) {
					echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 0;
				}

				if($_FILES["avatar"]["size"] > 1024000) {
					echo "Sorry, your file is too large.";
					$uploadOk = 0;
				}
				if($uploadOk) {

					if(is_file(SITE_DIR.$_POST['url']))
						@unlink(SITE_DIR.$_POST['url']);
					$file = getimagesize($_FILES["avatar"]["tmp_name"]);
					$aData['width'] = $file[0];
					$aData['height'] = $file[1];
					$aData['createdate'] = date("Y-m-d");
					
					$file_folder = $this -> img_path.date("Ymd").'/';
					echo $file_folder;
					$_POST['url'] = $file_folder.$aData['name'];
					if(file_exists($file_folder)) {
						 mkdir(SITE_DIR.$file_folder , 0777);
					}

					move_uploaded_file($_FILES["avatar"]["tmp_name"], SITE_DIR.$file_folder.$aData['name']);
				}
			}

			$aData['url'] = $_POST['url'];

			if( !$_POST['id'] ){
				$id = $this -> aDb -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id,$aData);
			}

			//$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
		}

		$this -> assign('detail', $data);
		$this -> display('creater_image_admin.tpl');
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