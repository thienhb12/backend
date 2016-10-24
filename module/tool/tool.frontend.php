<?php
class tool
{	
	function run($task= "")
	{

		global $smarty;

		if($task==''){$task = $_GET['task'];}
		$page_url = SITE_URL.$_SERVER['REQUEST_URI'];
		
		switch($task)
			{
				case 'fbfeedback':
					$smarty -> clearCache('fbfeedback.tpl');
					$smarty -> display("fbfeedback.tpl");
				break;
				case 'likebox':
					$smarty -> clearCache('likebox.tpl');
					$smarty -> display("likebox.tpl");
				break;
				case 'chat':
					$smarty -> clearCache('chat.tpl');
					$smarty -> display("chat.tpl");
				break;
				default:
					$smarty -> clearCache('like.tpl');
					$smarty -> display("like.tpl");
				break;
			}


	}
}
?>