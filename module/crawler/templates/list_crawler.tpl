<div id="list-cate" class="mrb box">
	<div class="box-header">
		<h2 class="nav"><span>Cralwer</span></h2></blockquote>
	</div>
	<div class="box-body">
		<table class="list-crawler" cellspacing="3"  cellpadding="3">
			<tr class="item head">
				<th>STT</th>
				<th>Tên chuyên mục</th>
				<th>Tên Site</th>
				<th>Crawler</th>
			</tr>
			{foreach from = $items item = item name = item}
			<tr class="item">
				<td class="stt">{$smarty.foreach.item.index}</td>
				<td>{$item.category}</td>
				<td>{$item.site_name}</td>
				<td class="link"><a href="/?task=details&mod=crawler&id={$item.id}" target="blank">Crawler</a></td>
			</tr>
			{foreachelse}
				<tr style="text-align:center; padding:5px; color:red;"><td colspan="4">Không có dữ liệu</td></tr>
			{/foreach}
		</table>
	</div>
</div>
