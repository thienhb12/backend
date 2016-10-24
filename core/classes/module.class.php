<?php
defined('ANPRO') or exit('404 - Not Found');
abstract class Anpro_Module_Base
{
	public $smarty;
	public $db;
	public $datagrid;
	public $table;
	public $pkId = 'id';
	public $id ;
	public $lang_id;
	public $site_id;
	public $mod;
	public $type;
	public $debug = false;
	public function run($task){}
	public function getPageInfo($task){}
	public function msg($task = null)
	{
		return "";
	}

	function set_array_check()
	{
		$arr_check = array(
			array(
			'task' => 'delete_all',
			'confirm' =>  'Bạn có chắc chắn muốn xóa tất cả?',
			'display' => 'Xóa tất cả'
			),
			array(
				'task' =>  'public_all',
				'confirm' =>  'Bạn có chắc chắn muốn kích hoạt?',
				'display' =>  'Kích hoạt',
			),
			array(
				'task' => 'unpublic_all',
				'confirm' => 'Bạn có chắc chắn muốn vô hiệu?',
				'display' => 'Vô hiệu',
			)
		);
		return $arr_check;
	}

	public function __construct(&$smarty = null, &$db = null, &$datagrid = null, &$table = null, &$id = null) {
		$this -> smarty = $smarty;
		$this -> db = $db;
		$this -> datagrid = $datagrid;
		$this -> table = $table;
		if(isset($_REQUEST['id'])) {
			$this -> id = $_REQUEST['id'];
		}
		if(isset($_SESSION['lang_id'])) {
			$this -> lang_id = $_SESSION['lang_id'];
		}else {
                    $this -> lang_id = 1;
                }

		if(isset($_SESSION['site_id'])) {
			$this -> site_id = $_SESSION['site_id'];
		}
	}
    function getRootPath ($default = '') {
		if ($default != '') {
			return $default;
		}
		$sub = isset($_GET['sub']) ? $_GET['sub'] : NULL;
		$mod = isset($_GET['mod']) ? $_GET['mod'] : NULL;
		$task = isset($_GET['task']) ? $_GET['task'] : NULL;
		if ($sub) {
			$root_Path=$this->getConfigVars($sub . "_sub_root_path");
		}
		if($task) {
			$root_Path=$this->getConfigVars($task."_task_".$sub."_root_path");
			
		}
		//$root_path = str_replace(">","<span class='nav'></span>",$root_Path);
		//$str = "<table cellspacing='0' cellpadding='0' width='100%'>";
		// $str = "<tr><td valign='middle' id='root'>".$root_path."</td></tr></table>";

		if(!isset($_GET['ajax']))
			echo $str;
	}
	

	/****************************FUNCTIONS FOR DATAGRID*************************************/
	public function isPost()
	{
		return (strtoupper($_SERVER['REQUEST_METHOD'])=='POST') ? true : false;
	}

	function contains($text, $key)
	{
		return (strpos($str, $key) !== FALSE);
	}

	function redirect($url,$type="location") {
		$url = $url!='' ? $url : SITE_URL;
		echo '<script language = "javascript">
				location.href = "'.$url.'";
				</script>
		';
	}

	function backPageList() {
		$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&filter_title=".$_GET['filter_title']."&per_page=".$_GET['per_page']."&page=".$_GET['page']));
	}

	/*
		// get Action .
		@ Date create : 2008-21-1
		@ Author : Khanhnk
		@ Paramater :
			1. $action: action in form. Default will get Action: Add, Edit, Delete
	*/	
	function getAct( $action=''){

		if(!$action){
			if(is_array($this-> getActionAdd()))
				$result[] = $this-> getActionAdd();
			if(is_array($this-> getActionEdit()))
				$result[] = $this-> getActionEdit();
			if(is_array($this-> getActionDelete()))
				$result[] = $this-> getActionDelete();
			if(is_array($this-> getActionEditor()))
				$result[] = $this-> getActionEditor();
		}else{
			$action = strtolower( $action );
			switch( $action ){
				case 'add':
					$result = $this-> getActionAdd();
					break;
				case 'edit':
					$result = $this-> getActionEdit();
					break;
				case 'delete':
					$result = $this-> getActionDelete();
					break;
				case 'editor':
					$result = $this-> getActionEditor();
					break;
				default: $result = $this -> getActionOther( $action );
					break;
			}
			if(is_array($result))
				$result = array($result);
		}
		
		return $result;
	}
	
