<div class="box">
	<div class="box-header">
		<ol class="breadcrumb">
		  <li><a href="/">Trang chủ</a></li>
		  <li><a href="/tin-tuc/">Tin tức</a></li>
		  {$str_nav}
		</ol>
	</div>
	<div class="box-body">
		<div class="item-list">
			{foreach from = $items item = item name = item}
				<figure class="item {if $item@first}first{/if}">
					<a href="{$item.page_url}" title="" class="avatar">
						{if $item.avatar}<img src="{$item.avatar}" alt="{$item.title}" />{/if}
						<figcaption class="title">{$item.title}</figcaption>
						<summary class="lead">{$item.lead}</summary>
						<div class="clear"></div>
					</a>
				</figure>
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

