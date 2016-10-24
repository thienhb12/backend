<?php
class groups extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "tbl_sys_groups";
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
				$this->db->query("DELETE FROM tbl_sys_groups_module WHERE group_id = '".$this->id."'");
				parent::redirect($_COOKIE['re_dir']); 
				break;
			case 'list':
				$this->list();
				break;
			case 'add':
			case 'edit':
				$this->insertUpdate($task);
				break;
			default:
					$this->listRecordContentGuild();
				break;
			}
	}
	
	function deleteImage($id, $field, $path){
		if($id == '')
			return;
		$imgpath = $path.$this->db->getOne("SELECT $field FROM ".$this->table." WHERE id = $id");
		if(is_file($imgpath))
			@unlink($imgpath);
	}
	
	function getPageInfo($task){
		return true;
	}
		
	function insertUpdate($task)
	{
		if($this->isPost())
		{
			$data = array (
				"name" => $_POST['name'],
				"summarise" => $_POST['summarise'],
				"status" => $_POST['status'],
                                "site_id" => $this -> site_id,
				"create_date" => $_POST['CreateDate']
			);
			//Prosess Insert Update
			if($task=='add'){
				$this->id = $this ->insert($data);
			}elseif($task=='edit'){
				$test = $this->updateById($data);
			}
			
			// Delete tbl_sys_users_module info 
			$this->db->query("DELETE FROM tbl_sys_groups_module WHERE group_id = '".$this->id."'");
			
			foreach($_POST['roll'] as $key=>$value){
                            $arr_roll = $this->splitRoll($value);
                            $data = array(
                                "group_id" => $this->id,
                                "module_id" => $arr_roll[0],
                                "roll_id"    => $arr_roll[1],
                            );
				$id = $this -> insert($data, 'tbl_sys_groups_module');
                        }
			//parent::redirect($_COOKIE['re_dir']);
			//End Prosess Insert Update
		}
		
		$this->makeFormContentGuild($task);
	}
	
	function listRecordContentGuild()
	{
		
		$arr_cols= array(
			array(
				"field" => "id",
				"datatype" => "text",
				"primary_key" => true,
				"visible" => "hidden",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "name",
				"display" => "Tên nhóm",
				"datatype" => "text",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "create_date",
				"display" => "Ngày tạo",
				"datatype" => "date",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "status",
				"display" => 'Trạng thái',
				"datatype" => "boolean",
				"sortable" => true,
				"width" => "100",
			)
		);
		// 
		$arr_filter= array(	
			array(
				"field" 	=> "name",
				"display" 	=> 'Tên nhóm',
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
		$arr_action = parent::getAct();
                $table = "(SELECT * FROM {$this->table} WHERE site_id = {$this -> site_id}) AS {$this->table}";
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, "?".$_SERVER['QUERY_STRING'], $arr_action, null, $root_path, false,$arr_check);

	}
	
	function makeFormContentGuild ($task, $id=0)
	{
		$form = new HTML_QuickForm('frmGuild','post',$_COOKIE['re_dir']."&task=".$_GET['task']);
		if($task == 'edit'){
			$row = $this->getById();
			$form->setDefaults($row);
		}
		
		$form->addElement('text', 'name', 'Tên nhóm', array('size' => 50, 'maxlength' => 255));
		 $form->addElement('textarea','summarise','Mô tả vắn tắt',array('style'=>'width:600px; height:100px;'));
		
		$assign_roll = $this->getRoll();
		$form -> addElement('static',NULL,'Phân quyền',$assign_roll);
		$date_time = parent::createCalendar('CreateDate',($data['CreateDate']) ? ($data['CreateDate']) : date("Y-m-d h:i"));
		$form -> addElement('static',NULL,'Ngày tạo',$date_time);
		$cbActive = array("1"=>"Kích hoạt","0"=>"Vô hiệu");
		$form -> addElement("select","status",'Trạng thái',$cbActive,array("style" => "width:100px;"));
		
		$btn_group[] = &HTML_QuickForm::createElement('submit',null,'Hoàn tất');
		
		$btn_group[] = &HTML_QuickForm::createElement('button',null,'Quay lại',array('onclick'=>'window.location.href = \''.$_COOKIE['re_dir'].'\''));
		
		$form -> addGroup($btn_group);
	  
		$form->addElement('hidden', 'id', $row['id']);

		$form->display();
	}
    
    function getRoll()
    {
        $module = $this->getAll("SELECT id, name FROM tbl_sys_menu WHERE is_sys = 0 AND status = 1 AND parent_id = 0 ORDER BY zindex");
        
        if(count($module) > 0) {
            foreach ($module as $key=>$value)
            {
                $sub_module = $this->getAll("SELECT id, name FROM tbl_sys_menu WHERE is_sys = 0  AND status = 1 AND editable = 1 AND parent_id = {$value["id"]}");
                if ($sub_module)
                {
                    $module[$key]["sub_module"] = $sub_module;
                    $check_full_roll = true;
                    foreach ($sub_module as $k=>$val)
                    {
                        $sql = "SELECT roll_id FROM tbl_sys_groups_module WHERE group_id='{$this->id}' AND module_id='{$val["id"]}'";
                        $current_roll = $this->db->getCol($sql);
                        $full_roll = $this->db->getCol("SELECT roll_id FROM tbl_sys_module_roll WHERE module_id = '{$val["id"]}'");
                        $result = array_diff($full_roll, $current_roll);
                        if (empty($result))
                            $module[$key]["sub_module"][$k]["is_full_roll"] = true;
                        else 
                            $check_full_roll = false;    
                        $module[$key]["sub_module"][$k]["current_roll"] = $current_roll;
                        $full_id_roll = implode(",",$full_roll);
                        $display_full_roll = $this->db->getAll("SELECT id, name FROM tbl_sys_roll WHERE id IN ($full_id_roll) ORDER BY ordered");

                        $module[$key]["sub_module"][$k]["full_roll"] = $display_full_roll;
                    }
                    $module[$key]["is_full_roll"] = $check_full_roll;
                }
                else 
                {
                    $sql = "SELECT roll_id FROM tbl_sys_groups_module WHERE group_id='{$this->id}' AND module_id='{$value["id"]}'";
                    $current_roll = $this->db->getCol($sql);
                    $full_roll = $this->db->getCol("SELECT roll_id FROM tbl_sys_module_roll WHERE module_id = '{$value["id"]}'");
                    $result = array_diff($full_roll, $current_roll);
                    if (empty($result))
                        $module[$key]["is_full_roll"] = TRUE;
                    $module[$key]["current_roll"] = $current_roll;
                    $full_id_roll = implode(",",$full_roll);
                    $display_full_roll = $this->db->getAll("SELECT id, name FROM tbl_sys_roll WHERE id IN ($full_id_roll) ORDER BY ordered");
                    $module[$key]["full_roll"] = $display_full_roll;
                }
            }
        }
		//pre($module);

        $this -> assign("module",$module);
		 $this -> clearCache('assign_roll.tpl');
        return $this->smarty->fetch('assign_roll.tpl');
    }
    function splitRoll($str)
    {
        return $arr = explode("-",$str);
    }
}
?>
