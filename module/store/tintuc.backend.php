<?php
class tintucBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "articles";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		parent::getRootPath();
		$this -> mod = 'tintuc';
		$this -> type = 'tin-tuc';
		$this -> img_path = "/upload/tintuc/";
		$this -> img_path_thumb = "/upload/tintuc/thumb/";
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
		$this -> deleteImage($id,"avatar",$this -> img_path);
		$this -> aDb -> deleteWithPk( $id );
		$msg = "Xóa bài tin bài thành công!";
		$this -> listItem( $msg );
	}
	
	function deleteItems()
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			for($i = 0 ; $i < count($aItems); $i++) {
				$this -> deleteImage($aItems[$i], "avatar", $this -> img_path);
				$this -> aDb -> deleteWithPk( $aItems[$i] );
			}
		}
		$msg = "Xóa (các) bài tin bài thành công!";
		$this -> listItem( $msg );
	}
	
	function changestatusMultiple( $status = 0 )
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',',$aItems );
			$this -> aDb -> updateWithPk( $sItems,array("status" => $status) );
		}
		$msg = "Sửa trạng thái bài tin bài thành công!";
		$this -> listItem( $msg );
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
        $msg = "Lưu thứ tự bài viết thành công!";
        $this -> listItem( $msg );
    }

	function indexOf($str, $seach ) {
		$pos = strrpos($str, $seach);
		if($pos===false) {
			return false;
		} else {
			return substr($str, $pos+1);
		}
	}
	
	function buildForm( $data=array() ,$msg = ''){
	
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if($_FILES['avatar']['name']!='')
			{
				$this -> deleteImage($this -> id,"avatar",$this -> img_path);
				$this -> deleteImage($this -> id,"avatar",$this -> img_path_thumb);
				$Filename = rand().'_'.$_FILES['avatar']['name'];
				parent::crop_image($_FILES['avatar']['tmp_name'],$Filename,SITE_DIR.$this -> img_path,300, 400);
				parent::crop_image($_FILES['avatar']['tmp_name'],$Filename,SITE_DIR.$this -> img_path_thumb, 150, 200);
				$_POST['avatar'] = SITE_URL.$this -> img_path.$Filename;
			}

			if($this ->indexOf($_POST['create_date'], ' AM') || $this ->indexOf($_POST['create_date'], ' FM')) {
				$_POST['create_date'] = str_replace(" AM", "", $_POST['create_date']);
				$_POST['create_date'] = str_replace(" FM", "", $_POST['create_date']);
			}
			
			$aData  = array(
				"lang_id"=> ($_POST['lang_id'])?$_POST['lang_id']:"1",
				"cate_id" => $_POST['cate_id'],
				"title" => $_POST['title'],
				"ext" => $_POST['ext'],
				"lead" => $_POST['lead'],
				"content" => $_POST['content'],
				"tagcloud" => $_POST['tagcloud'],
				"is_hot" => ($_POST['is_hot']== 'on')?1:0,
				"create_date" => $_POST['create_date'],
				"is_home" => $_POST['is_home'],
				"type" => $this -> type,
				"status" => $_POST['status'],
				"ordering" => ($_POST['ordering'])?$_POST['ordering']:"1"
			);

			if ($_POST['avatar']!='')
				$aData['avatar'] = $_POST['avatar'];
				
			if( !$_POST['id'] ){
				$id = $this -> aDb -> insert($aData);
				$msg = "Thêm bài tin bài thành công! ";
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id,$aData);
				$msg = "Chỉnh sửa bài tin bài thành công ";
			}

			parent::tagsCloud($_POST['tagcloud']);

			$aData = array(
				"page_url" => '/' . $this -> type . '/'.$id.'/'.strtolower(parent::removeMarks(stripcslashes($_POST['title']))).'.html'
			);
			$this -> aDb -> updateWithPk($id,$aData);
			$this->setCache();

			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}
		$data['create_date'] = ($data['create_date']) ? ($data['create_date']) : date("Y-m-d H:i");
		$this -> assign('detail', $data);
		$category = $this -> getCategory($this -> type);
		$this -> assign('category', $category);
		
		$this -> clearCache('creater_tintuc_admin.tpl');
		$this -> display('creater_tintuc_admin.tpl');
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
	
	function listItem( $sMsg= '' )
	{
		$this -> loadConfig();
		$root_path = "Quản lý tin bài > Danh sách tin bài";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table} WHERE type = '{$this -> type}') a";
		
		$arr_filter= array(
			array(
				'field' => 'title',
				'display' => $this->getConfigVars('title'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);
		if(MULTI_LANGUAGE)
		$arr_filter[] = array(
				'field' => 'lang_id',
				'display' => $this->getConfigVars('language'),
				'name' => 'filter_cat',
				'selected' => $_REQUEST['filter_cat'],
				'options' => parent::getAssocLang(),
				'filterable' => true
			);

		$arr_filter[] = array(
				'field' => 'cate_id',
				'display' => $this->getConfigVars('categories'),
				'name' => 'filter_cate',
				'selected' => $_REQUEST['filter_cate'],
				'options' => $this -> getCategory($this -> type),
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
				"field" => "title",
				"display" => $this->getConfigVars('title'),
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),

			array(
				"field" => "cate_id",
				"display" => $this->getConfigVars('categories'),
				"sql"=> "SELECT name FROM categories WHERE id = cate_id",
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "avatar",
				"display" => $this->getConfigVars('avatar'),
				"datatype" => "img"
			),
			array(
				"field" => "count",
				"display" => $this->getConfigVars('view'),
				"datatype" => "text"
			),
			array(
				"field" => "ordering",
				"display" => $this->getConfigVars('ordering'),
				"datatype" => "order",
				"sortable" => true
			),
			array(
				"field" => "status",
				"display" => $this->getConfigVars('status'),
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

		if( $sMsg ) {
			$this -> datagrid -> setMessage( $sMsg );
			$this -> setCache();
		}
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}

?>