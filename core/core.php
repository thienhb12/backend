<?php

// Core functions
// Created date: 25.05.2009
	
	// Include path functions
	function user_set_include_path($path="core/PEAR"){
		global $old_include_path;
		$old_include_path= ini_get("include_path");
		ini_set("include_path", SITE_DIR."$path");
	}

	function user_rollback_include_path(){
		global $old_include_path;
		ini_set("include_path", $old_include_path);
	}
	// End include path functions

    include_once("core/device.php");
	$detect = new Mobile_Detect;
	$device_check = 1;
	if ($detect->isMobile()) {
    	$device_check = 3;
	}
	if($detect->isTablet()){
	    $device_check = 2;
	}

    define("CHECK_DEVICE", $device_check);    
	// Include PEAR DB Library
	// Create global variable
	global $db, $module, $smarty, $site_id, $site_config;
	if(!is_object($db)){
		user_set_include_path("core/PEAR");
			
		$db= DB_NAME;
		$user= DB_USER;
		$pass= DB_PASSWORD;
		
		include_once('DB.php');  
		$db =DB::connect("mysql://$user:$pass@localhost/$db");
		$db->query("set names 'utf8'");
		$db->setFetchMode(DB_FETCHMODE_ASSOC);
		user_rollback_include_path();
	}
	// End include PEAR DB Library

	// Include Smarty template engine
	if(!is_object($smarty))
	{
		user_set_include_path("core/Smarty/libs");
		
		include_once("Smarty.class.php");
		$smarty = new Smarty;

		include_once("SmartyBC.class.php");
		$smarty = new SmartyBC();

		
		if(!isset($_GET['mod']))
			$module = "home";
		else $module = $_GET['mod'];


		$smarty -> template_dir= "module/$module/templates";
		$smarty -> config_dir= "languages";
		$smarty -> compile_dir= "templates_c";
		$smarty -> debugging = false;
		$smarty->cache_dir = "cache";

		#$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
		user_rollback_include_path();
	}

	// End include Smarty template engine
	
	// Load module function
	/*
	*	$modul: Module name
	*	$task: Task
	*/
	function loadModule($modul, $task = null, $other= array()) {
		global $db, $smarty, $module, $site_id;

		if($task=="" && isset($_REQUEST['task']))
			$task= $_REQUEST['task'];

		$template_dir = "module/$modul/templates";
		$class_dir = "module/$modul/$modul.frontend.php";
		
		if(is_dir($template_dir)){
			$smarty -> template_dir = $template_dir;
		}
		else
			die("ERROR: NOT FOUND DIRECTORY: {$site_template_dir}");
		if(file_exists($class_dir)) {
			include_once($class_dir);
			$mod = new $modul($smarty, $db);
			$mod -> run($task, $other);
		}
		else
			die("ERROR: FILE NOT FOUND: $class_dir");
		
	}
	// End load module function
	
	// Get page info functions
	function getPageinfo(){
		global $db, $smarty;
		$modul= $_GET['mod'];
		if($modul=="")
		{
			$aPageinfo= array(
				"title" => PAGE_TITLE,
				"description" => PAGE_DESCRIPTION,
				"keyword" => PAGE_KEYWORD
			);
			$smarty->assign("aPageinfo", $aPageinfo);
		}
		else
		{
			if(file_exists("module/$modul/$modul.frontend.php")) {
				include_once("module/$modul/$modul.frontend.php");
				$mod = new $modul($smarty, $db);
				if($task == "")
					$task= $_GET['task'];
				$mod->getPageinfo($task);
			}	
		}
	}
	
	function pre ($value)
	{
		echo "<pre>";
		if(is_array($value))
			print_r($value);
		else 
			echo $value;
		echo "</pre>";
	}

	function stripTagByTagName($html, $tag = "", $pattern)
	{
		$replace = "/(<$tag.*?$pattern.*?>.+?)+(<\/$tag.*?>)/i";
		$html = preg_replace($replace,'',$html);

		return $html;
	}

	function parserGetUrl($param) {
		if($_GET[$param]) {
			 return implode(',',$_GET[$param]);
		}
	}

	function checkError($isCheck) {
		if ($isCheck)
		{
			error_reporting(E_ALL);
			ini_set("display_errors", 1);
		}
	}

	function addArray($a, $b)
	{
		foreach ($b as $key => $val)
		{
			$a[$key] = $val;
		}
		return $a;
	}

	function alert($msg, $style = "danger") {
		echo '<div class="alert alert-' . $style . '" role="alert">' . $msg . '</div>';
	}

	function removeMarks($string)
	{
	 $trans = array ("'" => '', '"' => '','é' => 'e','è' => 'e', '‘' => '', '’' => '', '“' => '', '”' => '', 'ẻ' => 'e', 'ẽ' => 'e', 'ẳ' => 'a', 'ắ' => 'a', 'ắ' => 'a','ẵ' => 'a', 'ọ' => 'o', 'ẽ' => 'e', 'ờ' => 'o', 'ẹ' => 'e', 'ặ' => 'a', 'ề' => 'e', 'ặ' => 'a','ằ' => 'a', 'à' => 'a', 'á' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a', 'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẫ' => 'a', 'ẩ' => 'a', 'ậ' => 'a', 'ú' => 'a', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u', 'à' => 'a', 'á' => 'a', 'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o', 'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o', 'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e', 'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i', 'ơ' => 'o', 'ớ' => 'o', 'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o', 'ư' => 'u', 'ừ' => 'u', 'ứ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u', 'đ' => 'd', 'À' => 'A', 'Á' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A', 'Â' => 'A', 'Ấ' => 'A', 'À' => 'A', 'Ẫ' => 'A', 'Ẩ' => 'A', 'Ậ' => 'A', 'Ú' => 'U', 'Ù' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U', 'Ô' => 'O', 'Ố' => 'O', 'Ồ' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O', 'Ê' => 'E', 'Ế' => 'E', 'Ề' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E', 'Í' => 'I', 'Ì' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I', 'Ơ' => 'O', 'Ớ' => 'O', 'Ờ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O', 'Ư' => 'U', 'Ừ' => 'U', 'Ứ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U', 'Đ' => 'D', 'Ý' => 'Y', 'Ỳ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y', 'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a', 'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a', 'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a', 'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u', 'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u', 'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i', 'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o', 'ô' => 'o', 'ố' => 'o', 'ồ' => 'ô', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o', 'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o', 'đ' => 'd', 'Đ' => 'D', 'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y', 'Á' => 'A', 'À' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A', 'Ă' => 'A', 'Ắ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ặ' => 'A', 'Â' => 'A', 'Ấ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ậ' => 'A', 'É' => 'E', 'È' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E', 'Ế' => 'E', 'Ề' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E', 'Ú' => 'U', 'Ù' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U', 'Ư' => 'U', 'Ứ' => 'U', 'Ừ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U', 'Í' => 'I', 'Ì' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I', 'Ó' => 'O', 'Ò' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O', 'Ô' => 'O', 'Ố' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O', 'Ơ' => 'O', 'Ớ' => 'O', 'Ờ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O', 'Ý' => 'Y', 'Ỳ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y', '?'=>'', '"'=>'', '('=>'', ')'=>'', '['=>'', ']'=>'', ' - ' => '-', ' ' => '-', '/' => '');
		return strtr( trim($string), $trans );
	}
?>