	function getActionOther( $action ){
		$sql = "select id from tbl_sys_roll where name='{$action}'";
		$rollId = $this -> db -> getOne( $sql );
		if( !$rollId ) return "No Permission";
		if( $this -> checkPermission( $rollId )) return  array("task" => $action);
		else return "No Permission";
	}
	
	function checkPermission( $rollId ) {
		$link = "amod={$_GET['amod']}";

		if( $_GET['atask'] ) $link.= "&atask={$_GET['atask']}";
		if ($_GET['sys']) $link.="&sys={$_GET['sys']}";
		if ($_GET['task']) $link.="&task={$_GET['task']}";

		$userTypeId = $_SESSION['group_id'];

		if( $userTypeId == 0) return  true;

		$sql = "select t1.module_id from tbl_sys_module_roll t1 join tbl_sys_menu t2 on(t1.module_id = t2.id) where t1.roll_id = '{$rollId}' and t2.link = '{$link}' and t2.status='1' ";

		$moduleRoll = $this -> db -> getOne( $sql );

		if( $moduleRoll ){
			$sql = "select id from tbl_sys_groups_module where group_id = '{$userTypeId}' and module_id ='{$moduleRoll}' AND roll_id = {$rollId}";
			$checked = $this -> db -> getOne( $sql );
			if( $checked ) return true;
			else  return false;
		}else{
			return false;
		}
	}

	function modulePermission() {
		$where =  "";
		if($_SESSION['group_id'] > 0) {
                    $where .= "WHERE group_id = {$_SESSION['group_id']}";
                    $sql = "SELECT module_id FROM tbl_sys_groups_module {$where}";
		}else {
                    $sql = "SELECT id FROM tbl_sys_menu WHERE status = 1";
		}

		$list_module = $this -> db -> getCol($sql);
		return $list_module;
	}

	function provincePermission() {
		if($_SESSION['userid'] == 1) {
			$sql = "SELECT id FROM province";
			return $sql;
		}else {
			$sql = "SELECT province FROM tbl_sys_users WHERE id = {$_SESSION['userid']}";
			return $this -> db -> getOne($sql);
		}
	}

	function getActionAdd(){
		if( $this-> checkPermission( 1 ))
			return $result = array(
						"task" => "add",
						"action" => "",
						"tooltip" => ""
			);
		else 
			return $this -> getConfigVars('no_permission');
	}
	
	function getActionEdit(){
		if( $this-> checkPermission( 2 ))
		return $result = array(
					"task" => "edit",
					"icon" => "edit.png",
					"tooltip" => $this -> getConfigVars('edit')
				);
		else 
			return $this -> getConfigVars('no_permission');
	}
	
	function getActionDelete(){
		if( $this-> checkPermission( 3 ))
		return $result = array(
					"task" => "delete",
					"icon" => "delete.jpg",
					"confirm" => $this -> getConfigVars('confirm_delete'),
					"tooltip" => $this -> getConfigVars('delete')
				);
		else 
			return $this -> getConfigVars('no_permission');
	}

	function getActionEditor(){
		if( $this-> checkPermission( 4 ))
		return $result = array(
					"task" => "editor",
					"icon" => "editor.png",
					"tooltip" => $this -> getConfigVars('editor')
				);
		else 
			return $this -> getConfigVars('no_permission');
	}

