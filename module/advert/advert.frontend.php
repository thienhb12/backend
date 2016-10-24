<?php
class advert
{
	function run($task = "")
	{
		global $smarty,$db;
		if($task == '') {
			$task = 'left';
		}

		$sql = "SELECT * FROM advert WHERE status=1 AND position = '{$task}' ORDER BY ordering ASC";
		$advert = $db -> getAll($sql);
		$smarty -> assign("advert",$advert);
		$smarty -> display($task . ".tpl");
	}
}
?>