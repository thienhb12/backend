<?php
class product_typeBackEnd extends Anpro_Module_Base 
{
	public function __construct($smarty,$db,$datagird)
	{
		$table = "product_type";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> mod = 'product_type';
		$this -> type = 'product_type';
	}

	function run($task)
	{
		switch( $task ){
			case 'add':
				$this -> addItem();
				break;
			case 'edit':
				$this -> editItem();
				break;
			case 'delete':
				$this -> deleteItem();
				break;
			case 'editor':
				$this -> editorItem();
				break;
			case 'change_status':
				$this -> changestatus($_GET['id'],$_GET['status']);
				break;
			default:
				$this -> listItem( $_GET['msg'] );
				break;
		}
	}

	function addItem(){
		$this -> buildForm();
	}
	
	function editItem(){
		$row = $this -> getById();
		$this -> buildForm( $row );
	}
	
	function deleteItem()
	{
		$id = $_GET["id"];
		$this -> aDb -> updateWithPk( $id, array("status" => 9) );
		$this -> listItem();
	}

    function saveOrder(){
        $aItem = $_GET['ordering'];
        if(is_array($aItem) && count( $aItem ) > 0){
            // save order for item.
            foreach( $aItem as $key => $value){
                if( !is_numeric($value)) $value = 0;
                $this -> aDb -> updateWithPk( $key,array('ordering' => $value ));
            }
        }
        $this -> listItem();
    }
	
	function buildForm( $data=array() ,$msg = '')
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$aData  = array(
				"lang_id"=> $this -> lang_id,
				"site_id"=> $this -> site_id,
				"name" => $_POST['name'],
				"parent_id" => $_POST['parent_id'],
				"status" => $_POST['status'],
				"color" => $_POST['color'],
				"is_hot" => ($_POST['is_hot']== 'on')?1:0,
				"class_icon" => $_POST['class_icon'],
				"ordering" => ($_POST['ordering'])?$_POST['ordering']:"1",
			);

			$aData['root'] = $this -> getRootByParentId($_POST['parent_id'], $_POST['name']);

			if( !$_POST['id'] ){
				$id = $this -> insert($aData);
			}else {
				$id = $_POST['id'];
				$this -> updateById($aData);
			}

			$aData = array(
				"page_url" => '/' .strtolower(parent::removeMarks(stripcslashes($_POST['name']))) . '-c' . $id . '/'
			);

			$this -> updateById($aData);

			$this -> product_type2step($id, $_POST['attribute_id']);

