<?php
class header extends Anpro_Module_Base 
{
	public function __construct($smarty,$db)
	{
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
	}

	function run($task= "")
	{
		if($task == ''){$task = $_GET['task'];}
		switch($task)
		{
                    case 'menu':
                            $this -> getMenu();
                    break;
                    default:
                            $this->getHeader();
                    break;
		}

	}
	function getPageinfo()
	{ 
            $site_config = $this ->getSiteConfig();
            $task = isset($_GET['task'])? $_GET['task']:"";
            $aPageinfo=array(
                'title'=> $site_config['page_title'],
                'keyword'=> $site_config['page_keyword'],
                'description'=> $site_config['page_description'],
            );
            switch ($task) {
                case 'list':
                    $cate_id = (int)$_GET['id'];
                    $tintuc = $this -> getClass('tintuc');
                    $cate = $tintuc -> getCateById($cate_id);
                    $this -> checkUrl($cate['page_url']);
                    $aPageinfo['title'] = ($cate['name']?$cate['name']:"") . ' - ' . $site_config['page_title'];
                    $aPageinfo['description'] = $cate['lead']?$cate['lead']: $site_config['page_description'];
                    $aPageinfo['keyword'] = $site_config['page_keyword'];
                    break;
                case 'details':
                    if($_GET['mod'] == 'tintuc') {
                        $article_id = (int)$_GET['id'];
                        $tintuc = $this -> getClass('tintuc');
                        $article = $tintuc -> getArticleById($article_id);
                        $this -> checkUrl($article['page_url']);
                        $aPageinfo['title'] = ($article['title']?$article['title']:$site_config['page_title']);
                        $aPageinfo['description'] = $article['lead']?$article['lead']:$site_config['page_description'];
                        $aPageinfo['keyword'] = $article['tagcloud']?$article['tagcloud']:$site_config['page_keyword'];
                    }else {
                         $aPageinfo=array(
                            'title'=> $site_config['page_title'],
                            'keyword'=> $site_config['page_keyword'],
                            'description'=> $site_config['page_description']
                        );
                    }
                break;
            }
            $this -> assign('aPageinfo', $aPageinfo);
            $this -> assign('site_config', $site_config);
            $this -> assign('logo', $site_config['logo']);
	}

	function getHeader($task) {
        $this -> getPageinfo($task);
        if (CHECK_DEVICE == 1) {
            $this -> display('pc/header.tpl');
        }
        
       
       
	}
        
        function checkUrl($url) {
           if($_SERVER['REQUEST_URI'] != $url) {
               $this ->redirect(SITE_URL);
           }            
        }
}


?>