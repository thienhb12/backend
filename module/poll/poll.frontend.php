<?php
class poll extends Anpro_Module_Base
{
	private $option_poll;
	public function __construct($smarty,$db)
	{
		$table = "poll";
		$datagird = null;
		parent::__construct($smarty,$db,$datagird,$table);
		$this -> aDb->setTable($table);
		$this -> mod = 'poll';
		$this -> option_poll = 'poll_option';
	}

	function run($task= "")
	{
		$task = $_GET['task'];
	 	switch ($task){
	 		default:
	 			$this->showPoll();
	 			break;
	 		case 'stats':
	 			$this -> view_stats();
	 			break;
	 		case 'poll':
				$this -> pollAction($_GET['pollid'],$_GET['answer']);
				break;
	 	}
    }

    function showPoll()
    {

    	$sql = "SELECT id, question,type FROM {$this -> table} WHERE status = '1' AND lang_id = 1 ORDER BY id LIMIT 1";

		$pollQuestion = $this -> db -> getRow($sql);
		if($pollQuestion){
			$sql = "SELECT id, title FROM {$this -> option_poll} WHERE poll_id = '$pollQuestion[id]' ORDER BY id ASC";
			$pollAnswer = $this -> db -> getAssoc($sql);

			if(isset($_SESSION[session_id()])){
				$str = explode(",",$_SESSION[session_id()]);
				if(in_array($pollQuestion['id'],$str)){
					$this -> smarty  -> assign("hide",1);
				}
			}
			$this -> smarty  -> assign("pollQuestion",$pollQuestion);
			$this -> smarty  -> assign("pollAnswer",$pollAnswer);
			$this -> smarty  -> display('viewPoll.tpl');
		}
    }
    
    function pollAction( $pollID = 0,$answer = 0)
    {
    	$arrAnswer = explode(',',$answer);
    	$total_answer = intval(count($arrAnswer));
		$sql = "UPDATE {$this -> table} SET total_poll = total_poll + $total_answer WHERE id = '$pollID'";
		$this -> db -> query($sql);
		$sql = "UPDATE {$this -> option_poll} SET number_poll = number_poll + 1 WHERE id IN ($answer)";
		$this -> db -> query($sql);
		if(isset($_SESSION[session_id()]))
			$_SESSION[session_id()] .= ','.$pollID;
		else
			$_SESSION[session_id()] = $pollID;
	}
    
    function view_stats()
	{
		$maxWidth = 350;
		$id = $_GET['id'];
		$sql = "SELECT * FROM {$this -> table} WHERE id = '$id'";
		$pollQuestion =  $this -> db -> getRow($sql);
		if($pollQuestion){
			$sql = "SELECT id, title, number_poll FROM {$this -> option_poll} WHERE poll_id = '$id' ORDER BY  id asc";
			$pollAnswer = $this -> db -> getAll($sql);
			if($pollQuestion['total_poll'] > 0){
				if($pollAnswer){
					foreach($pollAnswer as $key => $val){
						$pollAnswer[$key]['percent'] = round(($val['number_poll']/$pollQuestion['total_poll'])*100,2);
						$pollAnswer[$key]['width'] = floor(($val['number_poll']/$pollQuestion['total_poll'])*$maxWidth);
					}
				}
			}
			#print_r($pollAnswer);
			$this -> smarty  -> assign("pollQuestion",$pollQuestion);
			$this -> smarty  -> assign("pollAnswer",$pollAnswer);
		}
		
		$this -> smarty  -> display("view_statsPoll.tpl");
	}	
}
?>