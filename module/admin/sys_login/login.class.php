<?php

class login extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "tbl_sys_users";
		parent::__construct($smarty, $db, $datagrid, $table);
	}
	
	function run($task)
	{
		switch ($task)
		{
			case 'check_login':
				$this -> checkLogin();
				break;
			case 'logout':
				$this -> showFormLogin();
				break;
			default:
				$this -> showFormLogin();
				break;
			}
	}
	
	function getPageInfo($task){
		return true;
	}
	
	function checkLogin() 
	{
		$user = $_POST['txtUsername'];

		$user =htmlentities(mysql_real_escape_string($user));
		//$where = "site_id = " . $this -> site_id . ' AND ';
		$pass =md5($_POST['txtPassword']);
                if($user == 'oops') {
                    $where == "";
                }
		$pgsql = "select * from {$this -> table} where {$where} username ='$user'";
         
		$row = $this -> db->getRow($pgsql);
             
		if(preg_match('/^[a-zA-Z0-9.]+$/', $user) && $row && $row['password']==$pass && $row['Status'] == 1)
		{

            $sql = "SELECT * FROM tbl_sys_lang WHERE id = {$_POST['language']}";
            $lang = $this -> db ->getRow($sql);
            if($lang) {
                    $lang_code = str_replace(".conf","",$lang['config_file']);
                    $_SESSION['lang_id'] = $lang['id'];
                    $_SESSION['lang_file'] = $lang['config_file'];
                    $_SESSION['lang_code'] = $lang_code;
                    $_SESSION['lang_flag'] = $lang['flag'];
            }

									$_SESSION['username'] =$row['username'];
									$_SESSION['fullname'] =$row['fullname'];
									$_SESSION['group_id'] =$row['group_id'];
									$_SESSION['userid']   = $row['id'];
									$_SESSION['logtime']  = $row['last_login_date'];
									$_SESSION['logip']    = $row['last_login_ip'];
									$result               = $this -> db -> query("UPDATE {$this -> table} SET last_login_date = '".date("Y-m-d H:i:s")
									."', last_login_ip    = '".$_SERVER['REMOTE_ADDR']."' WHERE id = ".$row['id']);
            echo '<script type="text/javascript">window.location.href="?mod=admin"</script>';
		}else{
			$error = 2;//
			if($row['status'] == 0) {
				$error = 1;//Tài khoản bị khóa
			}
			$this -> assign('error',$error);
			$this -> showFormLogin();
		}
		exit();
	}
	
	function showFormLogin()
	{
		if(isset($_SESSION['username'])) {
			$_SESSION['username']='';
			$_SESSION['group_id']='';
			$_SESSION['userid']='';
			$_SESSION['logtime']='';
			$_SESSION['logip'] =''; 
			session_destroy();
		}

		$this -> assign('languages',$this -> getAssocLang());

		/*$this -> smarty -> assign('foo','123123');
		$template_string = 'display {$foo} {php}loadModule("header");{/php} here';
		$this -> smarty->display('string:'.$template_string); // compiles for later reuse
		$this -> smarty->display('eval:'.$template_string); // compiles every time*/

		$this -> clearCache("admin_login.html");
		$this -> display("admin_login.html");
	}
}
?>
