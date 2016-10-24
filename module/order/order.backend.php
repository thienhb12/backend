<?php
class orderBackEnd extends Bsg_Module_Base{
	var $smarty;
	var $db;
	var $url="";
    var $datagrid;
	var $id;
	var $imgPath;
	var $imgPathShort;
	var $table='';
	var $arrAction;
	public function __construct($oSmarty, $oDb, $oDatagrid)
	{	
		$this -> smarty = $oSmarty;
		$this -> db = $oDb;		
        $this -> datagrid = $oDatagrid;
		$this -> id=$_REQUEST[id];		
		$this -> table	="tbl_order";	 		
		parent::__construct($oDb);		
		$this->bsgDb->setTable($this->table);
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
			case 'delete_all':
				$this -> deleteItems();
				break;
			case 'change_status':				
				$this -> changeStatus($_GET['id'], $_GET['status']);
				break;
			case 'public_all':						
				$this -> changeStatusMultiple( 1 );
				break;
			case 'unpublic_all':						
				$this -> changeStatusMultiple( 0 );
				break;
			case 'save_order':
				$this -> saveOrder();
				break;
			default:					
				$this -> listItem( $_GET['msg'] );		
				break;
		}
	}
	
	function getPageInfo()
	{
		return true;
	}

	function addItem()
	{
		$this -> getPath("Thông tin liên hệ > Thêm mới liên hệ");		
		$this -> buildForm();
	}
	
	function editItem()
	{
		$id = $_GET['id'];
		$this -> getPath("Thông tin liên hệ > Chỉnh sửa liên hệ");	
		$row = $this -> bsgDb -> getRow( $id );
		$this -> buildForm( $row );
	}
	
	function except($ido)
	{
		global $oDb, $oSmarty;		
	}
	
	function deleteItem()
	{
		global  $oDb;
		
		$id = $_GET["id"];
		$sql2 = "DELETE FROM tbl_transaction WHERE OrderId = {$id}";
		$oDb->query($sql2);
		
		$this -> bsgDb -> deleteWithPk( $id );
		$msg = "Xóa liên hệ thành công!";
		$this -> listItem( $msg );
	}
	
	function deleteItems()
	{
		$aItems	 = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',', $aItems );
			$this -> bsgDb -> deleteWithPk( $sItems );
		}
		$msg = "Xóa (các) liên hệ thành công!";
		$this -> listItem( $msg );
	}
	
	function changeStatusMultiple( $status = 0 )
	{
		$aItems	 = $_GET['arr_check'];
		if(is_array( $aItems) && count( $aItems) > 0){
			$sItems = implode( ',', $aItems );
			$this -> bsgDb -> updateWithPk( $sItems, array("Status" => $status) );
		}
		$msg = "Sửa trạng thái liên hệ thành công!";
		$this -> listItem( $msg );
	}
	
	function getCartContent($order_id)
	{
		global $oDb, $oSmarty;
		//$sql = "SELECT CartSessionID FROM tbl_mahoadon WHERE OrderID = '{$order_id}'";
		//$session_id = $oDb->getOne($sql);
		
		//$result = $oDb->getAll("SELECT * FROM tbl_shopping_cart WHERE sessionid = '{$session_id}'");
		$result = $oDb->getAll("SELECT * FROM tbl_transaction WHERE OrderID = '{$order_id}'");
		$total = 0;		
		foreach ($result as $key => $value) {
			
			$products = $oDb->getRow("SELECT * FROM tbl_product_item WHERE id = ".$result[$key]['product_id']);
			$subtotal = $products['Price']*$result[$key]['quantity'];
			$vat = ($subtotal*10)/100;
			$result[$key]['subtotal'] = $subtotal;
			$result[$key]['vat'] = $vat;
			$result[$key]['product'] = $products;
			//$total = $total + $subtotal + $vat;
			$total = $total + $subtotal ;
		}
		$html = '	<table width="100%" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">				';
		$html .= '		<tr align="center"><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th></tr>	';
		foreach($result as $key => $val){
			$html .= '<tr>';	   		
			$html .= '	<td bgcolor="#FFFFFF" align="center">';
			$html .= '		<a href="'.SITE_URL.'product/'.$val['product']['id'].'/'.remove_marks($val['product']['Name']).'.html">';
			$html .= '			<img src="'.SITE_URL.$val['product']['Photo'].'" height="88px;" border="0" />';
			$html .= '		</a><br />';
			$html .= '		<a href="'.SITE_URL.'product/'.$val['product']['id'].'/'.remove_marks($val['product']['Name']).'.html" style="color:#006699; font-weight:bold">'.$val['product']['Name'].'</a>';
			$html .= '	</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="110" align="center" valign="middle">'.$val['quantity'].'</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="120" align="center" style="font-weight:bold; color:#FF0000" >'.number_format($val['product']['Price'], 0, ".", ".").'VNĐ</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="120" align="center" style="font-weight:bold; color:#FF0000" >'.number_format($result[$key]['subtotal'], 0, ".", ".").'VNĐ</td>';
			//$html .= '	<td bgcolor="#FFFFFF" width="120" align="center" style="font-weight:bold; color:#FF0000" >'.number_format($result[$key]['vat'], 0, ".", ".").'VNĐ</td>';
			$html .= '</tr>';
		}
		$html .= '		<tr>';
		$html .= '			<td bgcolor="#FFFFFF" colspan="3" align="right" style="font-weight:bold; text-transform:uppercase">Tổng cộng &nbsp;&nbsp;</td>';
		$html .= '			<td bgcolor="#FFFFFF" colspan="3" align="center" ><span id="total" style="font-weight:bold; color:#FF0000">&nbsp;&nbsp;'.number_format($total, 0, ".", ".").' VNĐ</span></td>';			   
		$html .= '		</tr> ';
		$html .= '	</table>';
		
		return $html;
	}
	
	function buildForm( $data=array() , $msg = ''){
		
		$form = new HTML_QuickForm('frmAdmin','post',$_COOKIE['re_dir']."&task=".$_GET['task'], '', "style='padding:10px 15px 0 20px;'");
		
		$form -> setDefaults($data);
		
		$car_content = $this->getCartContent($data['id']);
		$form -> addElement('static',NULL,'Sản phẩm đã mua',$car_content);
		
		$form -> addElement('text', 'Fullname', 'Họ tên', array('size' => 50, 'maxlength' => 255));
		
		$form -> addElement('text', 'Phone', 'Điện thoại', array('size' => 50, 'maxlength' => 255));	
		
		$form -> addElement('text', 'CMND', 'Số CMND', array('size' => 50, 'maxlength' => 255));
		
		$form -> addElement('textarea','Address','Địa chỉ giao hàng',array('style'=>'width:500px; height:100px;'));
		
		$form -> addElement('text', 'DeliveryDate', 'Ngày giao hàng', array('size' => 50, 'maxlength' => 255));        
		
		
		$form -> addElement('select', 'Payment', 'Hình thức thanh toán', array("0" => "Trực tiếp","1" => "Qua tài khoản"));
		$form -> addElement('text', 'Deposit', 'Đã đặt cọc', array('size' => 50, 'maxlength' => 255));
		
		
		$form -> addElement('select', 'Delivery', 'Giao hàng', array("0" => "Chờ giao dịch","1" => "Giao dịch thành công","2" => "Hủy giao dịch"));
		
		$form -> addElement('checkbox', 'Status', 'Kích hoạt');
		
		$btn_group[] = $form -> createElement('submit',null,'Hoàn tất',array("style"=> "border:1px solid gray; padding:0 5px 0 5px;"));		
        $btn_group[] = $form -> createElement('button',null,'Quay lại',array('onclick'=>'window.location.href = \''.$_COOKIE['re_dir'].'\'', "style"=> "border:1px solid gray;"));      
        $form -> addGroup($btn_group);
      
		$form->addElement('hidden', 'id', $data['id']);		
		
		if( $form -> validate())
		{
			
			$aData  = array(
				"Fullname" 	=> $_POST['Fullname'],
				"Phone" 	=> $_POST['Phone'],
				"CMND" 		=> $_POST['CMND'],
				"Address" 	=> $_POST['Address'],
				"Payment" 	=> $_POST['Payment'],
				"Deposit" 	=> $_POST['Deposit'],
				"DeliveryDate" 	=> $_POST['DeliveryDate'],
				"Delivery" 	=> $_POST['Delivery'],
				"Status" 	=> $_POST['Status']
			);
			if( !$_POST['id'] ){
				
				 $id = $this -> bsgDb -> insert($aData);				 
				 $msg = "Thêm liên hệ thành công! ";
			}else {
				$id = $_POST['id'];
				//$this -> except($id);				
				$this -> bsgDb -> updateWithPk($id, $aData);
				$msg = "Chỉnh sửa liên hệ thành công ";
			}
			
			$this -> redirect($_COOKIE['re_dir']. "&msg={$msg}");
		}
		
		$form->display();
	}
	
	function deleteImage($id, $field, $path){
		if($id == '')
			return;
		$imgpath = $path.$this->db->getOne("SELECT $field FROM ".$this->table." WHERE id = $id");
		if(is_file($imgpath))			
			@unlink($imgpath);
	}
	
	function changeStatus( $itemId , $status ){
		$aData = array( 'Status' => $status );
		$this -> bsgDb -> updateWithPk( $itemId, $aData );
		return true;
	}
	
	function listItem( $sMsg= '' )
	{		
		global $oDb;
		global $oDatagrid;				
		
		$root_path = "Thông tin liên hệ > Danh sách liên hệ";						
		$submit_url= "/index.php?".$_SERVER['QUERY_STRING'];
		
		$table = $this -> table;
		
		$arr_filter= array(			
			array(
				'field' => 'Status',
				'display' => 'Trạng thái',				
				'name' => 'filter_show',
				'selected' => $_REQUEST['filter_show'],
				'options' => array('Vô hiệu','Kích hoạt'),
				'filterable' => true
			)
			
			
		); 
		
		$arr_cols= array(		
			
			array(
				"field" => "id",					
				"primary_key" =>true,
				"display" => "Id",				
				"align" => "center",
				"sortable" => true
			),
			array(
				"field" => "Fullname",
				"display" => "Họ tên",
				"align"	=> 'left',				
				"datatype" => "text",
				"sortable" => true
			),	
            array(
				"field" => "Phone",
				"display" => "Phone",
				"align"	=> 'left',				
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "Payment",
				"display" => "Thanh toán",				
				"datatype" => "value_set",
				"case"	=> array("0" => "Trực tiếp","1" => "Qua tài khoản"),
				"sortable" => true
			),
			array(
				"field" => "Deposit",
				"display" => "Đặt cọc",
				"align"	=> 'left',				
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "DeliveryDate",
				"display" => "Ngày giao hàng",
				"align"	=> 'left',				
				"datatype" => "text",
				"sortable" => true
			),
			array(
				"field" => "Delivery",
				"display" => "Giao hàng",				
				"datatype" => "value_set",
				"case"	=> array("0" => "Chờ giao dịch","1" => "Giao dịch thành công","2" => "Hủy giao dịch"),
				"sortable" => true
			),
			array(
				"field" => "Status",
				"display" => "Trạng thái",				
				"datatype" => "publish",
				"sortable" => true
			),		
		);		
		
		$arr_check = array(
			array(
				"task" => "delete_all",
				"confirm"	=> "Xác nhận xóa?",
				"display" => "Xóa"
			),
			array(
				"task" => "public_all",
				"confirm"	=> "Xác nhận thay đổi trạng thái?",
				"display" => "Kích hoạt"
			),
			array(
				"task" => "unpublic_all",
				"confirm"	=> "Xác nhận thay đổi trạng thái?",
				"display" => "Vô hiệu"
			)			
		);
		if( $sMsg )
			$oDatagrid -> setMessage( $sMsg );
		$oDatagrid->display_datagrid($table, $arr_cols, $arr_filter, $submit_url, $this -> getAct() ,120, $root_path, false ,$arr_check);		
		
	}		
	
}

?>