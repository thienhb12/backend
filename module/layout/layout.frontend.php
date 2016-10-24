<?php
class layout extends Anpro_Module_Base 
{
	public function __construct($smarty,$db)
	{
		$table = "layouts";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
	}

	function run($task= "")
	{
		$modul= (isset($_GET["mod"]) && $_GET["mod"]!=""  )?$_GET["mod"]:"home";
		if($modul == 'admin' && is_file("module/layout/templates/{$modul}.tpl")){
				$this->display($modul.".tpl");
		}
		else{
			if($modul == 'store') {
				$detail = $this -> db -> getRow("SELECT * FROM {$this->table} WHERE status = 1 AND id = 1");
				$my_cache_id = $_GET['test'];
			
				$this -> smarty->setTemplateDir('themes/site/templates/');
				$this -> smarty->setConfigDir('themes/site/configs/');
				$this->caching = false;
				$cacheDir = $this -> smarty->getCacheDir();
				//echo $cacheDir;
				if(!$this -> smarty -> isCached('home.tpl',$my_cache_id)) {
					// No cache available, do variable assignments here.
					$abc = '13123213';
					$this -> assign('abc', $abc);
				}
				$this -> display('home.tpl', $my_cache_id);
			}else {
				if( is_file("module/layout/templates/{$modul}.tpl")){
					$this->display($modul.".tpl");
				}
				else{
				
					$this->display("default.tpl");
				}
			}
		}
		
	}
}
?>
