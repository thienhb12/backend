<?php
class users extends Anpro_Module_Base 
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
			case 'delete':
				$this->aDb->deleteWithPk($this->id);
				parent::redirect($_COOKIE['re_dir']); 
				break;
			case 'delete_all':
				$this->aDb->deleteWithPk($str_id);
				parent::redirect($_COOKIE['re_dir']); 
				break;
			case 'unpublic_all':
				$this->aDb->updateWithPk($str_id,  array("Status"=>"0"));
				parent::redirect($_COOKIE['re_dir']);
				break;
			case 'public_all':
				$this->aDb->updateWithPk($str_id,  array("Status"=>"1"));
				parent::redirect($_COOKIE['re_dir']);
				break;
			case 'list':
				$this->list();
				break;
			case 'add':
			case 'edit':
				$this->insertUpdate($task);
				break;
			case 'change_pass':
				$this->changePass();
				break;
			case 'reset_password':
				$this -> resetPassword();
				break;
			default:
					$this->listRecordContentGuild($_GET["msg"]);
				break;
			}
	}

	function changePass()
	{
		$this -> display('changepass_user_admin.tpl');
	}

	function resetPassword()
	{
		$this -> loadConfig();
		$message = "Đối tượng không tồn tại.";
		$style = 'text-danger';
		$message = $this -> getConfigVars('error_confirm_password');
		if($this->isPost() && $this -> isNumber($_GET['id']))
		{
			$newpassword = md5($_POST['newpassword']);
			$cfpassword = md5($_POST['cfpassword']);
			if($newpassword == $cfpassword) {
				$data = array (
					"password" => $newpassword
				);
				$id = $this -> aDb -> updateWithPk($_GET['id'], $data);
				$style = 'text-success';
				$message = $this -> getConfigVars('change_password_success');
			}else {
				$message = $this -> getConfigVars('error_confirm_password');
			}
		}

		$this -> assign('message', $message);
		$this -> assign('style', $style);
		$this -> display('reset_password_user_admin.tpl');
	}

	function getPageInfo($task){
		return true;
	}

	function insertUpdate($task)
	{
		if($this->isPost())
		{
			$data = array (
				"username" => $_POST['username'],
                                "group_id" => $_POST['group'],
                                "fullName" => $_POST['fullname'],
                                "email" => $_POST['email'],
                                "status" => $_POST['status'],
                                site_id => $this-> site_id
			);

			//Prosess Insert Update
			if($task=='add'){
                                $data["password"] = md5($_POST['password']);
				$this->id =$this->insert($data);
			}
			elseif($task=='edit')
				$this->updateById($data);

			parent::redirect($_COOKIE['re_dir']);
			//End Prosess Insert Update
		}
		
		$this->makeFormContentGuild($task);
	}
	
	function listRecordContentGuild($sMsg='')
	{
		$arr_cols= array(
			array(
				"field" => "id",
				"datatype" => "text",
				"primary_key" => true,
				"visible" => "hidden"
			),
			array(
				"field" => "username",
				"display" => $this->getConfigVars('accounts'),
				"datatype" => "text",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "last_login_date",
				"display" => "Thời điểm đăng nhập gần nhất",
				"datatype" => "text"
			),
			array(
				"field" => "last_login_ip",
				"display" => "Đăng nhập ở địa chỉ IP",
				"datatype" => "text"
			),
			array(
				"field" => "status",
				"display" => $this->getConfigVars('status'),
				"datatype" => "boolean",
				"width" 		=> "100",
			)
		);
		// 
		$arr_filter= array(	
			array(
				"field" 	=> "username",
				"display" 	=> 'Tên',
				"type" 		=> "text",
				"style"		=> "width:200px",
				"selected" 	=> isset($_REQUEST["username"])?$_REQUEST["username"]:"",
				"filterable"=>true
			)
		);
		$arr_check = array(
			array(
			'task' => 'delete_all',
			'confirm'	=> 'Are you sure?',
			'display' => 'Xóa'
			),
			array(
				'task' 	=> 'public_all',
				'confirm'	=> 'Are you sure?',
				'display' 	=> 'Kích hoạt',
			),
			array(
				'task' 	=> 'unpublic_all',
				'confirm'	=> 'Are you sure?',
				'display' 	=> 'Vô hiệu',
			)
		);
		if( $sMsg )
			$this->datagrid -> setMessage( $sMsg );
                
		$table = "(SELECT * FROM {$this->table} WHERE id != 1 AND site_id = {$this -> site_id}) AS {$this->table}";
		$arr_action = parent::getAct();
		$action_in = array(
                    "task" => 'reset_password',
                    "text" => $this -> getConfigVars('reset_password'),
                    "icon" => "password.png"
		);

		//pre($arr_action);
        
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, "?".$_SERVER['QUERY_STRING'], $arr_action, null, $root_path, false,$arr_check);

	}

	function getGroups()
	{
            $sTbl = "tbl_sys_groups";
            $query = "SELECT id, CONCAT('&nbsp;&nbsp;&nbsp;',name) FROM {$sTbl} WHERE site_id = {$this -> site_id}";
            $result = $this -> db -> getAssoc( $query );
            
            return $result;
        }

	function makeFormContentGuild ($task, $id=0)
	{
		if($task == 'edit'){
			$detail = $this-> getById();
			$this -> assign('detail', $detail);
		}

		$this -> assign('nhomquyen', $this->getGroups());
		$this -> display('creater_user_admin.tpl');
	}
    
    function getRoll()
    {
        global $smarty,$db;
        $module = $db->getAll("SELECT id, Name FROM tbl_sys_menu WHERE ParentID = 0 ORDER BY Zindex");
        foreach ($module as $key=>$value)
        {
            $sub_module = $db->getAll("SELECT id, Name FROM tbl_sys_menu WHERE Editable = 1 AND ParentID = {$value["id"]}");
            if ($sub_module)
            {
                $module[$key]["sub_module"] = $sub_module;
                $check_full_roll = TRUE;
                foreach ($sub_module as $k=>$val)
                {
                    $sql = "SELECT RollID FROM tbl_sys_users_module WHERE UserID='{$this->id}' AND ModuleID='{$val["id"]}'";
                    $current_roll = $db->getCol($sql);
                    $full_roll = $db->getCol("SELECT roll_id FROM tbl_sys_module_roll WHERE module_id = '{$val["id"]}'");
                    $result = array_diff($full_roll, $current_roll);
                    if (empty($result))
                        $module[$key]["sub_module"][$k]["is_full_roll"] = TRUE;
                    else 
                        $check_full_roll = FALSE;    
                    $module[$key]["sub_module"][$k]["current_roll"] = $current_roll;
                    $full_id_roll = implode(",",$full_roll);
                    $display_full_roll = $db->getAll("SELECT id, name FROM tbl_sys_roll WHERE id IN ($full_id_roll) ORDER BY ordered");
                    $module[$key]["sub_module"][$k]["full_roll"] = $display_full_roll;
                }
                $module[$key]["is_full_roll"] = $check_full_roll;
            }
            else 
            {
                $sql = "SELECT RollID FROM tbl_sys_users_module WHERE UserID='{$this->id}' AND ModuleID='{$value["id"]}'";
                $current_roll = $db->getCol($sql);
                $full_roll = $db->getCol("SELECT roll_id FROM tbl_sys_module_roll WHERE module_id = '{$value["id"]}'");
                $result = array_diff($full_roll, $current_roll);
                if (empty($result))
                    $module[$key]["is_full_roll"] = TRUE;
                $module[$key]["current_roll"] = $current_roll;
                $full_id_roll = implode(",",$full_roll);
                $display_full_roll = $db->getAll("SELECT id, name FROM tbl_sys_roll WHERE id IN ($full_id_roll) ORDER BY ordered");
                $module[$key]["full_roll"] = $display_full_roll;
            }
        }
        $smarty->assign("module",$module);
    }

    function splitRoll($str)
    {
        return $arr = explode("-",$str);
    }
}
?>
