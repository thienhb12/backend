<?php
class account extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "tbl_sys_users";
		parent::__construct($smarty, $db, $datagird, $table);
		parent::getRootPath();
	}
	
	function run($task)
	{
		$str_id = parserGetUrl('arr_check');
		switch ($task)
		{
			case 'change_password':
				$this->changePassword();
				break;
			default:
					$this->buildForm($task);
				break;
			}
	}

	function changePassword()
	{
		$this -> loadConfig();
		$message = "";
		$style = 'text-danger';
		if($this->isPost())
		{
			$detail = $this -> getAccountInfo();
			$password = md5($_POST['password']);
			$newpassword = md5($_POST['newpassword']);
			$cfpassword = md5($_POST['cfpassword']);
			if($detail['password'] == $password) {
				if($newpassword == $cfpassword) {
					$data = array (
						"password" => $newpassword
					);

					$this->updateById($data, $_SESSION['userid']);
					$style = 'text-success';
					$message = $this -> getConfigVars('change_password_success');
					$this -> redirect('/index.php?mod=admin&amod=sys_login&atask=login&task=logout&sys=true');
				}else {
					$message = $this -> getConfigVars('error_confirm_password');
				}
			}else {
				$message = $this -> getConfigVars('wrong_password');
			}
		}
		$this -> assign('message', $message);
		$this -> assign('style', $style);
		$this -> display('changepass_account_admin.tpl', true);
	}

	function getPageInfo($task){
		return true;
	}

	function buildForm($task)
	{
		if($this->isPost())
		{
			$data = array (
                            "fullname" => $_POST['fullname'],
                            "email" => $_POST['email']
			);

                    $this->updateById($data, $_SESSION['userid']);

		}
		$this -> assign('detail', $this -> getAccountInfo());
		$this -> display('account_admin.tpl', true);
	}

	function getAccountInfo() {
		$sql = "SELECT * FROM {$this -> table} WHERE id = " . $_SESSION['userid'];
		return $this -> db -> getRow($sql);
	}
}
?>
