<?php
class pollBackEnd extends Anpro_Module_Base 
{
	private $_table_poll_option;
	public function __construct($smarty,$db,$datagird)
	{
		$table = "poll";
		$this -> _table_poll_option = "poll_option";
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		parent::getRootPath();
		$this -> mod = 'poll';
		$this -> img_path = "/upload/contact/";
	}

	function run( $task)
	{
		switch ( $task )
		{
			default:
				$this -> listItem();
				break;
			case 'add':
				$this -> addItem() ;
				break;
			case 'edit':
				$this -> editItem();
				break;
			case 'delete':
				$this -> deleteItem();
				break;
			case 'change_status':
				$this -> changeStatus();
				break;
		}
	}
	
	function addItem()
	{
		$this -> buildForm ();
	}
	
	function editItem()
	{
		$sql = "select id, title from {$this -> _table_poll_option} where poll_id = '{$this ->id}' ORDER BY id";

		$list_answer = $this -> db -> getAll($sql);

		$this -> assign('list_answer', $list_answer);

		$detail = $this -> getById();
		$this -> assign('detail', $detail);

		$this -> buildForm ($list_answer);
	}
	
	function deleteItem()
	{
		$id = $_GET['id'];
		$sql = "DELETE FROM {$this -> _table_poll_option} WHERE poll_id = '{$id}'";
		$this -> db -> query ( $sql );
		$this -> deleteById($id);
		$this -> listItem();
	}
	
	function changeStatus()
	{
		$id = $_GET['id'];
		$status = $_GET['status'];
		$this -> aDb -> updateWithPk ( $id, array ( 'status' => $status));
		
	}
	
	function buildForm( $arrData=array() )
	{
		if( $this -> isPost())
		{
			$aData = array(
					"question"=> $_POST['question'],
					"description"=> $_POST['description'],
					"type"=> $_POST['type'],
					"status"=> $_POST['status']
			);
			if(!$_POST['id'] ){
				$id = $this -> insert($aData, $this -> table);
			}else {
				$id = $_POST['id'];
				$this -> updateById($aData,$id);
			}

			for($i = 0; $i< count($_POST['answer']); $i++) {
				$data = array(
					"poll_id"=> $id,
					"title"=> $_POST['answer'][$i]
				);
					
				$this -> insert($data, $this -> _table_poll_option);
			}
			for($i = 0; $i< count($arrData); $i++)
			{
				$ans =  'answer_'.$arrData[$i]['id'];
				if($_POST[$ans] != '')
				{
					$data = array(
						"title"=> $_POST[$ans]
					);

					$err = $this -> updateById($data, $arrData[$i]['id'],  $this -> _table_poll_option);
				}else {
					$this -> deleteById($arrData[$i]['id'], $this -> _table_poll_option);
				}
			}
			$this -> redirect($_COOKIE['re_dir']);
		}

		$arr_type = array(
			"0"=> "Chọn 1 kết quả",
			"1"=> "Chọn nhiều kết quả",
		);
		$this -> assign('arr_type', $arr_type);
		$this -> display('poll_admin.tpl', true);
	}
	
	function view_stats()
	{
		$maxWidth = 350;
		$id = $_GET['id'];
		$sql = "SELECT * FROM tbl_poll WHERE id = '$id'";
		$pollQuestion =  $this -> aDb -> getRow($sql);
		#print_r($pollQuestion); die;
		if($pollQuestion){
			$sql = "SELECT id, title, number_poll FROM {$table_poll_option} WHERE poll_id = '$id' ORDER BY  id asc";
			$pollAnswer = $this -> aDb -> getAll($sql);
			if($pollQuestion['total_poll'] > 0){
				if($pollAnswer){
					foreach($pollAnswer as $key => $val){
						$pollAnswer[$key]['percent'] = round(($val['number_poll']/$pollQuestion['total_poll'])*100,2);
						$pollAnswer[$key]['width'] = floor(($val['number_poll']/$pollQuestion['total_poll'])*$maxWidth);
					}
				}
			}
			#print_r($pollAnswer);
			$$this -> smarty -> assign("pollQuestion",$pollQuestion);
			$$this -> smarty -> assign("pollAnswer",$pollAnswer);
		}
		$path = str_replace('poll.backend.php','',__FILE__);
		$$this -> smarty -> display("file:".$path."templates/view_statsItem.tpl");
	}

		function listItem($sMsg= '')
	{
		$this -> loadConfig();
		$root_path = "<li>{$this -> getConfigVars('list')} {$this -> getConfigVars('poll')}</li>";
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];

		$arr_filter= array(
			array(
				'field' => 'question',
				'display' => $this -> getConfigVars('question'),
				'type' => 'text',
				'name' => 'title',
				'selected' => $_REQUEST['title'],
				'filterable' => true
			),
			array(
				"field" 	=>"status",
				"display" 	=> $this -> getConfigVars('status'),
				"style" 	=> "width:110px;",
				"name" 		=> "status",
				"selected" 	=> isset($_REQUEST["status"])?$_REQUEST["status"]:"",
              	"options" 	=> array('Vô hiệu','Kích hoạt'),
				"filterable"=> true
			)
		); 
		
		$arr_cols= array(
			array(
				"field" => "id",
				"primary_key" =>true,
				"visible"=>"hidden",
				"datatype" => "text",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "question",
				"display" => $this -> getConfigVars('question'),
				"datatype" => "text",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field" => "total_poll",
				"display" => $this -> getConfigVars('numberpoll'),
				"datatype" => "int",
				"sortable" => true,
				"searchable" => true
			),
			array(
				"field"=> "status",
				"display"=> $this -> getConfigVars('status'),
				"datatype"=> "publish"
			)
			
		);

		$arrAction = $this -> getAct();
		$arrAction[] = array(
				"task" => "",
				"icon" => "view.gif",
				"action" => "view_stats",
				"tooltip" => $this -> getConfigVars('viewresult')
			
		);

		if( $sMsg )
			$this -> datagrid -> setMessage( $sMsg );
		$arr_action = parent::getAct();

		$this -> datagrid -> display_datagrid($this->table, $arr_cols, $arr_filter, $submit_url, $arrAction ,120, $root_path, false);
	}
}
?>