	function getActionForm() {
		$action_url = "index.php?a";
		if(isset($_GET['mod']))
			$action_url .= "&mod=" . $_GET['mod'];
		if(isset($_GET['amod']))
			$action_url .= "&amod=" . $_GET['amod'];
		if(isset($_GET['atask']))
			$action_url .= "&atask=" . $_GET['atask'];
		if(isset($_GET['task']))
			$action_url .= "&task=" . $_GET['task'];
		if(isset($_GET['filter_title']))
			$action_url .= "&filter_title=" . $_GET['filter_title'];
		if(isset($_GET['per_page']))
			$action_url .= "&per_page=" . $_GET['per_page'];
		if(isset($_GET['page']))
			$action_url .= "&page=" . $_GET['page'];
		if(isset($_GET['sys']))
			$action_url .= "&sys=" . $_GET['sys'];
		if(isset($_GET['ajax']))
			$action_url .= "&ajax";

		$this -> assign('action_url', $action_url);
	}

	function getAssocLang()
	{
		$sql = "SELECT id,name FROM tbl_sys_lang";
		return $this -> db -> getAssoc($sql);
	}

	function getPrefix( $level ){
		$prefix = "&emsp;&emsp;";
		return str_repeat( $prefix, $level );
	}

	function getLangDefault()
	{
		return $this->db->getOne("SELECT id FROM tbl_sys_lang where default = 1");
	}
	
	public function crop_image($src, $name_file, $des_dir, $w=640, $h= 480, $active = true)
	{
		include_once(SITE_DIR.'core/thumbnail/thumbnail.class.php');
		$thumb=new Thumbnail($src);
		$thumb->size($w, $h);
		$thumb->output_format= "JPG";
		// Get file extension
		$ext = strtolower(end(explode(".", basename($name_file))));
		if($active && ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif')){
			$thumb->process();
			$res = $thumb->save($des_dir."/".$name_file);
                        echo $res; exit();
		}else
			move_uploaded_file($src, $des_dir."/".$name_file);
		return $name_file;
	}
	/*
		Câu lệnh truy vấn cơ sở dữ liệu;
	*/
//------------------------------------QUERY DATA BY ID--------------------------------------/
	private function checkValue ($value1, $value2)
	{
		if($value1 == null) $value1 = $value2;
		return $value1;
	}

	private function prepareInput ($value)
	{
		$value = stripslashes($value);
		return addcslashes($value,"'");
	}

	public function getById(&$id = null, $table = null, $field = null)
	{
		$field = $this->checkValue($field, $this->pkId);
		$table = $this->checkValue($table, $this -> table);
		$sql = "SELECT * FROM " . $table . " WHERE " . $field . "=";
		$sql .= $this->checkValue($id, $this -> id);
		if($this -> debug) echo($sql);
		return $this -> db -> getRow($sql);
	}

	public function deleteById(&$id = null, $table = null)
	{
		$table = $this -> checkValue($table, $this -> table);
		$field = $this -> checkValue($field, $this -> pkId);
		$sql = "DELETE FROM " . $table . " WHERE " . $field . "=";
		$sql .= $this -> checkValue($id, $this -> id);
		if($this->debug) echo($sql);
		$result = $this -> db -> query($sql);
		return (PEAR::isError($result)) ? false : true;
	}

	public function delete($cond, $table = null)
	{
		$table = $this->checkValue($table, $this -> table);
		$sql = "DELETE FROM " . $table . " WHERE " . $cond;
		if($this->debug) echo($sql);
		$result = $this->db->query($sql);
		return (PEAR::isError($result)) ? false : true;
	}

	public function updateById($data, $id = null, $table = null, $field = null)
	{
		$field = $this->checkValue($field, $this->pkId);
		$sql = $field . "=";
		$table = $this->checkValue($table, $this -> table);
		$sql .= $this->checkValue($id, $this -> id);
		$this -> updateField($sql, $data, $table);
	}

	public function updateField ($cond = NULL, $data = array(), $table = NULL) {
		if (!is_array($data)) {
			$data = (array) $data;
		}
		if(!empty($data)){
			$res = $this -> db -> autoExecute($table, $data, DB_AUTOQUERY_UPDATE, $cond);
			if ($this->debug) {
				pre($res);
			}
			return true;
		}else{
			return false;
		}
	}

	public function query($sql){
		$result = $this->db->query($sql);
		if ($this->debug) {
			pre($result);
		}
		if (PEAR::isError($result))
		{
			return false;
		}

		return $result;
	}

