<div class="box box-cate">
	<div class="nav row10">{$str_nav}<div class="clear">.</div></div>
	<div class="box-body">
		<div class="article">
			<div class="avatar" ><img src="{$detail.avatar|replace:'thumb/':''}" /></div>
			<h1 class="title">
				{if $detail.sub_title}<label class="sub_title">{$detail.sub_title}: </label> {/if}
				{$detail.title}
			</h1>
			<div class="date">{$detail.publish_date}</div>
			<div class="clear">.</div>
			<div class="content mrb">{$detail.content}</div>
			<div class="clear">.</div>
		</div>
		{php}
			//loadModule('tagcloud','article');
			loadModule('tool','sharetool');
			loadModule('comment','form');
			loadModule('comment', 'list');
		{/php}

		{if $other}
			<div class="others list1">
				<h2 style="margin-top: 20px; margin-bottom: 5px; font-weight: bold;">{#other_news#}</h2>
				<ul class="list-simple">
					{foreach from = $other item = item name = item}
						<li class="title"><a href="{$item.page_url}" >{$item.title}</a></li>
					{/foreach}
				</ul>
			</div>
		{/if}
	</div>
</div>


