<?php
class admin extends Anpro_Module_Base  {
	private $datagird;
	public function __construct($smarty,$db)
	{
		$table = "articles";
                include_once("core/pear_quickform.php");
		include_once("core/datagrid/datagrid.php");
		$this -> datagird= new datagrid();
		parent::__construct($smarty ,$db ,$this -> datagird, $table);
	}

	function run($amod="")
	{
		// Task proccess...
		$atask = $_GET["atask"];
		$task = $_GET["task"];
		if($_SESSION['userid'] == ''){
			$amod = "sys_login";
			$atask = "login";
			$task = ($_GET["task"] == '')?"login":$_GET["task"];
			$_GET['sys'] = true;
		}
		else {
			$amod = $_GET["amod"]?$_GET['amod']:"welcome";
			if (!$_GET['amod']) { 
				$_GET['sys'] = 1;
				$atask = 'welcome';
			}
		}
		$list_modul_of_user = $this -> modulePermission();

		if(!isset($_GET['ajax']) && $amod != 'sys_login') {
			$this -> clearCache('header_admin.tpl');
			$this -> display('header_admin.tpl');
			$this -> loadMenu($list_modul_of_user);
		}
		// Load module: Get main content
		if($_GET['sys'])
		{
			$module_id = $this -> getOne("SELECT id FROM tbl_sys_menu WHERE link = 'amod=$amod&atask=$atask&sys=true'");
			if ($_SESSION['userid'] != 1 && (is_array($list_modul_of_user) &&  !in_array($module_id,$list_modul_of_user)) && $amod!='sys_login' && $amod!='welcome' && $atask != "account" && $atask != "images")
			{
				alert(ERROR_PERMISSION_MODULE);
			}
			elseif(!$this->checkPermissionTask() && $atask != "account" && $atask != "images")
			{
				alert(ERROR_PERMISSION_TASK);
			}
			elseif(file_exists(SITE_DIR."module/admin/".$amod."/".$atask.".class.php"))
			{
				include(SITE_DIR."module/admin/".$amod."/".$atask.".class.php");
				if (class_exists($atask)){
					$obj = new $atask($this->smarty, $this->db, $this->datagird);
					$obj ->run($task);
				}else{
					echo "ERROR: CLASS NOT FOUND (".$atask.") IN FILE (".$amod."/".$atask.".class.php)";
				}
			}else{
				echo "ERROR: File not found: ".SITE_DIR."module/admin/".$amod."/".$atask.".class.php";
			}
		}
		else
		{
                        $link  = "amod=$amod&atask=$atask";
			$arr_task = array('add');
                        //echo $task;
			if($task != '' && in_array($task, $arr_task)) {
				$link  .= "&task={$task}";
			}
                        
                        //echo "SELECT id FROM tbl_sys_menu WHERE link = '{$link}'";
			
			$module_id = $this -> db -> getOne("SELECT id FROM tbl_sys_menu WHERE link = '{$link}'");
                        
			if ($_SESSION['userid']!=1 && !in_array($module_id,$list_modul_of_user) && $amod!='welcome' && $amod != "images")
			{
				alert(ERROR_PERMISSION_MODULE);
			}
			elseif(!$this->checkPermissionTask())
			{
				alert(ERROR_PERMISSION_TASK);
			}
			elseif(file_exists(SITE_DIR."module/".$amod."/".$atask.".backend.php"))
                        {
                            include(SITE_DIR."module/".$amod."/".$atask.".backend.php");

                            $amod_backend = $atask."BackEnd";
                            if (class_exists($amod_backend)){
                                    $amod_backend = new $amod_backend($this->smarty, $this->db, $this->datagird);
                                    $this -> smarty -> template_dir= "module/".$amod."/templates";
                                    $amod_backend ->run($task);
                            }else{

                                    alert("ERROR: CLASS NOT FOUND (".$amod_backend.") IN FILE (".$amod."/".$atask.".backend.php)");
                            }
			}else{
				alert("ERROR: File not found: ".SITE_DIR."modules/".$amod."/".$atask.".backend.php");
			}
		}

		if(!isset($_GET['ajax']) && $amod != 'sys_login') {
			$this -> templatesDirSmarty("module/admin/templates");
			$this -> clearCache('footer_admin.tpl');
			$this -> display('footer_admin.tpl');
		}
	}

    
    function checkPermissionTask()
    {
		$task = $_GET['task'];
        if($_GET["amod"] == "sys_login") return true;
        $link = "amod={$_GET['amod']}";
        if( $_GET['atask'] ) $link.= "&atask={$_GET['atask']}";
        if ($_GET['sys']) $link.="&sys={$_GET['sys']}";

        if( $_SESSION['userid'] == 1 || $_GET["amod"] != 'images') {
            return  true;
        }

        if(isset($_GET["task"]) && $_GET["task"] != "" && !isset($_GET['ajax']))
        {
			$sql = "SELECT id FROM tbl_sys_roll WHERE task='".$_GET["task"]."'";
			$rollId = $this -> getOne($sql);

			$sql = "SELECT roll_id FROM tbl_sys_groups_module WHERE module_id in (SELECT id FROM tbl_sys_menu WHERE link like '%$link%') AND group_id={$_SESSION['group_id']}";
			$list_roll_of_user = $this -> getCol($sql);

			if (is_array($list_roll_of_user) && in_array($rollId,$list_roll_of_user)){
				return true;
			}else{
				return false;
			}
        }

        return true;
    }
	