	function getCol($sql)
	{
		$result = $this -> db -> getCol($sql);
		if ($this->debug) {
			pre($result);
		}
		if (PEAR::isError($result))
		{
			return false;
		}
		return $result;
	}

	function getOne($sql)
	{
		$result = $this -> db -> getOne($sql);
		if ($this->debug) {
			pre($result);
		}
		if (PEAR::isError($result))
		{
			return false;
		}
		return $result;
	}

	function getAssoc($sql)
	{
		$result = $this -> db -> getAssoc($sql);
		if ($this->debug) {
			pre($result);
		}
		if (PEAR::isError($result))
		{
			return false;
		}
		return $result;
	}

	function getAll($sql)
	{
		$result = $this -> db -> getAll($sql);
		if ($this->debug) {
			pre($result);
		}
		if (PEAR::isError($result))
		{
			return false;
		}
		return $result;
	}
	public function insert($data, $table = null)
	{
		$table = $this -> checkValue($table, $this -> table);
                
		$sql = "INSERT INTO {$table} SET ";
		$sInsert = "";
		$doInsert = false;
		foreach ($data as $field => $value)
		{
			$sInsert .= "`{$field}`='{$this->prepareInput($value)}',";
			$doInsert = true;
		}

		if($doInsert)
		{
			$sInsert = substr($sInsert,0,strlen($sInsert)-1);
			$sql .= $sInsert;
			$result = $this-> db -> query($sql);
			if ($this->debug) {
				pre($result);
			}
			if (PEAR::isError($result))
			{
				return false;
			}
			else 
			{
				$insertId = $this->db->getOne( "SELECT last_insert_id()" );
				if (PEAR::isError($insertId))
				{
					return false;
				}
				else 
				{
					return $insertId;
				}
				
			}
			
		}
		return false;
	}

	//-----------------------------------------------------------------------------

	public function createCalendar($id, $value)
	{
		$path = '../core';
		$str = '<link type="text/css" rel="stylesheet" href="'.$path.'/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></link>
				<script type="text/javascript" src="'.$path.'/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
				<script language = "javascript">var pathToImages = "'.$path.'/calendar/images/";</script>
				';

		$str .= '<input type="text" value="'.$value.'" readonly name="'.$id.'"><input type="button" value="Select" onclick="displayCalendar(document.forms[0].'.$id.',\'yyyy-mm-dd hh:ii\',this,true)">';

		return $str;
	}
	
	function multiLevel( $table, $pkey, $parent, $sql_select = '*', $where = '', $order=''){
		$aResult = array();
		$this -> getCategoryMultiLevel($aResult, 0, 0, $table, $pkey, $parent, $sql_select, $where, $order );
		return  $aResult;
	}
	
	function getCategoryMultiLevel( &$aRef, $parentId, $level=0, $table, $pkey, $parent, $sql_select, $where='' , $order='' ){
		if( $where )$condition = " and {$where}";
		if( $order )$condition .= " order by {$order}";
		//echo $condition;
		if( $level == 0)
			$sql = "SELECT {$sql_select} FROM {$table} WHERE (`{$parent}` = '0' or `{$parent}` is NULL){$condition}";
		else
			$sql = "SELECT {$sql_select} FROM {$table} WHERE `{$parent}` = '{$parentId}' {$condition}";

		//echo $sql;
		$result = $this -> db -> getAll( $sql );
		if( $result ){
			if( $level > 0)
				$aRef[count($aRef)-1]['hashchild'] = true;
			foreach ( $result as $key => $val){
				$val['level'] = $level;
				$aRef[] = $val;
				$this -> getCategoryMultiLevel( $aRef, $val[$pkey], $level + 1, $table, $pkey, $parent, $sql_select, $where, $order  );
			}
		} 
	}

	//lấy danh mục theo parentId
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

	function removeMarks($string)
	{
		return removeMarks($string);
	}

	function indexOf($str, $seach ) {
		$pos = strrpos($str, $seach);
		if($pos===false) {
			return false;
		} else {
			return substr($str, $pos+1);
		}
	}

