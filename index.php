<?php
    
    session_start();
    ini_set('error_reporting', 1); 
    ini_set("display_errors",E_ALL);

    include("config.php");
 
    include('core/classes/module.class.php');

    require_once('core/classes/cache.class.php');

    global $db, $cache, $site_id, $domain;
 
    include("core/core.php");
    $domain = str_replace('www.', '', $_SERVER['HTTP_HOST']);
    if($domain == 'backend.dev')
        $domain = 'backend.dev';
    if(!isset($_SESSION['site_id'])) {
       $sql = "SELECT id FROM anpro_site WHERE domain = '{$domain}' AND status = 1";
       $site_id = $db -> getOne($sql);
        $_SESSION['site_id'] = $site_id;
    }else {
        $site_id =  $_SESSION['site_id'];
    }
    $cache = new cache();

    define("DIR_ROOT_CACHE", $_SERVER[DOCUMENT_ROOT]."/sites/site{$_SESSION['site_id']}/cache/");
    // Language proccess...
    if(!isset($_SESSION['lang_id'])){
        $sql = "SELECT * FROM `tbl_sys_lang` WHERE 1 AND `default` = 1";
    }
    else{
        $sql = "SELECT * FROM tbl_sys_lang WHERE id =' ".$_SESSION['lang_id']."'";
    }
    if (!($site_config_lang = $cache->get('site_config_lang', "config"))) {
        $site_config_lang = $db->getRow($sql);
        
        $cache->save('site_config_lang', $site_config_lang, "config");
    }
   
    $_SESSION['lang_id'] = $site_config_lang['id'];

    $_SESSION['lang_file'] = $site_config_lang['config_file'];
    $_SESSION['flag'] = $site_config_lang['flag'];

    if(!isset($_REQUEST['ajax'])) {     
        loadModule("layout");
    }
    else
        loadModule($_GET['mod'], $_GET['task']);
?>