			$this -> redirect(str_replace("task=".$_GET['task'], '', $_SERVER['REQUEST_URI']. "&msg={$msg}"));
		}

		$sql = "SELECT id, name FROM STEP WHERE lang_id = {$this -> lang_id} status = 1 ORDER BY ordering DESC limit 0, 20";
		$list_step = $this -> db -> getAll($sql);
		$this -> assign('list_step_items', $list_step);

		$parent = $this -> getAssocCategory();

		if($data['id'] != '') {
			$sql = "SELECT s.id, s.name  FROM product_type2step ts, step s WHERE ts.product_type_id = {$data[id]} AND  s.id = ts.step_id order by ts.id";
			$list_step_of_product = $this -> getAll($sql);
			$this -> assign('list_step_of_product', $list_step_of_product);
		}

		$this -> assign('detail', $data);
		$this -> assign('parent', $parent);
		$this -> display('product_type.backend.tpl');
	}

	//function add tags
	function product_type2step($product_type_id, $arr_step_id){

		if(count($arr_step_id) > 0){
			$this -> delete("product_type_id = {$product_type_id}", 'product_type2step');
			foreach($arr_step_id as $step_id)
			{
				$data = array(
					'product_type_id' => $product_type_id,
					'step_id' => $step_id
				);
				$this -> insert($data, 'product_type2step');
			}
		}
	}

	function changestatus( $itemId ,$status ){
		$aData = array( 'status' => $status );
		$this -> aDb -> updateWithPk( $itemId,$aData );
		return true;
	}

	function getAssocCategory($parent_id = -1)
	{
		$sql = "SELECT id, root,parent_id from " . $this -> table . " WHERE status = 1 ";

		if ($parent_id > 0) {
			$sql .= "AND parent_id = ".$parent_id;
		}
		$sql .= " order by root, ordering";
		//echo $sql;
		$arr = $this -> db -> getAll($sql);
		for($i = 0; $i < count($arr); $i++)
		{
			$array[$arr[$i]['id']] = $arr[$i]['root'];
		}
		return $array;
	}
	
	function listItem( $sMsg= '' )
	{
		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('product_type')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM {$this -> table} where site_id = {$this -> site_id} ) a";
		
		$arr_filter= array(
			array(
				'field' => 'name',
				'display' => $this -> getConfigVars('product_type'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);
        if(MULTI_LANGUAGE)
            $arr_filter[] = array(
                    'field' => 'lang_id',
                    'display' => $this -> getConfigVars('language'),
                    'name' => 'filter_lang',
                    'selected' => $_REQUEST['filter_lang'],
                    'options' => parent::getAssocLang(),
                    'filterable' => true
                );

		$arr_filter[] = array(
				'field' => 'status',
				'display' => $this -> getConfigVars('status'),
				'name' => 'filter_show',
				'selected' => $_REQUEST['filter_show'],
				'options' => array($this -> getConfigVars('unpublic_all'),$this -> getConfigVars('public_all')),
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
				"field" => "root",
				"display" => $this -> getConfigVars('product_type'),
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "status",
				"display" => $this -> getConfigVars('status'),
				"datatype" => "publish"
			)
		);

		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );

		$arr_action = parent::getAct();
		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $arr_action, null, $root_path, false,$arr_check);
	}

	function editorItem()
	{

		if(isset($_GET['ordering'])) {
			$aItem = $_GET['ordering'];
			$list_id = "0";
			if(is_array($aItem) && count( $aItem ) > 0){
				// save order for item.
				foreach( $aItem as $key => $value){
					if( !is_numeric($value)) $value = 30;
					$this -> updateById(array('ordering' => $value ),$key, 'product');
					$list_id .= "," .$key;
				}

				if($_GET['page'] == 1 && isset($_GET['id'])) {
					$this -> updateField("product_type_id = {$_GET['id']} AND id not in ({$list_id})", array('ordering' =>  '30'), 'product');
				}
			}
		}

		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('product_type')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = "(SELECT * FROM product where site_id = {$this -> site_id} AND product_type_id = ".$_GET['id']." order by ordering ASC) a";
		
		$arr_filter= array(
			array(
				'field' => 'title',
				'display' => $this -> getConfigVars('title'),
				'type' => 'text',
				'name' => 'filter_title',
				'selected' => $_REQUEST['filter_title'],
				'filterable' => true
			)
		);

		$arr_cols= array(
			array(
				"field" => "id",
				"primary_key" =>true,
				"display" => "Id",
				"align" => "center",
				"sortable" => false,
				"order_default" => "DESC"
			),
			array(
				"field" => "title",
				"display" => $this -> getConfigVars('title'),
				'link' => SITE_URL.'index.php?mod=admin&amod=product&atask=product&task=edit',
				"align"=> 'left',
				"datatype" => "text",
				"sortable" => false
			),
			array(
				"field" => "ordering",
				"display" => $this -> getConfigVars('ordering'),
				"datatype" => "order",
				"sortable" => false
			),
			array(
				"field" => "status",
				"display" => $this -> getConfigVars('status'),
				"datatype" => "publish"
			)
		);

		$this -> datagrid -> display_datagrid($table, $arr_cols, $arr_filter, $submit_url, null, null, $root_path, false, "", "", 'ordering');
	}
}

?>