	function Paging($limit, $field = '*', $cond, $paging_path, $order=''){
		$sql = "SELECT $field FROM ".$this -> table;

		if($cond == '')
			$cond = "1=1";
		$page = isset($_GET['page'])?$_GET['page']:1;
		$limit = $limit;
		$num_rows = $this -> db -> getOne("SELECT count(id) from ".$this->table." WHERE $cond");
		$eu = $limit*($page-1);
		$to = $limit*$page;
		if($to > $num_rows) {
			$to = $num_rows;
		}
		$sql .= " WHERE {$cond} {$order} LIMIT $eu, $limit";
		//echo $sql;
		$this -> assign('limit',$limit);
		$this -> assign ('paging_path',$paging_path);
		$this -> assign('num_rows', $num_rows);
		$this -> assign('to', $to);
		$this -> assign('from', $eu + 1);

		$result = $this -> db -> getAll($sql);
		return $result;
	}
	
	function getListTitle ($condition = "status = 1", $limit = 10, $order = "ordering, create_date DESC")
	{
		if (MULTI_LANGUAGE)
			$condition .= " AND lang_id ={$this -> lang_id} ";
                        
                $condition .= " AND site_id ={$this -> site_id} ";
                
		$sql = "SELECT id, title, page_url, lead, avatar, count FROM {$this -> table} WHERE {$condition} ORDER BY {$order} limit {$limit}";

		$items = $this -> db -> getAll($sql);
		return $items;
	}

	function getListTitleHasCate ($condition = "status = 1", $limit = 10, $order = "a.ordering, a.create_date DESC")
	{
		if (MULTI_LANGUAGE)
			$condition .= " AND a.lang_id ={$this -> lang_id} ";

		$condition .= " AND a.site_id ={$this -> site_id} ";

		$sql = "SELECT a.id, a.title, a.sub_title, a.page_url, a.lead, a.avatar, a.count, c.name cateTitle, c.page_url cateUrl, c.lead cateLead FROM articles a, categories c WHERE {$condition} AND c.id = a.cate_id  ORDER BY {$order} limit {$limit}";

		//echo $sql;
		$items = $this -> db -> getAll($sql);
		return $items;
	}

