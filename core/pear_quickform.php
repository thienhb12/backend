<?php	
	global $form;		
	if(!is_object($form))
	{   	
    	ini_set('include_path', PATH_SEPARATOR.SITE_DIR.'/core/PEAR' . PATH_SEPARATOR . ini_get('include_path'));    	
    	include_once('HTML/QuickForm.php');    	

	}

?>
