<?php
class crawler extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "news_sources";
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
			default:
					$this->listRecordContentGuild($_GET["msg"]);
				break;
			}
	}

	function getPageInfo($task){
		return true;
	}

	function insertUpdate($task)
	{
		if($this->isPost())
		{
			$aData  =array(
				'id'					=> $_POST["id"],
				'site_name'				=> $_POST['site_name'],
				'url'					=> $_POST['url'],
				'category'				=> $_POST['category'],
				'title'					=> $_POST['title'],
				'lead'					=> $_POST['lead'],
				'content'				=> $_POST['content'],
				'full_content'				=> $_POST['full_content'],
				'domain'				=> $_POST['domain'],
				'link_attribute'		=> $_POST['link_attribute'],
				'status'				=> $_POST['status'],
				'category_id'			=> $_POST['category_id']
			);

			if( !$_POST['id'] ){
				 $id = $this -> aDb -> insert($aData);
				 $msg = "Item has been inserted at ". date('Y-m-d h:i:s');
			}else {
				$id = $_POST['id'];
				$this -> aDb -> updateWithPk($id, $aData);
				$msg = "Item has been updated at ". date('Y-m-d h:i:s');
			}

			parent::redirect($_COOKIE['re_dir']);
			//End Prosess Insert Update
		}
		
		$this->makeFormContentGuild($task);
	}
	
	function listRecordContentGuild($sMsg='')
	{
		$startdate = date('Y-m-d',strtotime("-1 month",strtotime(date("Y-m-d"))));
		$enddate = date('Y-m-d', time());

		$arr_cols = array(
			array(
			"field" => "id",
			"primary_key" => true,
			"visible" => "hidden",
			"display" => "id"
			),
			array(
			"field" => "url",
			"visible" => "hidden"
			),
			array(
				"field"			=> "title",
				"display"		=> 'Tiêu đề',
				"datatype"		=> "link",
				"page_url" 	=> "url"
			),
			array(
				"field"			=> "domain",
				"display"		=> 'Nguồn',
				"datatype"		=> "text",
				"sortable" 		=>true,
				"searchable" 	=> true
			),
			array(
				"field"			=> "category",
				"display"		=> 'Chuyên Mục',
				"datatype"		=> "text",
				"sortable" 		=>true,
				"searchable" 	=> true
			),
			array(
				"field" 		=> "status",
				"display" 		=> 'Trạng thái',
				"datatype"	 	=> "boolean",
				"width" 		=> "100",
				"sortable" 		=>true
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
		$table = "(SELECT * FROM {$this->table} WHERE id != 1) AS {$this->table}";
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, "?".$_SERVER['QUERY_STRING'], $arr_action, null, $root_path, false,$arr_check);

	}

	function getGroups()
	{
        $sTbl = "tbl_sys_groups";
        $query = "SELECT id, CONCAT('&nbsp;&nbsp;&nbsp;',Name) FROM {$sTbl}";
        $result = $this -> db -> getAssoc( $query );        
        return $result;
    }

	function makeFormContentGuild ($task, $id=0)
	{
		$form = new HTML_QuickForm('frmGuild','post',$_COOKIE['re_dir']."&task=".$_GET['task']);
		if($task == 'edit'){
			$row = $this->aDb->getRow($this->id);
			
			$result = $this -> db ->getAssoc("SELECT id, ModuleID FROM tbl_sys_users_module WHERE UserID = ". $this->id);
			foreach($result as $k=>$v){
				$row['module'][$v] = "1";
			}
			
			$form->setDefaults($row);
		}

		$form->addElement('text', 'site_name', 'Tên site', array('size' => 50, 'maxlength' => 255));
        $form->addElement('text', 'url', 'Link', array('size' => 50, 'maxlength' => 255));
        $form->addElement('text', 'category', "Chuyên mục", array('size' => 50, 'maxlength' => 255));
		$form->addElement('text', 'title', 'title', array('size' => 50, 'maxlength' => 255));
		$form->addElement('text', 'lead', 'lead', array('size' => 50, 'maxlength' => 255));
		$form->addElement('text', 'content', 'content', array('size' => 50, 'maxlength' => 255));
		$form->addElement('text', 'domain', 'domain', array('size' => 50, 'maxlength' => 255));
		$form->addElement('text', 'full_content', 'full_content', array('size' => 50, 'maxlength' => 255));
		$form->addElement('text', 'link_attribute', 'link_attribute', array('size' => 50, 'maxlength' => 255));
		$form->addElement('text', 'category_id', 'category_id', array('size' => 50, 'maxlength' => 255));
		
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
        return $smarty->fetch('assign_roll.tpl');
    }

    function splitRoll($str)
    {
        return $arr = explode("-",$str);
    }
}
?>
