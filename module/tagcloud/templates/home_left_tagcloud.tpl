<div class="list1 box-home box-l auto">
	<div class="box-header"><blockquote><h2><a href="{$items[0].cate_url}">{$items[0].cate_title}</a></h2></blockquote></div>
	<div class="box-body">
		<ul class="list-simple">
			{foreach item=item from=$items name=item}
					<li class="first">
						{if $item.avatar}
							<a href="{$item.page_url}" title="" class="avatar">
								<img src="{$item.avatar}" alt="{$item.title}" />
							</a>
						{/if}
						<h3 class="title"><a href="{$item.page_url}" title="">{$item.title}</a></h3>
						<div class="lead">{$item.lead}</div>
						<div class="more"><a href="{$item.page_url}">{#more#}</a></div>
						<div class="clear"></div>
					</li>
				{if $smarty.foreach.item.index mod 2 == 0}
					<li class="clear"></li>
				{/if}
			{/foreach}
		</ul>
		<div class="clear">.</div>
	</div>
	<div class="brb"><div><blockquote>&nbsp;</blockquote></div></div>
</div>
<div class="clear">.</div>