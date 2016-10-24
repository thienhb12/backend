<?php
class printorderBackEnd extends Bsg_Module_Base{
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
			case 'printorder':
				$this -> printorder();
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
		
		$sql = "SELECT sessionid FROM tbl_order WHERE id = {$ido} AND Delivery = 0 ";
		$session_id = $oDb->getOne($sql);
		
		$sql2 = "SELECT * FROM tbl_shopping_cart WHERE sessionid = '".$session_id."' ";
		$result = $oDb->getAll($sql2);
		
		
		
		foreach ($result as $key => $value)
		{
			$productid=$value['product_id'];
			$quantity2=$value['quantity'];
			
			$sql3 = "SELECT Quantity FROM tbl_product_item WHERE id ={$productid} ";
			$quantity1 = $oDb->getOne($sql3);
			
			
			
				if($quantity1 < $quantity2)
				{
					$quantity3 = 0;
					$soluong=array('Quantity'=>$quantity3);
					$oDb->autoExecute('tbl_product_item',$soluong,DB_AUTOQUERY_UPDATE,"id = $productid");
				}
				else
				{
				$quantity3 = $quantity1-$quantity2;
				$soluong=array('Quantity'=>$quantity3);
				$oDb->autoExecute('tbl_product_item',$soluong,DB_AUTOQUERY_UPDATE,"id = $productid");
				}
			
		}
		
		
	}
	
	function deleteItem()
	{
		global  $oDb;
		
		$id = $_GET["id"];
		
		$sql = "SELECT sessionid FROM tbl_order WHERE id = {$id} ";
		$session_id = $oDb->getOne($sql);
		
		$sql2 = "DELETE FROM tbl_shopping_cart WHERE sessionid = '".$session_id."'";
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
		$sql = "SELECT CartSessionID FROM tbl_transaction WHERE OrderID = '{$order_id}'";
		$session_id = $oDb->getOne($sql);
		
		$result = $oDb->getAll("SELECT * FROM tbl_shopping_cart WHERE sessionid = '{$session_id}'");
		$total = 0;		
		foreach ($result as $key => $value) {
			
			$products = $oDb->getRow("SELECT * FROM tbl_product_item WHERE id = ".$result[$key]['product_id']);
			$subtotal = $products['Price']*$result[$key]['quantity'];

			$vat = ($subtotal*10)/100;
			$result[$key]['subtotal'] = $subtotal;
			$result[$key]['vat'] = $vat;
			$result[$key]['product'] = $products;
			$total = $total + $subtotal + $vat;
		}
		$html = '	<table width="100%" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">				';
		$html .= '		<tr align="center"><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th><th>VAT 10%</th></tr>	';
		foreach($result as $key => $val){
			$html .= '<tr>';	   		
			$html .= '	<td bgcolor="#FFFFFF" align="center">';
			$html .= '		<a href="'.SITE_URL.'product/'.$val['product']['id'].'-'.remove_marks($val['product']['Name']).'.html">';
			$html .= '			<img src="'.SITE_URL.$val['product']['Photo'].'" height="88px;" border="0" />';
			$html .= '		</a><br />';
			$html .= '		<a href="'.SITE_URL.'product/'.$val['product']['id'].'-'.remove_marks($val['product']['Name']).'.html" style="color:#006699; font-weight:bold">'.$val['product']['Name'].'</a>';
			$html .= '	</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="110" align="center" valign="middle">'.$val['quantity'].'</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="120" align="center" style="font-weight:bold; color:#FF0000" >'.number_format($val['product']['Price'], 0, ".", ".").'VNĐ</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="120" align="center" style="font-weight:bold; color:#FF0000" >'.number_format($result[$key]['subtotal'], 0, ".", ".").'VNĐ</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="120" align="center" style="font-weight:bold; color:#FF0000" >'.number_format($result[$key]['vat'], 0, ".", ".").'VNĐ</td>';
			$html .= '</tr>';
		}
		$html .= '		<tr>';
		$html .= '			<td bgcolor="#FFFFFF" colspan="4" align="right" style="font-weight:bold; text-transform:uppercase">Tổng cộng &nbsp;&nbsp;</td>';
		$html .= '			<td bgcolor="#FFFFFF" colspan="4" align="center" ><span id="total" style="font-weight:bold; color:#FF0000">&nbsp;&nbsp;'.number_format($total, 0, ".", ".").' VNĐ</span></td>';			   
		$html .= '		</tr> ';
		$html .= '	</table>';
		
		return $html;
	}
	function printorder()
	{
		global $oDb, $oSmarty;
		echo '<script type="text/javascript" src="/lib/printpage.js"></script>';
		$id_order=$_GET['id'];
		$Fullname=$_GET['Fullname'];
		
		
		$sql = "SELECT * FROM tbl_order WHERE id = '{$id_order}'";
		$result_order = $oDb->getRow($sql);
		$thanhtoan=$result_order['Payment'];
		if($thanhtoan==0)
		{
			$thanhtoan1="Trực tiếp";
		}
		if($thanhtoan==1)
		{
			$thanhtoan1="Qua tài khoản";
		}
		
		
		
		echo '<a href='.'javascript:CallPrint("printpage");'.'><b>Print this page</b></a>';
		
		echo '<div id="printpage">';
		
		
		
		
		
		
		echo '<table width="100%" border="0" cellpadding="3" cellspacing="0">
	
	<tr>
		<td width="100%" align="center" colspan="3"><b>ĐỀ NGHỊ XUẤT KHO</b></td>
	</tr>
	<tr>
		<td width="33%">Khách hàng : <b>'.$result_order['Fullname'].'</b></td>
		<td width="33%">Điện thoại : '.$result_order['Phone'].'</td>
		<td width="34%">Kho : ........</td>
	</tr>
	<tr>
		<td width="33%">Hình thức thanh toán : '.$thanhtoan1.'</td>
		<td width="33%">Tỷ giá : ........................</td>
		<td width="34%">Đặt cọc : ........................</td>
	</tr>
	<tr>
		<td width="100%" colspan="3">Lý do xuất kho : ..................................................</td>
	</tr>
	<tr>
		<td width="100%" colspan="3">Yêu cầu giao hàng lúc : ........giờ..........phút,ngày : '.$result_order['DeliveryDate'].'    tại : '.$result_order['Address'].'</td>
	</tr>
	<tr>
		<td width="33%">................................Người giao hàng : </td>
		<td width="33%"><input type="checkbox" /> &nbsp;&nbsp;Vận chuyển</td>
		<td width="34%"><input type="checkbox" /> &nbsp;&nbsp;Kỹ thuật</td>
	</tr>
</table>
';

		$sql2 = "SELECT sessionid FROM tbl_order WHERE id = '{$id_order}'";
		$session_id = $oDb->getOne($sql2);
		$result = $oDb->getAll("SELECT * FROM tbl_shopping_cart WHERE sessionid = '{$session_id}'");
		$total = 0;		
		foreach ($result as $key => $value) {
			
			$products = $oDb->getRow("SELECT * FROM tbl_product_item WHERE id = ".$result[$key]['product_id']);
			$subtotal = $products['Price']*$result[$key]['quantity'];

			$vat = ($subtotal*10)/100;
			$result[$key]['subtotal'] = $subtotal;
			$result[$key]['vat'] = $vat;
			$result[$key]['product'] = $products;
			$total = $total + $subtotal + $vat;
		}
		$stt=0;
		$html = '	<table width="100%" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">				';
		$html .= '		<tr align="center"><th>STT</th><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th><th>VAT 10%</th></tr>	';
		foreach($result as $key => $val){
			$stt=$stt+1;
			$html .= '<tr>';	  
			$html .= '	<td bgcolor="#FFFFFF" align="center">'.$stt.'</td>'; 		
			$html .= '	<td bgcolor="#FFFFFF" align="center">';
			$html .= '		<a href="'.SITE_URL.'product/'.$val['product']['id'].'-'.remove_marks($val['product']['Name']).'.html">';
			//$html .= '			<img src="'.SITE_URL.$val['product']['Photo'].'" height="88px;" border="0" />';
			$html .= '		</a><br />';
			$html .= '		<a href="'.SITE_URL.'product/'.$val['product']['id'].'-'.remove_marks($val['product']['Name']).'.html" style="color:#006699; font-weight:bold">'.$val['product']['Name'].'</a>';
			$html .= '	</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="110" align="center" valign="middle">'.$val['quantity'].'</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="120" align="center" style="font-weight:bold; color:#FF0000" >'.number_format($val['product']['Price'], 0, ".", ".").'VNĐ</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="120" align="center" style="font-weight:bold; color:#FF0000" >'.number_format($result[$key]['subtotal'], 0, ".", ".").'VNĐ</td>';
			$html .= '	<td bgcolor="#FFFFFF" width="120" align="center" style="font-weight:bold; color:#FF0000" >'.number_format($result[$key]['vat'], 0, ".", ".").'VNĐ</td>';
			$html .= '</tr>';
		}
		$html .= '		<tr>';
		$html .= '			<td bgcolor="#FFFFFF" colspan="4" align="right" style="font-weight:bold; text-transform:uppercase">Tổng cộng &nbsp;&nbsp;</td>';
		$html .= '			<td bgcolor="#FFFFFF" colspan="4" align="center" ><span id="total" style="font-weight:bold; color:#FF0000">&nbsp;&nbsp;'.number_format($total, 0, ".", ".").' VNĐ</span></td>';			   
		$html .= '		</tr> ';
		$html .= '	</table><br/><br/>';
		echo $html;
		
		
		echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="50%" align="left" valign="top">Kế toán nhận phiếu lúc: ....giờ.....phút</td>
		<td width="50%" align="right" valign="top">...............Ngày .......tháng........năm..................</td>
	</tr>
	<tr>
		<td width="50%" align="left" valign="top">Kế toán ký nhận:</td>
		<td width="50%" align="right" valign="top">Kinh doanh lập phiếu<br/>(Ký,ghi rõ họ tên)&nbsp;&nbsp;&nbsp;&nbsp;</td>
	</tr>
</table><br/><br/><br/><br/><br/>';

		
		echo '<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td width="70%" align="center" valign="top" style="border:#666666 1px solid">THÔNG TIN VIẾT HÓA ĐƠN GTGT</td>
		<td width="15%" align="left" valign="top" style="border:#666666 1px solid"> &nbsp; Số PX:</td>
		<td width="15%" align="left" valign="top" style="border:#666666 1px solid"> &nbsp; Ngày xuất:</td>
	</tr>
	
	<tr>
		<td colspan="3" style="border-left:#666666 1px solid;border-right:#666666 1px solid;">Tên đơn vị : </td>
	</tr>
	<tr>
		<td colspan="3" style="border-left:#666666 1px solid;border-right:#666666 1px solid;">Địa chỉ : </td>
	</tr>
	<tr>
		<td colspan="3" style="border-left:#666666 1px solid;border-right:#666666 1px solid;">Mã số thuế : </td>
	</tr>
	<tr>
		<td colspan="3" style="border-left:#666666 1px solid;border-right:#666666 1px solid;"><input type="checkbox" />&nbsp; Xuất ngay khi nhận hàng &nbsp;&nbsp; <input type="checkbox" /> Xuất hóa đơn sau(ngày đề nghị xuất : ....../......../................) </td>
	</tr>
	<tr>
		<td colspan="3" style="border-left:#666666 1px solid;border-right:#666666 1px solid;border-bottom:#666666 1px solid;  ">Các yêu cầu khác : </td>
	</tr>
	
</table>';

		echo '</div>';
		
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
				$this -> except($id);				
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
		
		$aData=$oDb->getAll( "select * from tbl_order where Delivery =1 AND Status=1");
		
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
				'link' => SITE_URL.'index.php?mod=admin&amod='.$_GET['amod'].'&atask='.$_GET['atask'].'&tab=1&frame&task=printorder',				
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
		$oDatagrid->display_datagrid($aData, $arr_cols, $arr_filter, $submit_url, $this -> getAct() ,120, $root_path, false ,$arr_check);		
		
	}		
	
}

?>