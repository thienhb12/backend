<?php
class tintucBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "articles";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'tintuc';
		$this -> type = 'sp';
		$this -> img_path = "/upload/tintuc/";
		$this -> img_path_thumb = "/upload/tintuc/thumb/";
	}

	function run($task)
	{
        switch( $task ){                          
           case 'pager':
                    $this -> pager();
                    break;
            case 'add':
                    $this -> addItem();
                    break;
            case 'edit':
                    $this -> editItem();
                    break;
            case 'delete':
                    $this -> deleteItem();
                    break;
            default:
                    $this -> listItem( $_GET['msg'] );
                    break;
        }
	}

	function pager() {
		$template = 'list_tintuc_admin.tpl';
		$limit = 10;
		$page = isset($_GET['page'])?$_GET['page']:1;
		$where = "status = 1 AND lang_id = {$this -> lang_id}";
                if(!$this ->isOOPS()) {
                    $where .= " AND site_id = {$this -> site_id}";
                }
		if($_GET['filter']) {
                    $where .= " AND title like '%".$_GET['filter']."%'";
		}

		$num_rows = $this -> db -> getOne("SELECT count(id) from ".$this->table." WHERE {$where}");
		$eu = $limit*($page-1);

		$sql = "SELECT id, title, sub_title FROM ".$this -> table;
		$sql .= " WHERE {$where} order by ID DESC LIMIT $eu, $limit ";
		//echo $sql;
		$items = $this -> db -> getAll($sql);
		$result['total_pages'] = ceil($num_rows/ $limit);
		$result['items'] = $items;
		echo json_encode($result);
	}

	function addItem(){
		$this -> buildForm();
	}
	
	function editItem(){
		$row = $this -> getById();
		$row['Content'] =  $this->getOne("SELECT Content FROM tbl_product_content WHERE ProID= ".$row['id']);		
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{
		$this -> deleteImage($this -> id,"avatar",$this -> img_path);
		$this -> deleteByid();
		$this -> backPageList();
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
			if($this ->indexOf($_POST['create_date'], ' AM') || $this ->indexOf($_POST['create_date'], ' FM')) {
				$_POST['create_date'] = str_replace(" AM", "", $_POST['create_date']);
				$_POST['create_date'] = str_replace(" FM", "", $_POST['create_date']);
			}
		
			$aData  = array(
				"lang_id"=> ($_POST['lang_id'])?$_POST['lang_id']:"1",
				"site_id" => $this -> site_id,
				"cate_id" => $_POST['cate_id'],
				"title" => $_POST['title'],
				"sub_title" => $_POST['sub_title'],
				"ext" => $_POST['ext'],
				"lead" => $_POST['lead'],
				"keyword" => $_POST['keyword'],
				"content" => $_POST['content'],
				"tagcloud" => $_POST['tagcloud'],
				"is_hot" => ($_POST['is_hot']== 'on')?1:0,
				"is_home" => ($_POST['is_home']== 'on')?1:0,
				"create_date" => $_POST['create_date'],
				"type" => $this -> type,
				"status" => $_POST['status'],
				"is_cache" => 0,
				"ordering" => ($_POST['ordering'])?$_POST['ordering']:"1"
			);

			if ($_POST['avatar']!='')
				$aData['avatar'] = $_POST['avatar'];

			if( !$_POST['id'] ){
                $this -> id = $this -> insert($aData);
			}else {
                $this -> updateById($aData);
			}	
			parent::tagsCloud($_POST['tagcloud']);

			$aData = array(
                "page_url" => '/'.strtolower(parent::removeMarks(stripcslashes($_POST['Name'])))."-a{$this -> id}.html"
			);
			$this -> updateById($aData);
            $this ->addRelated($this -> id, $_POST['related_id']);
			$this->clearCacheArticle();
			
			$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
		}
		$category = $this -> getCategory($this -> type);
		$data['create_date'] = ($data['create_date']) ? ($data['create_date']) : date("Y-m-d H:i");
		$this -> assign('detail', $data);
		

		$this -> assign('list_related', $this->getRelatedById($this -> id));
        $this -> assign('category', $category);

		$this -> assign('lang', parent::getAssocLang());
		$this -> display('backend/tintuc.backend.tpl');
	}

	function clearCacheArticle() {
		global $cache;
		$cache -> delete("news_article_{$this -> id}", "article");
	}
       
    function getCategory($lang_id = 0 )
	{
		$cond = "site_id = {$this -> site_id}";
		$result = parent::multiLevel( "categories","id","parent_id","*",$cond,"ordering ASC");
		
		$category = array();
		foreach ($result as $value => $key)
		{
			if( $key['level'] > 0){
				$name = $this -> getPrefix( $key['level']).$key['name'];
			}
			else 
				$name = $key['name'];
			$category[$key['id']] = $name;
		}
		
		return $category;
	}
	

	function deleteImage($id,$field,$path){
		if($id == '')
			return;
		$imgpath = $path.$this -> db-> getOne("SELECT $field FROM ".$this -> table." WHERE id = $id");
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
		$root_path = "{$this -> getConfigVars('list')} {$this -> getConfigVars('news')}";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		$where = " 1 ";
		
		$table = "(SELECT * FROM {$this -> table} WHERE {$where}) a";

		$arr_filter= array(
			array(
				'field' => 'title',
				'display' => "Tiêu đề",
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);
		

		$arr_filter[] = array(
				'field' => 'cat_id',
				'display' => 'Danh mục',
				'name' => 'filter_cate',
				'selected' => $_REQUEST['filter_cate'],
				//'options' => $this -> getCategory($this -> type),
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
				"display" => 'Tiêu đề',
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "cate_id",
				"display" => "Danh mục",
				"sql"=> "SELECT name FROM categories WHERE id = cate_id",
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "avatar",
				"display" => "Ảnh",
				"datatype" => "img"
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
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}

?>