	function loadMenu($list_modul_of_user)
	{
		$modul=$_GET['amod'];
		$atask = $_GET['atask'];
		$task = $_GET['task'];
		$link = "amod={$_GET['amod']}";
                if( $_GET['atask'] ) $link.= "&atask={$_GET['atask']}";
		if( $_GET['task'] ) $link.= "&task={$_GET['task']}";
                if ($_GET['sys']) $link.="&sys={$_GET['sys']}";

		
		$modules = $this -> getAll("SELECT * FROM tbl_sys_menu WHERE parent_id = 0 AND status=1 ORDER BY zindex ASC");

		$str_per = "";
		//get parentId of permission
		if(is_array($list_modul_of_user))
			$str_per = implode(",",$list_modul_of_user);
		$sql = "SELECT parent_id FROM tbl_sys_menu WHERE status = 1";
		if($str_per != '') {
			$sql .= " AND id IN ($str_per)";
		}
		$sql .= " ORDER BY zindex ASC";
		$arr_parent = $this -> getCol($sql);

		//end
		if ($modul) {
			$module_id = $this -> getOne("SELECT id FROM tbl_sys_menu WHERE link LIKE '%amod=$modul%' AND parent_id = 0");
		}

		$menuItems = array();
		
		foreach($modules as $mod)
		{
			if((is_array($list_modul_of_user) && in_array($mod['id'],$list_modul_of_user)) || (is_array($list_modul_of_user) && in_array($mod['id'],$arr_parent))){
				$menuItems[] = $mod;
			}
		}

		if($_SESSION['userid']== '1') {
			$menuItems = $modules;
		}

		if(count($menuItems)) {
			for($i = 0; $i < count($menuItems); $i++)
			{
				$sql = "SELECT * FROM tbl_sys_menu WHERE parent_id = ".$menuItems[$i]['id']." AND status=1";

				if($str_per != '') {
					$sql .= " AND id IN ($str_per)";
				}
				$sql .= " ORDER BY zindex ASC";
				$subs = $this -> db -> getAll($sql);

				for($j = 0; $j < count($subs); $j++) {
					$subs[$j]['selected'] = '';
					if($link == $subs[$j]['link'] )
						$subs[$j]['selected'] = 'active';
				}
				$menuItems[$i]['submenu'] = $subs;
				$menuItems[$i]['selected'] = '';
				if ($module_id == $menuItems[$i]['id'])
					$menuItems[$i]['selected'] = 'active';
					$menuItems[$i]['style'] = 'block';
			}
		}
		$this -> assign('menuItems', $menuItems);
		$this -> clearCache('menu.tpl');
		$this -> display('menu.tpl');
	}
}
?>