<?php
function isMobile()
{    
    if(isset($_SERVER["HTTP_X_WAP_PROFILE"]))
        return true;
   if(preg_match('/wap.|.wap/i',$_SERVER["HTTP_ACCEPT"]))
        return true;
  
    if(isset($_SERVER["HTTP_USER_AGENT"]))
    {
        $user_agents = array(
            'midp', 'java', 'opera mini', 'opera mobi', 'mobi', 'nokia',
			'midp', 'midp', 'j2me', 'avantg', 'docomo', 'novarra', 'palmos', 
            'palmsource', '240x320', 'opwv', 'chtml', 'pda', 'windows ce', 
            'mmp\/', 'blackberry', 'mib\/', 'symbian', 'wireless', 'nokia', 
            'hand', 'mobi', 'phone', 'cdm', 'up.b', 'audio', 'SIE-', 'SEC-', 
            'samsung', 'HTC', 'mot-', 'mitsu', 'sagem', 'sony', 'alcatel', 
            'lg', 'erics', 'vx', 'NEC', 'philips', 'mmm', 'xx', 'panasonic', 
            'sharp', 'wap', 'sch', 'rover', 'pocket', 'benq', 'java', 'pt', 
            'pg', 'vox', 'amoi', 'bird', 'compal', 'kg', 'voda', 'sany', 
            'kdd', 'dbt', 'sendo', 'sgh', 'jb', 'dddi', 'iphone', 'ipad',
            'android', 'Android'
        );
        $user_agents  = implode('|', $user_agents );
        if (preg_match("/$user_agents/i", $_SERVER["HTTP_USER_AGENT"]))
            return true;
    }
    return false;
}

function displayErrors($is_display = false) {
	if($is_display) {
        error_reporting(E_ALL);
        ini_set('error_reporting', 30711);
        ini_set("display_errors", 1);
	}
}

//Define multi language
define("SBCMS",true);
define("ANPRO",1);
define("MULTI_LANGUAGE",false);
define("_IS_MOBILE_", isMobile());


// Define site info
define("SITE_URL", "http://".$_SERVER['SERVER_NAME']."/");
define("SITE_DIR", $_SERVER['DOCUMENT_ROOT']."/");
define("CATEGORIES", 'categories');
define("ARTICLE", 'articles');

// Define database info
define("DB_NAME", "backend_dqv2");
define("DB_USER", "root");
define("DB_PASSWORD", "");

/*// Define database conect host
define("DB_NAME", "laptoptocdo_247");
define("DB_USER", " laptoptocdo_247");
define("DB_PASSWORD", "fDKzSpivUr");*/

//Defined ERROR 
define("NOT_HAS_TEMPLATE", "Not has template ");
define("EMPTY_FIELD", "ERROR: Field cannot be blank");
define("ERROR_PERMISSION_MODULE", "ERROR: You not have permission to access this module!");
define("ERROR_PERMISSION_TASK", "ERROR: You not have permission to access this task!");
?>