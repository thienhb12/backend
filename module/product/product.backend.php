<?php
class productBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "tbl_product_item";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'product';
		$this -> type = 'sp';
		$this -> img_path = "/upload/product/";
		$this -> img_path_thumb = "/upload/product/thumb/";
	}

	function run($task)
	{
        switch( $task ){
            case 'webtube':
                $this -> getWebtube();
                break;                
            case 'pager':
                $this -> pager();
                break;
            case 'add':
                $this -> addItem();
                break;
            case 'edit':
                $this -> editItem();
                break;
            case 'delete':
                $this -> deleteItem();
                break;
            default:
                $this -> listItem( $_GET['msg'] );
                break;
        }
	}
        
    function pager() {
		$template = 'list_product.tpl';
		$limit = 10;
		$page = isset($_GET['page'])?$_GET['page']:1;
		$where = "status = 1 ";                
		if($_GET['filter']) {
                    $where .= " AND Name like '%".$_GET['filter']."%'";
		}

		$num_rows = $this -> db -> getOne("SELECT count(id) from ".$this->table." WHERE {$where}");
		$eu = $limit*($page-1);

		$sql = "SELECT id, Name FROM ".$this -> table;
		$sql .= " WHERE {$where} order by ID DESC LIMIT $eu, $limit ";
		//echo $sql;
		$items = $this -> db -> getAll($sql);
		pre($items);
		$result['total_pages'] = ceil($num_rows/ $limit);
		$result['items'] = $items;
		echo json_encode($result);
	}
        
        function getWebtube() {
            $list = $this -> db -> getAll("SELECT * from categories where site_id= 3 and status = 1");
            foreach($list as $val) {
                $this ->getTinFromWebtube($val);
            }            
        }
        
        function getTinFromWebtube($cate) {
            $url = 'http://truelife.vn/offica/webtube/action';
            $url .= '?_f=31&start=0&limit=1000&ownerId=14252&name='.urlencode($cate['name']).'&type=file';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POST, 1);
            $data = curl_exec($curl);
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($data) {
                    $obj = json_decode($data);
                    $status = $obj->{'success'};
                    if($status) {
                            $list = $obj->{'object'};
                            for($i = 0; $i < count($list); $i++) {
                            $creator = $list[$i]->creator;
                                $content = $list[$i]->content;
                                $content = json_decode($content);
  
                                $tag = $cate['lead'];
                                $aData  = array(
									"lang_id"     => "1",
									"site_id"     => $this -> site_id,
									"cate_id"     => $cate['id'],
									"title"       => str_replace("'", '"', $list[$i]->header),
									"lead"        => $aData["title"]. $tag,
									"tagcloud"    => $tag,
									content       => "",
									"sub_title"   => $_POST['sub_title'],
									"ext"         => $_POST['ext'],
									"is_hot"      => 0,
									"create_date" => ("Y-m-d H:i"),
									"is_home"     => 1,
									"type"        => $this -> type,
									"status"      => 1,
									"ordering"    => 1                            
                                );
                                
                                
                                
                                $aData['source'] = $content->{'truelifeId'};
                                $aData['page_source'] = 'truelife.vn';
                                $aData['avatar'] = $content->{'avatar'};
                                $aData['create_date'] = $content->{'lastUpdatedate'};
								$this -> id = $this -> insert($aData);
                                
                                parent::tagsCloud($tag);

                                $aData = array(
                                        "page_url" => strtolower(parent::removeMarks(stripcslashes($list[$i]->header)))."-a{$this -> id}.html"
                                );
                                $this -> updateById($aData);
                            }
                    }else {
                           echo 'Lỗi'; exit();
                    }
            }

            curl_close($curl);
        }


	function addItem(){
		$this -> buildForm();
	}
	
	function editItem(){
		$row = $this -> getById();
		$row['Content'] =  $this->getOne("SELECT Content FROM tbl_product_content WHERE ProID= ".$row['id']);		
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{
		$this -> deleteImage($this -> id,"avatar",$this -> img_path);
		$this -> deleteByid();
		$this -> backPageList();
	}

	function indexOf($str, $seach ) {
		$pos = strrpos($str, $seach);
		if($pos===false) {
			return false;
		} else {
			return substr($str, $pos+1);
		}
	}
	
	function buildForm( $data=array() ,$msg = ''){
	
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			if($this ->indexOf($_POST['create_date'], ' AM') || $this ->indexOf($_POST['create_date'], ' FM')) {
				$_POST['create_date'] = str_replace(" AM", "", $_POST['create_date']);
				$_POST['create_date'] = str_replace(" FM", "", $_POST['create_date']);
			}
		
			$aData  = array(
				"CatID"       => $_POST['CatID'],
				"Sub_title"   => $_POST['sub_title'],
				"Name"        => $_POST['Name'],
				"PriceID"     => $_POST['PriceID'],
				"Keyword"     => $_POST['keyword'],
				"MacID"       => $_POST['MacID'],
				"Summarise"   => $_POST['Summarise'],
				"Description" => $_POST['Description'],
				"Code"        => $_POST['Code'],
				"Old_Price"   => $_POST['Old_Price'],
				"Price"       => $_POST['Price'],
				"giagoc"      => $_POST['giagoc'],
				"CreateDate"  => $_POST['CreateDate'],
				"Soluong"     => $_POST['Soluong'],
				"IsNew"       => ($_POST['IsNew'] == 1)?1:0,
				"IsHot"       => ($_POST['IsHot'] == 1)?1:0,
				"CreateDate"  => date('Y-m-d h:i:s'),
				"Status"      => $_POST['Status'],
			);

			if ($_POST['Photo']!='')
				$aData['Photo'] = $_POST['Photo'];
			if( !$_POST['id'] ){
                $id = $this -> insert($aData);
                if( $id){
                	$this -> insert(array('Content'=> $_POST['Content'],'ProID' => $id),'tbl_product_content');
                }
			}else {
				$this -> updateById($aData);
                $this -> updateById(array('Content' => $_POST['Content'],'ProID' => $id),'tbl_product_content');
			}

			parent::tagsCloud($_POST['tagcloud']);

			$aData = array(
                "page_url" => '/'.strtolower(parent::removeMarks(stripcslashes($_POST['Name'])))."-a{$this -> id}.html"
			);

			$this -> updateById($aData);
          
			//$this->clearCacheArticle();
		
			$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
		}

		$data['create_date'] = ($data['create_date']) ? ($data['create_date']) : date("Y-m-d H:i");
		$category            = $this -> getCategory('tbl_product_category');
		$category_Mac        = $this -> getCategory('tbl_product_mac');
		$category_Price      = $this -> getCategory('tbl_product_price');
	
		$this -> assign('detail', $data);
		$this -> assign('list_related', $this->getRelatedById($this -> id));
        $this -> assign('category', $category);
        $this -> assign('category_Mac', $category_Mac);
        $this -> assign('category_Price', $category_Price);

		$this -> assign('lang', parent::getAssocLang());
		$this -> display('backend/product.backend.tpl');
	}

	function clearCacheArticle() {
		global $cache;
		$cache -> delete("news_article_{$this -> id}", "article");
	}
       
    function getCategory($table)
	{
		$result = parent::multiLevel($table,"id","ParentID","*",$cond,"ordering ASC");
		$category = array();
		foreach ($result as $value => $key)
		{
			if( $key['level'] > 0){
				$name = $this -> getPrefix( $key['level']).$key['Name'];
			}
			else 
				$name = $key['Name'];
			$category[$key['id']] = $name;
		}
		
		return $category;
	}
	

	function deleteImage($id,$field,$path){
		if($id == '')
			return;
		$imgpath = $path.$this -> db-> getOne("SELECT $field FROM ".$this -> table." WHERE id = $id");
		if(is_file($imgpath))
			@unlink($imgpath);
	}

	function changestatus( $itemId ,$status ){
		$aData = array( 'status' => $status );
		$this -> aDb -> updateWithPk( $itemId,$aData );
		return true;
	}
	
	function listItem( $sMsg= '' )
	{
		$this -> loadConfig();
		$root_path = "{$this -> getConfigVars('list')} {$this -> getConfigVars('product')}";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		$where = " 1 ";
		
		$table = "(SELECT * FROM {$this -> table} WHERE {$where}) a";

		$arr_filter= array(
			array(
				'field' => 'Name',
				'display' => "Tên sản phẩm",
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);
		

		$arr_filter[] = array(
				'field' => 'CatID',
				'display' => 'Danh mục',
				'name' => 'filter_cate',
				'selected' => $_REQUEST['filter_cate'],
				//'options' => $this -> getCategory($this -> type),
				'filterable' => true
			);
		$arr_cols= array(
			array(
				"field" => "id",
				"primary_key" =>true,
				"display" => "Id",
				"align" => "center",
				"sortable" => true,
				"order_default" => "DESC"
			),
			array(
				"field" => "Name",
				"display" => 'Tên sản phẩm',
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "CatID",
				"display" => "Danh mục",
				"sql"=> "SELECT name FROM tbl_product_category WHERE id = CatID",
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "Photo",
				"display" => "Ảnh",
				"datatype" => "img"
			),
			
			array(
				"field" => "Status",
				"display" => $this->getConfigVars('status'),
				"datatype" => "publish"
			)
		);
		
		$arr_check = array(
			array(
				"task" => "delete_all",
				"confirm"=> "Xác nhận xóa?",
				"display" => "Xóa"
			),
			array(
				"task" => "public_all",
				"confirm"=> "Xác nhận thay đổi trạng thái?",
				"display" => "Kích hoạt"
			),
			array(
				"task" => "unpublic_all",
				"confirm"=> "Xác nhận thay đổi trạng thái?",
				"display" => "Vô hiệu"
			)
		);
		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}
}

?>