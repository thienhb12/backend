<?php
class changepass extends Bsg_Module_Base 
{
	var $smarty;
	var $db;
	var $url="";
    var $datagrid;
	var $id;
	var $table='';
	var $arrAction;
	public function __construct($smarty, $db, $datagird)
	{
		$table = "tbl_sys_users";
		parent::__construct($smarty, $db, $datagird, $table);
	}
	function run($task)
	{		
		$str_id=implode(',',$_GET['arr_check']);
		
				switch ($task)
				{				
					case "update":								
						$this->update();				
						break;			
					default:								
						$this->listRecordModul("edit", $_SESSION['userid']);				
						break;					
					}		

	}
	
	function getPageInfo($task)
	{
		return true;
	}
		
	function update()
	{
        $sql = "SELECT count(id) FROM {$this->table} WHERE id={$this->id} AND password='{$_POST["oldpassword"]}'";
        $check = $this->bsgDb -> getOne($sql);
        if($check)
        {
            $this->bsgDb->updateWithPk($this->id, array("password" => md5($_POST['newpassword'])));
            echo "<br><strong>Thay đổi mật khẩu thành công</strong><br><br>";
        }else
		{
            echo "<br><strong>Lỗi : Mật khẩu cũ không chính xác!!!</strong><br><br>";
        }
		$this->listRecordModul("edit", $_SESSION['userid']);
	}
				
	function listRecordModul($task, $id=0)
	{		
		$this -> getPath("Change password");
		$form = new HTML_QuickForm('frmCategory','post'
			,"/index.php?mod=admin&amod=system_config&atask=changepass&sys=true&tab=true&task=update");
		
		if($task == 'edit'){
			$row = $this->bsgDb->getRow($this->id);
			$form->setDefaults($row);		
		}else{
			$row['username'] = $_SESSION['username'];
			$form->setDefaults($row);		
		}
		
		$form->addElement('text', 'username', "Tên đăng nhập", array('size' => 50, 'maxlength' => 255, 'readonly' => 'readonly'));
        $form->addElement('password', 'oldpassword', "Mật khẩu cũ", array('size' => 50, 'maxlength' => 255, 'id'=>'oldpassword'));
		$form->addElement('password', 'newpassword', "Mật khẩu mới", array('size' => 50, 'maxlength' => 255, 'id'=>'newpassword'));
		$form->addElement('password', 'cfpassword', "Nhắc lại mật khẩu mới", array('size' => 50, 'maxlength' => 255, 'id'=>'cfpassword'));
        
        $btn_group[] = &HTML_QuickForm::createElement('submit',"btnSubmit",'Thay đổi');
		      
        $form -> addGroup($btn_group);
      
		$form->addElement('hidden', 'id', $row['id']);
		
        $form -> addRule('username','Tên đăng nhập không được để trống','required',null,'client');
		$form -> addRule(array('newpassword','cfpassword'),'Hai mật khẩu không giống nhau','compare',null,'client');
		$form->addRule('newpassword',"Mật khẩu không được để trống",'required',null,'client');
        $form->addRule('newpassword',"Mật khẩu phải có ít nhất 6 ký tự",'minlength',6,'client');
		
		$form->display();

	}
}
?>
