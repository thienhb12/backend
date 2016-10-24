<div class="box-home box-list amthuc">
	<div class="box-header">
		<h1 class="title"><a>{$detail.title}</a></h1>
	</div>
	<div class="box-body">
			<div class="content mrb">{$detail.content}</div>
			<div class="clear"></div>
			{if $other}
				<div class="other list1">
					<ul class="list-simple">
						<li class="first"><a>{#other_news#}</a></li>
						{foreach from = $other item = item name = item}
							<li><a href="{$item.page_url}" >{$item.title}</a></li>
						{/foreach}
					</ul>
				</div>
			{/if}
	</div>
	<div class="brb"><div><blockquote>&nbsp;</blockquote></div></div>
</div>
