<?php
class solr {
	private $list;
	private $filter;
	private $start;
	private $limit;
	private $numRow;
	private $url;
	private $siteId;
	private $page = 1;
	private $pagingHTML = "";
	private $listHTML = "";
	public function __construct($filter = '',$page = 1, $limit = 24, $cate = '', $siteId = 303, $url = 'http://s1.megafun.vn/select?')
	{
		$this -> filter = $filter; 
		$this -> page = $page;
		$this -> start = ($page - 1) * $limit;
		$this -> siteId = $siteId;
		$this -> limit = $limit;
		$this -> url = $url;

		if (!empty($_GET['page'])) {
			$this -> page = $_GET['page'];
		}

		$this -> getData($filter, $cate);
	}

	function getData($filter, $cate) {
		$url = $this -> url;
		$date = date("Y-m-d");
		if(!empty($filter)) {
			$url .= 'sort=score%20desc,date%20desc';
			$url .= '&defType=dismax';
			$url .= '&recip(ms(NOW,date),3.16e-11,1,1)';
			$url .= '&qf=title^1000+sub_title^100+lead^10+content^0.1';
			$url .= !empty($cate)?'&fq=cateid:' . $cate . '': '';
		}else {
			$url .= 'sort=date+desc';
			$url .= !empty($cate)?'&q=cateid:' . $cate . '': '';
			$url .= '%20AND%20date:['.$date.'T00:00:00.000Z%20TO%20'.$date.'T23:59:59.999Z]';
		}

		$url .= '&wt=json';
		#$url .= '&fq=object_type:article';
		$url .= '&fl=id,title,avatar,lead,url,date,sub_title,tag';
		$url .= '&start='. $this -> start;
		$url .= '&rows='. $this -> limit;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		echo $url;
		curl_setopt($ch, CURLOPT_URL,$url);
		$data = curl_exec($ch);
		curl_close($ch);

		if($data) {
			$obj = json_decode($data);
			$this -> numRow = $obj->{'response'}->{'numFound'};
			$list = $obj->{'response'}->{'docs'};
			for($i = 0; $i < count($list); $i++) {
				$item = array(
					'title' => '',
					'subTitle' => '',
					'pageUrl' => '',
					'lead' => '',
					'avatar' => '',
					'date' => '',
					'id' => '',
				);
				$item['title'] = $list[$i] -> title;
				$item['subTitle'] = $list[$i] -> sub_title;
				$item['pageUrl'] = $list[$i] -> url;
				$item['lead'] = $list[$i] -> lead;
				$item['date'] = $list[$i] -> date;
				$item['id'] = $list[$i] -> id;
				$item['avatar'] = $list[$i] -> avatar;
				if(is_array($list[$i] -> tag) && count($list[$i] -> tag) > 0)
                   $item['tagcloud'] = implode(',', $list[$i] -> tag);

				$this -> list[$i] = $item;
			}
		}else {
			$this -> numRow = -1;
		}
	}

	function getList() {
		return $this -> list;
	}
}
?>