	function getCategory($type = '') {
		if($type == '') {
			$type = $this -> type;
		}
		if($type == 'all') {
			$cond = " status=1 AND site_id ={$this -> site_id} ";
		}
		else{
			$cond = " status=1 AND type='{$type}'";
		}

		$table = 'categories';
		
		$result = $this -> multiLevel( $table,"id","parent_id","*",$cond,"ordering ASC");
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

	function getRelatedById($article_id = 0, $limit = 5) {
            if($article_id == 0) {
               $article_id = $this -> id; 
            }
            $data = array();
            if($article_id > 0 ){
                $where .= "article_id = {$article_id} AND type = 'article'";
                $sql = "SELECT id, title, sub_title, page_url from {$this->table}  where id in (SELECT object_id FROM related_links WHERE {$where}) AND status = 1 AND site_id = {$this ->site_id} limit {$limit}";
                $data = $this ->getAll($sql);
            }
            return $data;
        }
        
        function addRelated($article_id, $list_related, $type="article") {
            $this->deleteRelated($article_id);
            if(is_array($list_related) && count($list_related) > 0) {
                foreach ($list_related as $val) {
                    $aData=array(
                        'article_id' => $article_id,
                        'object_id' => $val,
                        'type' => "{$type}"
                    );
                    $this -> insert($aData, 'related_links');
                }
            }
        }
        
        function deleteRelated($article_id, $type = "article") {
            if((int)article_id > 0){
                $this ->delete("article_id = '{$article_id}' AND type = '{$type}'" , 'related_links');
            }
        }

	//function add tags
	function tagsCloud($tags ='',$type='add'){
		if($tags!= ''){
			$arr_tags = explode(",",$tags);
			foreach($arr_tags as $tag){
				$tag = trim($tag);
				if($tag != ''){
					$id = $this -> db -> getOne("SELECT id FROM tagcloud WHERE site_id = {$this -> site_id}  AND lower(name) = lower('".$tag."')");
					
					if($id){
                                            $sql = "UPDATE tagcloud SET count=count+1 WHERE site_id = {$this-> site_id} AND id = '{$id}'";
                                            $this -> db -> query($sql);
					}else{
                                                $aData=array(
                                                    'name' => $tag,
                                                    'count' => 1,
                                                    'site_id' => $this->site_id
                                                );
                                                $id = $this ->insert($aData, 'tagcloud');
					}
						
					$page_url = strtolower('/tagcloud/'.$id.'/'.$this -> removeMarks($tag). '.html');

					$sql = "UPDATE tagcloud SET page_url= '{$page_url}' WHERE site_id = {$this -> site_id}  AND id = {$id}";
					$this ->query($sql);
				}
			}
		}
	}

	function getTagCloud ($tagcloud) {
		if(!$tagcloud) {
			return array();
		}
		$arr_tags = explode(',',$tagcloud);
		$i = 0;
		foreach($arr_tags as $tag){
			$tag = trim($tag);
                        $detail = $this -> db -> getRow("SELECT name, page_url FROM tagcloud WHERE site_id = {$this-> site_id} AND page_url != '' AND lower(name) = lower('".$tag."')");
			if(count($detail) > 0) {
                            $arr_data[$i] = $detail;
                            $i++;
                        }
		}
		return $arr_data;
	}
        
        function checkLinkArticle() {
            
        }
        
        function updateNumberView($id) {
		$this -> db -> query("UPDATE articles SET count = count+1, is_cache = 1 WHERE site_id = {$this-> site_id} AND id = {$id}");
	}

	function isOOPS() {
		if($_SESSION['group_id'] == '0') {
                    return true;
		}

		return false;
	}

	 function editorItem($field = "ordering"){
        $aItem = $_GET[$field];
        if(is_array($aItem) && count( $aItem ) > 0){
            // save order for item.
            foreach( $aItem as $key => $value){
				if( !is_numeric($value)) $value = 100;
					$this -> updateById(array($field => $value ), $key);
            }
        }
        $this -> listItem();
    }

	/****************************FUNCTION SMARTY******************************/

	function loadConfig($lang_file = "") {
		if($lang_file == "")
			$lang_file = $_SESSION['lang_file'];
		if(file_exists(SITE_DIR . $this -> smarty -> config_dir[0] . $lang_file)) {
			$this -> smarty -> config_load($lang_file);
		}
	}

	function getConfigVars ($var = '', $default = '')
	{
		$value = $this -> smarty -> get_config_vars($var);
		if ($value != '') {
			return (string) $value;
		} else {
			return $default;
		}
	}
	
	function setCache($template = null, $status = 1, $id = 0, $table = null, $module = null) {
		$table = isset($table)?$table:$this -> table;
		$module = isset($module)?$module:$this -> mod;
	
		if(isset($template)) {
			$sql = "SELECT status FROM cache WHERE `table` = '{$table}' AND `module` = '{$module}' AND `template` = '{$template}' AND id = {$id}";
			$checkStatus = $this -> db -> getOne($sql);

			if(isset($checkStatus) || !empty($checkStatus) ){
				if($status != $checkStatus)
					$this -> db -> query("UPDATE cache SET status = {$status} WHERE `table` = '{$table}' AND `module` = '{$module}' AND `template` = '{$template}' AND id = {$id}");
			}else {
				$this -> db -> query("INSERT cache SET `status` = {$status},  `table` = '{$table}', `module` = '{$module}', `template` = '{$template}', id = {$id}");
			}

		}else {
			$this -> db -> query("UPDATE cache SET status = {$status} WHERE `table` = '{$table}' AND `module` = '{$module}'");
		}
	}

	function isCache($template, &$id = null) {
            return 0;
		if(!isset($template))
			return 0;

        $where = " AND p.name = '{$template}' ";
        $where .= " AND p.status = 1 ";
        $where .= " AND pc.portlet_id = p.id ";
        $where .= " AND pc.portlet_id = p.id ";
        $where .= " AND pc.type = 'portlet'";

        if($id != null){
            $where .= " AND pc.cache_id = {$id} ";
        }

        $sql = "SELECT pc.is_cache FROM portlet2cache pc, portlet p WHERE 1 {$where}";
		$is_cache = $this -> getOne($sql);

		if($this -> smarty -> isCached($template, $id) && $is_cache){
			return 1;
		}

		return 0;
	}

	function compileDirSmarty($path = "templates_c") {
		$this -> smarty -> compile_dir = $path;
	}

	function templatesDirSmarty($path) {
		$this -> smarty -> template_dir = $path;
	}

	function confiDirSmarty($path = "languages") {
		$this -> smarty -> config_dir = $path;
	}

	function assign($var, $value) {
		$this -> smarty -> assign($var, $value);
	}

	function display($template, $cache_id = null) {
		if(file_exists(SITE_DIR . $this -> smarty -> template_dir[0] . $template)) {
			$this -> smarty -> display($template, $cache_id);
		}else {
			alert(NOT_HAS_TEMPLATE . SITE_DIR . $this -> smarty -> template_dir[0] . $template);
		}
	}

    function displayString($template, $cache_id = null) {
        $template = 'string:' . $template;
        $this -> smarty -> clearCache($template);
        $this -> smarty -> display($template, $cache_id);
    }



	function clearCache($template, $cache_id = null) {
		$this -> smarty -> clearCache($template, $cache_id);
	}

    function getPortlet($template_name) {
        $sql = "SELECT content FROM portlet WHERE site_id = {$this -> site_id} AND name= '{$template_name}' AND status = 1";
        $template_string = $this -> getOne($sql);
        return $template_string;
    }

	/*********************END FUNCTION SMARTY ********************/

	function getNav($cat_id, &$table = 'categories')
	{
		$where = "status=1";

		if (MULTI_LANGUAGE)
			$where .= " AND langid = {$this->langid} ";

		$sql = "SELECT name, page_url FROM {$table} WHERE {$where} AND id={$cat_id} ORDER BY ordering";
		$row = $this -> db -> getRow($sql);

		$sql = "SELECT parent_id FROM {$table} WHERE {$where} AND id={$cat_id} ORDER BY ordering";
		$parent = $this -> db ->getOne($sql);


		if ($parent)
			$result = $this->getNav($parent).'<li><a href="'.$row['page_url'].'" class="second">'.$row['name'].'</a></li>';
		else 
			if($row['page_url']) {
				$result = '<li><a href="'.$row['page_url'].'">'.$row['name'].'</a></li>';
			}
			

		return $str.$result;
	}

	function getExtensionFile($file_name)
	{
	   return pathinfo($file_name,PATHINFO_EXTENSION);
	}
	
	function checkExtensionFileUpload($extentsion , $file_name) {
		if(is_array($extentsion)){
			if(in_array($this -> getExtensionFile($file_name),$extentsion) )
				return true;
		}else{
			if($this -> getExtensionFile($file_name) == $extentsion)
				return true;
		}
		return false;
	}

	function renameFile($file_name) {
		$file_extention = $this -> getExtensionFile($file_name);
		$file_name = $this -> removeMarks(preg_replace('/.'.$file_extention.'/i', '', $file_name));
		return $file_name.'-'.time().'.'.$file_extention;
	}
        
        function getSiteConfig() {
            global $cache;
            if (!($site_config = $cache->get('site_config', "config")))
            {
                $sql = "SELECT name, value FROM tbl_sys_config where site_id = {$this ->site_id} ";
                $site_config = $this -> db -> getAssoc($sql);
                $cache->save('site_config', $site_config, "config");
            }
            
            return $site_config;
        }
        
         public function getClass($modul)
        {
            $class_dir = "module/$modul/$modul.frontend.php";
            
            if (file_exists($class_dir))
            {
               if(class_exists( $modul) ){
                    require($class_dir);
                    $mod = new $modul($this->smarty, $this-> db);
                    return $mod;
                }else {
                    require($class_dir);
                    $mod = new $modul($this->smarty, $this-> db);
                    return $mod;
                }
            }

            return false;
        }
}
?>
