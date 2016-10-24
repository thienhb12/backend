<?php

ini_set('error_reporting', E_ALL & ~E_NOTICE); 
	ini_set("display_errors",1);
        
        include_once('solr.php');
        include_once('simple_html_dom.php');
        include_once('remove_html_tags.php');
class crawler extends Anpro_Module_Base 
{
	public function __construct($smarty,$db)
	{
		$table = "articles";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'cralwer';
		$this -> type = 'tin-tuc';
	}

	function run($task= "")
	{
		if($task=='') {$task = $_GET['task'];}
		switch($task)
                {
                    case 'details':
                            $this -> detail();
                    break;
                    default:
                            $this -> listItem();
                    break;
                }
	}

	function detail()
	{
		if(isset($_GET['id']) && $_GET['id'] != '') 
			$detail = $this -> getById($_GET['id'], 'news_sources');
		
		switch ($detail['site_name']){
			case 'MegaFun':
				$this -> getMegaFun($detail);
			break;
			case 'BaoNgheAn':
				$this -> getKhac($detail);
			break;
			default:
				echo 'Khác MegaFun';
		}
	}

	function getKhac($detail) {
		$page = $_GET['page']?$_GET['page']: 1;
		$filter = $_GET['q']?$_GET['q']: '';
		$limit = 100;
		$solr = new solr($filter, $page, $limit, $detail['url'], 262, $detail['content']);
		$list = $solr -> getList();
		for($i = (count($list) - 1); $i >= 0; $i--) {
			$item = $list[$i];
			if(!$this -> checkExistArticle($item['title'], $item['pageUrl'])){
				$aData  = array(
					"lang_id"=> "1",
					"cate_id" => $detail['category_id'],
					"title" => $item['title'],
                                        "tagcloud" => $item['tagcloud'],
					"sub_title" => $item['subtitle'],
					"lead" => $item['lead'],
					"content" => $this -> getContent($item['pageUrl'], $detail['domain']),
					"create_date" => date("Y-m-d h:i"),
					"type" => $this -> type,
                                        "site_id" => $this->site_id,
					"status" => 0,
					"ordering" => "1",
					"source" => $detail['site_name'],
					"page_source" => $item['pageUrl']
				);

				if ($item['avatar']!='')
					$aData['avatar'] = $item['avatar'];

				if(!preg_match('/((http:||https:)\/\/.*?)[\/||\n||\s]/s', $aData['avatar'])) {
					$aData['avatar'] = $detail['domain']. str_replace('normal', 'original', $aData['avatar']);
				}
				$this -> insertRow($aData);
			}
		}
	}

	function getMegaFun($detail) {
		$page = $_GET['page']?$_GET['page']: 1;
		$filter = $_GET['q']?$_GET['q']: '';
		$limit = 100;
		$solr = new solr($filter, $page, $limit, $detail['url']);
		$list = $solr -> getList();
		for($i = (count($list) - 1); $i >= 0; $i--) {
			$item = $list[$i];
			if(!$this -> checkExistArticle($item['title'], $item['pageUrl'])){
				$item['lead'] = str_replace('(MegaFun.vn) - ', '',$item['lead'] );
				$item['lead'] = str_replace('(MegaFun)', '', $item['lead'] );
				$aData  = array(
					"lang_id"=> "1",
					"cate_id" => $detail['category_id'],
					"title" => $item['title'],
                                        "tagcloud" => $item['tagcloud'],
					"sub_title" => $item['subtitle'],
					"lead" => $item['lead'],
                                        "site_id" => $this->site_id,
					"content" => $this -> getContentMegaFun($item['pageUrl'], $detail['domain']),
					"create_date" => date("Y-m-d h:i"),
					"status" => 1,
					"ordering" => "1",
					"is_hot" => 1,
					"source" => $detail['site_name'],
					"page_source" => $item['pageUrl']
				);
				if ($item['avatar']!='')
					$aData['avatar'] = $item['avatar'];


				if(!preg_match('/((http:||https:)\/\/.*?)[\/||\n||\s]/s', $aData['avatar'])) {
					$aData['avatar'] = $detail['domain']. str_replace('normal', 'original', $aData['avatar']);
				}
				$this -> insertRow($aData);
			}
		}
	}

	function getContentMegaFun($url, $domain) {
            
		$html = file_get_html($url.'index.htm');
		$domain = 'http://megafun.vn';
		foreach( $html->find('img') as $img) {        
			if (substr($img->src,0,1) == "/") 
				$img->src = $domain.$img->src;
			if (substr($img->src,0,1) == " ")
			{
				$img->src = str_replace(" ","",$img->src);
				$img->src = $domain.$img->src;
			}
			if (substr($img->src,0,1) == "	")
			{
				$img->src = str_replace("	","",$img->src);
				$img->src = $domain.$img->src;
			}
		};

		$content = $html->find('div[id=content]', 0);
		$content = $content->innertext;
		$san = new HTML_Sanitizer(); 
		$content = $san -> sanitize($content);

		$content = stripTagByTagName($content, 'table', 'id="related_items"');

		$content = str_replace('<p></p>', '',$content );
		$content = str_replace('(MegaFun)', '',$content );
		$content = str_replace('(MegaFun.vn)', '',$content );

		return $content;
	}
        
	function getContent($url, $domain) {
		$html = file_get_html($url);
		foreach( $html->find('img') as $img) {        
			if (substr($img->src,0,1) == "/") 
				$img->src = $domain.$img->src;
			if (substr($img->src,0,1) == " ")
			{
				$img->src = str_replace(" ","",$img->src);
				$img->src = $domain.$img->src;
			}
			if (substr($img->src,0,1) == "	")
			{
				$img->src = str_replace("	","",$img->src);
				$img->src = $domain.$img->src;
			}
		};

		$content = $html->find('div[id=content]', 0);
		$content = $content->innertext;
		$san = new HTML_Sanitizer(); 
		$content = $san -> sanitize($content);

		$content = stripTagByTagName($content, 'table', 'id="related_items"');

		$content = str_replace('<p></p>', '',$content );
                
		return $content;
	}

	function insertRow($item) {
            $id = $this ->insert($item, 'articles');
            $item = array(
                "page_url" => '/'.strtolower(parent::removeMarks(stripcslashes($item['title'])))."-a{$id}.html"
            );
            $this -> updateById($item, $id,'articles');
	}

	function checkExistArticle($title, $page_url) {
		$sql = "SELECT id FROM ".ARTICLE." WHERE title like '%{$title}%'";
		$id = $this -> db -> getOne($sql);
		if(!empty($id)) return true;
		$sql = "SELECT id FROM ".ARTICLE." WHERE page_source = '{$page_url}'";
		$id = $this -> db -> getOne($sql);
		if(!empty($id)) return true;

		return false;
	}

	function listItem($cat_id=0)
	{
		$template = 'list_crawler.tpl';
		$sql = "SELECT * FROM {$this -> table} WHERE status = 1";
		$items = $this -> db -> getAll($sql);
		$this -> smarty -> assign('items',$items);
		$this -> smarty -> clearCache($template);
		$this -> smarty -> display($template);
	}
}
?>