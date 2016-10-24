<div class="box">
	<div class="box-header">
		<h3 class="color11 nav"><a href="/tin-tuc/" class="first">Tin tức</a>{$str_nav}</a></h3>
	</div>
	<div class="box-body">
		<div class="item-list">
			{foreach from = $items item = item name = item}
				<div class="item">
					{if $item.avatar}
						<a href="{$item.page_url}" title="" class="avatar">
							<img src="{$item.avatar}" alt="{$item.title}" />
						</a>
					{/if}
					<h3 class="title"><a href="{$item.page_url}" title="">{$item.title}</a></h3>
					<div class="lead">{$item.lead}</div>
					<div class="more"><a href="{$item.page_url}">{#more#}</a></div>
					<div class="clear"></div>
				</div>
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
	</div>
</div>

