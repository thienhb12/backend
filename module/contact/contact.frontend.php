<?php
class contact extends Anpro_Module_Base 
{
	public function __construct($smarty,$db)
	{
		$table = "contact";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> img_path = "/upload/contact/";
	}

	function run($task = '')
	{
		if($task) $task = $_GET['task'];
		switch($task) {
			case 'send_info':
				$this -> sendInfo();
				break;
			default:
				$this -> sendContact();
		}
	}

	function sendContact()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if ($_POST['captcha']==$_SESSION['key_captcha']) {
				$data = array(
					"fullname" => $_POST['fullname'],
					"address" => $_POST['Address'],
					"phone" => $_POST['phone'],
					"fax" => $_POST['fax'],
					"email" => $_POST['email'],
					"subject" => $_POST['subject'],
					"content" => $_POST['content'],
					"create_date" => date("Y-m-d"),
					"status" => "0"
				);
				$res = $this -> db -> autoExecute("contact",$data,DB_AUTOQUERY_INSERT);
				if($res) {
					$msg = "Thư của bạn đã được gửi tới chúng tôi";
				}
			}
			else 
				$msg = "Mã xác nhận sai!";
	
			$this -> assign('msg',$msg);
		}
		
		$this -> display('contact.tpl');
	}

	function sendInfo()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if ($_POST['captcha']==$_SESSION['key_captcha'])
			{
				$data = array(
					"fullname" => $_POST['fullname'],
					"address" => $_POST['address'],
					"phone" => $_POST['phone'],
					"fax" => $_POST['fax'],
					"email" => $_POST['email'],
					"subject" => $_POST['subject'],
					"content" => $_POST['content'],
					"create_date" => date("Y-m-d"),
					"status" => "0",
					"type" => "sendinfo"
				);

				$data['file1'] = $this -> uploadImage('file1');
				$data['file2'] = $this -> uploadImage('file2');
				$data['file3'] = $this -> uploadImage('file3');

				$res = $this -> db -> autoExecute("contact",$data,DB_AUTOQUERY_INSERT);
				if($res) {
					$msg = "Thông tin của bạn đã được gửi tới chúng tôi";
				}
			}
			else 
				$msg = "Mã xác nhận sai!";
	
			$this -> smarty -> assign('msg',$msg);
		}
		
		$this -> smarty -> display('sendInfo.html');
	}

	function uploadImage($name) {
		if($_FILES[$name]['name']!='')
		{
			$Filename = rand().'_'.$_FILES[$name]['name'];
			parent::crop_image($_FILES[$name]['tmp_name'],$Filename,SITE_DIR.$this -> img_path,600, 800);
			return $this -> img_path.$Filename;
		}
		return "";
	}

	function getPageinfo($task= "")
	{
		switch ($task)
		{
			default:
				$aPageinfo=array('title'=>PAGE_TITLE, 'keyword'=>PAGE_KEYWORD
						, 'description'=>PAGE_DESCRIPTION);
				break;
		}
		$this -> smarty->assign('aPageinfo', $aPageinfo);
		
	}
}
?>
