<div id="mostread" class="row15">
	<div class="cate-title">Đọc nhiều nhất</div>
	<table class="list-simple" cellpadding="0" cellspacing="0">
		{foreach from = $most_read item = item name = item}
			<tr><td class="pos-{$smarty.foreach.item.index + 1}"><a href="{$item.page_url}">{$item.title}</a></td></tr>
		{/foreach}
	</tr></table>
</div>