<?php
class welcome extends Anpro_Module_Base
{
	public function __construct($smarty, $db, $datagird)
	{
		$table = "tbl_sys_menu_home";
		parent::__construct($smarty, $db, $datagrid, $table);
	}

	function run($task)
	{
		switch ($task)
		{
			default:
				$this -> showpanel();
			break;
		}
	}
	
	function showpanel()
	{
		$field = "id, title, link, avatar";
		if($_SESSION['lang_code'] == 'en') {
			$field = "id, title_en title, link, avatar";
		}

		$sql = "SELECT {$field} FROM {$this -> table} WHERE status = 1 ORDER BY ordering ASC limit 0, 10";

		$list_link = $this -> db -> getAll($sql);
		$list_modul = $this -> modulePermission();
		$list_menu;
		$j = 0;
		for($i = 0; $i < count($list_link); $i++) {
			if(in_array($this -> getModuleByLink($list_link[$i]['link']), $list_modul)) {
				$list_menu[$j] = $list_link[$i];
				if (isset($list_menu[$j]['link']) && $list_menu[$j]['link'] != '' && !preg_match("/^(http|ftp):/", $list_menu[$j]['link'])) {
					$list_menu[$j]['link'] = 'index.php?mod=admin&' .$list_menu[$j]['link'];
				}
				$j++;
			}
		}
		$this -> assign('list_menu', $list_menu);
		$this -> display('welcome.tpl', true); 
	}

	function getModuleByLink($link) {
		$sql = "SELECT id FROM tbl_sys_menu WHERE Link = '{$link}' AND status = 1";
		return $this -> db -> getOne($sql);
	}
}
?>
