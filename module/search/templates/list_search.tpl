<div class="box">
	<div class="box-header"><h3 class="">Kết quả tìm kiếm từ khóa <font color="red">"{$key}"</font></h3></div>
	<div class="box-body border">
		<div class="item-list">
			{foreach from = $itemsListNews item = item name = item}
				<div class="item {if $item@first}first{/if}">
					<a href="{$item.page_url}" title="" class="avatar">
						{if $item.avatar}<img src="{$item.avatar}" alt="{$item.title}" />{/if}
						<div class="title">{$item.title}</div>
						<div class="clearfix"></div>
					</a>
				</div>
			{foreachelse}
				<div style="text-align:center; padding:5px; color:red;">{#updating#}</div>
			{/foreach}
			<div class="clearfix">.</div>
		</div>

		{if $num_rows > $limit}
			<div class="paging">
				{$num_rows|page:$limit:$smarty.get.page:$paging_path}
			</div>
		{/if}
		<div class="clearfix">.</div>
	</div>
</div>
