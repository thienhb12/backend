<div class="box ">
	<!--<div class="box-header">
		<h3 class="nav"><a href="/" class="first">Trang chủ</a><a href="/tagcloud/">TagCound</a></h3>
	</div>-->
	<div class="box-body list-tagcloud">
		
		{foreach from = $items item = item name = item}
			{assign var='random' value=1|rand:10}  

			{assign var='font' value=10|rand:35}
			<a href="{$item.page_url}" class="color{$random}" style="font-size:{$font}px">{$item.name}</a>&nbsp;&nbsp;
		{foreachelse}
			<div style="text-align:center; padding:5px; color:red;">{#updating#}</div>
		{/foreach}

		{if $num_rows > $limit}
			<div class="paging">
				{$num_rows|page:$limit:$smarty.get.page:$paging_path}
			</div>
		{/if}
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
</div>