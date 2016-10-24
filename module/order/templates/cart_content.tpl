<table width="100%" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
	<tr align="center">
		<th>{#PRODUCT#}</th>
		<th>{#QUANTITY#}</th>
		<th>{#TOTAL#}</th>
	</tr>

	{foreach from=$result item=item name=item}
		<tr>
			<td bgcolor="#FFFFFF" align="center"><br />
				<a href='{$smarty.const.SITE_URL}project/{$pid}-{$item.product.Name|remove_marks}.html'>
					<img src="{$smarty.const.SITE_URL}{$item.product.Photo}" height="88px;" border="0" />
				</a>
				<a href='{$smarty.const.SITE_URL}project/{$pid}-{$item.product.Name|remove_marks}.html' style="color:#006699; font-weight:bold">{$item.product.Name}</a>
			</td>
			<td bgcolor="#FFFFFF" width="110" align="center" valign="middle">{$item.quantity}</td>
			<td bgcolor="#FFFFFF" width="120" align="right" style="font-weight:bold; color:#FF0000" >{$item.subtotal|number_format:0:".":"."} VNĐ</td>
		</tr>
	{/foreach}
	<tr>
		<td bgcolor="#FFFFFF" colspan="2" align="right" style="font-weight:bold; text-transform:uppercase">{#TOTAL#}</td>
		<td bgcolor="#FFFFFF" colspan="2"><span id="total" style="font-weight:bold; color:#FF0000">{$total|number_format:0:".":"."} VNĐ</span></td>
	</tr>

</table>





