<div class="list1 box-home box-l auto">
	<div class="brt"><div><blockquote>&nbsp;</blockquote></div></div>
	<div class="box-body">
		<ul class="list-simple">
			{foreach item=item from=$items name=item}
				{if $smarty.foreach.item.first}
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
				{else}
					<li><a href="{$item.page_url}" title="{$item.title}" >{$item.title}</a></li>
				{/if}
			{/foreach}
		</ul>
		<div class="clear">.</div>
	</div>
	<div class="brb"><div><blockquote>&nbsp;</blockquote></div></div>
</div>
