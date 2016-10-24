	<div class="box_center_title">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{#ORDER#}</div>
	<div class="box_center_content">
		<table align="center" width="100%" cellpadding="3" cellspacing="3" border="0" id="order_info">
			<tr>
				<td width="35%" align="right">{#ct_comp#}: </td>
				<td style="color:#FF0000; font-weight:bold ; text-align:left;">{$order.Company}</td>
			</tr>
			<tr>
				<td align="right">{#ct_fullname#}:
				<td>{$order.Fullname}</td>
			</tr>
			<tr>
				<td align="right">Email:</td>
				<td>{$order.Email}</td>
			</tr>
			<tr>
				<td align="right">{#ct_phone#}:</td>
				<td>{$order.Phone}</td>
			</tr>
			<tr>
				<td align="right">{#ct_fax#}:</td>
				<td>{$order.Fax}</td>
			</tr>
			<tr>
				<td align="right">{#ct_address#}:</td>
				<td>{$order.Address}</td>
			</tr>
		</table>
	</div>



