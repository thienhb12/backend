<?php
class manage_lang extends Anpro_Module_Base 
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "tbl_sys_lang";
		parent::__construct($smarty, $db, $datagird, $table);
		parent::getRootPath();
	}
	
	function run($task)
	{
		$str_id = parserGetUrl('arr_check');
	
				switch ($task)
				{
					case 'delete':
						$this->deleteImage($this->id, "flag", SITE_DIR."languages/");
						$this->aDb->deleteWithPk($this->id);
						parent::redirect($_COOKIE['re_dir']); 
						break;
					case 'delete_all':
						$this->aDb->deleteWithPk($str_id);
						parent::redirect($_COOKIE['re_dir']); 
						break;
					case 'unpublic_all':
						$this->aDb->updateWithPk($str_id,  array("status"=>"0"));
						parent::redirect($_COOKIE['re_dir']);
						break;
					case 'public_all':
						$this->aDb->updateWithPk($str_id,  array("status"=>"1"));
						parent::redirect($_COOKIE['re_dir']);
						break;
					case 'list':
						$this->list();
						break;
					case 'add':
						$this->insert($_GET['sub']);
						break;
					case 'edit':
						$this->update($_GET['sub']);
						break;
					default:
							$this->listRecordModul();
						break;
					}
		

	}
	
	function getPageInfo($task)
	{
		return true;
	}
	
	function insert($sub)
	{
		$this -> getPath("System Config >> Manage Language >> Add Language ");
		if($this->isPost($_POST)){
			if($_FILES['flag']['name'] != ''){
				$name_file = rand().'_'.$_FILES['flag']['name'];
				parent::crop_image($_FILES['flag']['tmp_name'],$name_file, SITE_DIR."languages/","100","50", true);
				$_POST['flag'] = "languages/".$name_file;
			}
			$this -> write_file_config($_POST['config_file'],$_POST['content_config_file']);
			$this->aDb->insert($_POST);
			parent::redirect($_COOKIE['re_dir']);
		}
		
		$this->makeFormProduct('add');
	}
	
	function update($sub)
	{
		$this -> getPath("System Config >> Manage Language >> Edit Language");
		if($this->isPost()){
			if($_FILES['flag']['name'] != ''){
				$this->deleteImage($this->id, "flag", SITE_DIR."languages/");
				$name_file = rand().'_'.$_FILES['flag']['name'];
				parent::crop_image($_FILES['flag']['tmp_name'],$name_file, SITE_DIR."languages/","100","50", true);
				$_POST['flag'] = "languages/".$name_file;
			}
			@unlink("./languages/".$_POST['config_file']);
			$this -> write_file_config($_POST['config_file'],$_POST['content_config_file']);
			$this->aDb->updateWithPk($this->id, $_POST);
		}
		
		if($_POST['btnSubmit'] == '')
			$this->makeFormProduct('edit');
		else
			$this->listRecordModul();
	}
		
	function listRecordModul(&$msg = null)
	{
		$root_path = "System config > Manage Language > List Language";
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
				"display" => "Name",
				"datatype" => "text",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "config_file",
				"display" => "Config File Name",
				"datatype" => "text",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "flag",
				"display" => 'Flag',
				"datatype" => "img",
				"img_path" => SITE_URL,
				"sortable" => true,
				"searchable" => true
			)
		);
		
		$arr_check = array(
			array(
			'task' => 'delete_all',
			'confirm'	=> 'Are you sure?',
			'display' => 'Xóa'
			)
		);
		
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($this->table, $arr_cols, $arr_filter, "?".$_SERVER['QUERY_STRING'], $arr_action, null, $root_path, false,$arr_check);

	}
	
	function deleteImage($id, $field, $path){
		if($id == '')
			return;
		$imgpath = $path.$this->db->getOne("SELECT $field FROM ".$this->table." WHERE id = $id");
		if(is_file($imgpath))
			@unlink($imgpath);
	}
	
	function read_file_config($filename){
			$file_path = "./languages/".$filename;
			if(is_file($file_path)){
				$handle = fopen($file_path,'r');
				$contents = fread($handle, filesize($file_path));
				fclose($handle);
			}
			return $contents;
	}
	
	function write_file_config($filename,$content){
			$file_path = "./languages/".$filename;
			$handle = fopen($file_path,'w');
			$contents = fwrite($handle, $content);
			fclose($handle);
	}
	
	function makeFormProduct ($task, $id=0)
	{
		$form = new HTML_QuickForm('frmCategory','post',$_COOKIE['re_dir']."&task=".$_GET['task']);
		
		if($task == 'edit'){
			$row = $this->aDb->getRow($this->id);
			$row['content_config_file'] =  $this -> read_file_config($row['config_file']);
			$form->setDefaults($row);
		}
				
		$form->addElement('text', 'name', "Name", array('size' => 50, 'maxlength' => 255));
		$form->addElement('text', 'config_file', "Config file name", array('size' => 50, 'maxlength' => 255));
        
		$form->addElement('file', 'flag', 'Flag image');
		
		if($task=='edit' && $row['flag'] !='')
			$form->addElement('static', null, '',"<a href='".SITE_URL.$row['flag']."' onclick='return hs.expand(this)' class='highslide'><img src='".SITE_URL.$row['flag']."' width=100 hight=100 border=0></a>");
		
		$form -> addElement('textarea', "content_config_file", "Content of config file", 
												array("style" => "width:600px; height:400px;"));
		
        $btn_group[] = &HTML_QuickForm::createElement('submit',"btnSubmit",'Submit');
		
        $btn_group[] = &HTML_QuickForm::createElement('button',null,'Back',array('onclick'=>'window.history.go(-1)'));
		
        $form -> addGroup($btn_group);
      
		$form->addElement('hidden', 'id', $row['id']);
		
        $form -> addRule('name','Name cannot be blank','required',null,'client');
		
		$form->display();

	}
}
?>
