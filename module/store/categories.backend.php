<?php
/** 
	@nxan
	@Sửa 10/4
**/
class categoriesBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "categories";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		parent::getRootPath();
		$this -> type = 'tin-tuc';
		$this -> mod = 'tintuc';
		$this -> img_path = "upload/categories";
		$this -> img_path_thumb = "/upload/categories/thumb";
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
			case 'ajax_form':
				$this->ajax_form();
				break;
			default:
				$this -> listItem( $_GET['msg'] );
				break;
		}
	}

    function ajax_form()
    {
        $lang_id = $_REQUEST['lang_id'];
        $category = array(0 => "- - - Quản lý danh mục gốc - - -" ) + $this->getAssocCategory($lang_id);
        $option_str = parent::changeOption($category);
        echo $option_str;
    }

	function addItem()
	{
		$this -> buildForm();
	}
	
	function editItem()
	{
		$id = $_GET['id'];
		$row = $this -> aDb -> getRow( $id );
		$this -> buildForm( $row );
	}
	
	function hasSubItem($parent_id)
	{
		$sub = $this -> db->getOne("SELECT count(*) FROM {$this->table} WHERE parent_id = {$parent_id}");
		$item = $this -> db->getOne("SELECT count(*) FROM {$this->table} WHERE parent_id = {$parent_id}");
		
		if ($sub>0 || $item>0)
			return true;
		else
			return false;
	}

	function deleteItem()
	{
		$id = $_GET["id"];
		if (!$this->hasSubItem($id)) {
			$this -> aDb -> deleteWithPk( $id );
			$msg = "Xóa Quản lý danh mục thành công!";
		}
		else 	
			$msg = "Không thể xóa danh mục này vì còn chứa danh mục con hoặc sản phẩm";
		$this -> listItem( $msg );
	}
	
	function deleteItems()
	{
		$aItems = $_GET['arr_check'];
		$del = true;
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',',$aItems );
			if (!$this->hasSubItem($sItems)) {
				$this -> aDb -> deleteWithPk( $sItems );
			}
			else 
				$del = false;
		}
		if ($del)
			$msg = "Xóa (các) danh mục thành công!";
		else 
			$msg = "Không thể xóa toàn bộ danh mục vì còn chứa danh mục con hoặc sản phẩm";
		$this -> listItem( $msg );
	}
	
	function changestatusMultiple( $status = 0 )
	{
		$aItems = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',',$aItems );
			$this -> aDb -> updateWithPk( $sItems,array("status" => $status) );
		}
		$msg = "Sửa trạng thái danh mục thành công!";
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
		$msg = "Lưu thứ tự danh mục thành công!";
		$this -> listItem( $msg );
	}
	
	function buildForm( $data=array() ,$msg = '')
	{
		$form = new HTML_QuickForm('frmAdmin','post',$_COOKIE['re_dir']."&task=".$_GET['task'],'',"style='padding:10px 15px 0 20px;'");
		$form -> setDefaults($data);
		if (MULTI_LANGUAGE)
		{
			$lang = parent::getAssocLang();
			$form -> addElement('select','lang_id',$this ->getConfigVars('language', 'Ngôn ngữ'),$lang,array('id'=>'cat_form_lang'));
			if ($_GET['task']=='edit')
				$selected_langid = $data['lang_id'];
			else
				$selected_langid = $this -> db->getOne("SELECT id FROM tbl_sys_lang ORDER BY id ASC LIMIT 1");
		}
		else
			$selected_langid = 1;
		
		// Đối với các site yêu cầu Quản lý danh mục sản phẩm đa cấp
		$aParent = array(0 => "- - - Danh mục gốc - - -" ) + $this -> getAssocCategory();
		$form -> addElement('select','parent_id','Danh mục cha',$aParent,array('id'=>'cat_form_parent'));
		
		$form -> addElement('text','name','Tên danh mục',array('size' => 50,'maxlength' => 255));
		$form->addElement('textarea','lead','Mô tả',array('style'=>'width:600px;height:100px;'));
		$form->addElement('file','avatar','Ảnh');
		
		if($_GET['task']=='edit')
			$form->addElement('static',null,'',"<a href='".SITE_URL.$data['avatar']."' onclick='return hs.expand(this)' class='highslide'><img src='".SITE_URL.$data['avatar']."' width=100 hight=100 border=0></a>");
		
		$form -> addElement('text','ordering','Thứ tự',array('size' => 10,'maxlength' => 50));
		$form -> addElement('checkbox','is_home','Hiển thị trang chủ');
		$form -> addElement('checkbox','status','Kích hoạt');
		
		$btn_group[] = $form -> createElement('submit',null,'Hoàn tất',array("style"=> "border:1px solid gray;padding:0 5px 0 5px;"));
		$btn_group[] = $form -> createElement('button',null,'Quay lại',array('onclick'=>'window.location.href = \''.$_COOKIE['re_dir'].'\'',"style"=> "border:1px solid gray;"));
		$form -> addGroup($btn_group);
		$form->addElement('hidden','id',$data['id']);
		$form -> addRule('name','Tên Quản lý danh mục không được để trống','required',null,'client');

		if( $form -> validate())
		{
			if($_FILES['avatar']['name']!='')
			{
				$this -> deleteImage($this->id,"avatar",$this->img_path);
				$this -> deleteImage($this->id,"avatar",$this->img_path_thumb);
				$Filename = rand().'_'.$_FILES['avatar']['name'];
				parent::crop_image($_FILES['avatar']['tmp_name'],$Filename, SITE_DIR.$this -> img_path,800,600,false);
				$file_name = parent::crop_image($_FILES['avatar']['tmp_name'],$Filename, SITE_DIR.$this -> img_path_thumb,150,200,false);
				$_POST['avatar'] = $this -> img_path_thumb . '/' . $file_name;
			}

			if(!$_POST['lang_id']) {
				 $_POST['lang_id'] = 1;
			}

			$aData  = array(
				"name" => $_POST['name'],
				"lang_id" => $_POST['lang_id'],
				"lead" => $_POST['lead'],
				"parent_id" => $_POST['parent_id'],
				"is_home" => $_POST['is_home'],
				"ordering" => $_POST['ordering'],
				"status" => $_POST['status'],
				"type" => $this -> type,
			);

			$aData['root'] = $this -> getRootByParentId($_POST['parent_id'], $_POST['name']);
			if ($_POST['avatar']!='')
				$aData['avatar'] = $_POST['avatar'];

			if( !$_POST['id'] ){
				 $id = $this -> aDb -> insert($aData);
				 $msg = "Thêm Quản lý danh mục sản phẩm thành công! ";
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id,$aData);
				$msg = "Chỉnh sửa Quản lý danh mục tức tin dành công ";
			}

			$aData  = array(
				"page_url" => '/' . $this -> type .'/c-'.$id.'/'.strtolower(parent::removeMarks(stripcslashes($_POST['name']))).'/trang-1.html'
			);
			$this -> aDb -> updateWithPk($id,$aData);
			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}
		
		$form->display();
	}
	
	function deleteImage($id,$field,$path){
		if($id == '')
			return;
		$imgpath = $path.$this->db->getOne("SELECT $field FROM ".$this->table." WHERE id = $id");
		if(is_file($imgpath))
			@unlink($imgpath);
	}
	
	function getCategory($lang_id=0)
	{
		$cond = '';
		$result = parent::multiLevel( $this->table,"id","parent_id","*",$cond,"ordering ASC");
		
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
	
	function changestatus( $itemId ,$status ){
		$aData = array( 'status' => $status );
		$this -> aDb -> updateWithPk( $itemId,$aData );
		return true;
	}

	function getAssocCategory($parent_id = -1, $type = -1)
	{
		$sql = "SELECT id, root,parent_id from " . $this -> table . " WHERE type = '" . $this -> type . "' AND status = 1 ";
		if ($type >= 0) {
			$sql .= " AND type = ".$type;
		}
		if ($parent_id > 0) {
			$sql .= "AND parent_id = ".$parent_id;
		}
		$sql .= " order by root, ordering";
		//echo $sql;
		$arr = $this -> db -> getAll($sql);
		$array[0] = "---------Chọn chuyên mục ----------";
		for($i = 0; $i < count($arr); $i++)
		{
			$array[$arr[$i]['id']] = $arr[$i]['root'];
		}
		return $array;
	}

	function getRootByParentId($parentId, $name)
	{
		$root = "";
		if ($parentId == 0) {
			$root = $name;
		}else {
			$str = $this -> db -> getOne("SELECT name FROM ". $this -> table ." WHERE id = {$parentId}");
			$root =  $str. ' >> ' . $name;
		}
		return $root;
	}

	function listItem( $sMsg= '' )
	{

		$root_path = "Quản lý sản phẩm > Quản lý danh mục";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table} WHERE type = '{$this -> type}') a";
		
		$order = ($_GET['sort_by'])?($_GET['sort_by']):'ordering';
		$orderType = $_GET['sort_value'];
		if( $_GET['filter_title']!= '')
			$where[] = " name like '%{$_GET['filter_title']}%'";
		if( $_GET['filter_show']!= '')
			$where[] = " status = '{$_GET['filter_show']}'";

		if( is_array( $where) && count( $where )> 0)
			$condition = implode( " and ",$where );

		$aData = $result = parent::multiLevel($table,"id","parent_id","*",$cond,"ordering ASC");

		foreach ( $aData as $key => $row){
			if( $row['level'] > 0){
				$aData[$key]['name'] = $this -> getPrefix( $row['level']).$row['name'];
			}
		}
		
		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => 'Tên Quản lý danh mục',
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
                'options' => array($this -> getConfigVars('unpublic'),$this -> getConfigVars('public')),
                'filterable' => true
            );
		
		$arr_cols= array(
			array(
				"field" => "root",
				"display" => $this -> getConfigVars('categories'),
				"align"    => 'left',
				"datatype" => "text",
				"sortable" => true,
				"order_default" => "asc"
			),
			array(
				"field" => "page_url",
				"display" => "Link",
				"datatype" => "text"
			)
        );
        if (MULTI_LANGUAGE)    
            $arr_cols[] = array(
				    "field" => "Lang",
				    "display" => $this -> getConfigVars('language'),
				    "align"	=> 'left',
				    "datatype" => "text",
				    "sortable" => true
			    );
		
        $arr_cols_more = array( 
            array(
				"field" => "ordering",
				"display" => $this -> getConfigVars('ordering'),
				"datatype" 		=> "order",
				"sortable" => true
			),
        	array(
				"field" => "status",
				"display" => "Trạng thái",
				"datatype" => "publish",
				"sortable" => true
			)
        );
		$arr_cols = array_merge($arr_cols,$arr_cols_more);
		
		$arr_check = array(
			array(
				"task" => "delete_all",
				"confirm"	=> "Xác nhận xóa?",
				"display" => $this -> getConfigVars('delete')
			),
			array(
				"task" => "public_all",
				"confirm"	=> "Xác nhận thay đổi trạng thái?",
				"display" => "Kích hoạt"
			),
			array(
				"task" => "unpublic_all",
				"confirm"	=> "Xác nhận thay đổi trạng thái?",
				"display" => "Vô hiệu"
			)
		);
		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table,$arr_cols,$arr_filter,"?".$_SERVER['QUERY_STRING'],$arr_action,null,$root_path,false,$arr_check);
	}
}

?>