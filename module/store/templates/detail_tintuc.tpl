<div class="box box-page">
	<div class="box-header">
		<ol class="breadcrumb">
		  <li><a href="/">Trang chủ</a></li>
		  <li><a href="/tin-tuc/">Tin tức</a></li>
		  {$str_nav}
		</ol>
	</div>
	<div class="box-body" id="article">
		{if $detail.avatar}
			<a href="{$detail.page_url}" title="" class="avatar">
				<img src="{$detail.avatar}" alt="{$detail.title}"/>
			</a>
		{/if}
		<h1 class="title">{$detail.title}</h1>
		{php}
			loadModule("tool", 'like');
		{/php}
		<div class="clear"></div>
		<article class="content" style="margin: 10px 0;">{$detail.content}</article>
		<div class="clear"></div>
		{php}
			loadModule('tagcloud','article');
			if(!_IS_MOBILE_)
				loadModule('advert','ad_article_bottom');
		{/php}
		{if $other}
			<div class="other list1">
				<h3 class="color1" style="padding-left: 0;font-weight: bold;">Những tin khác:</h3>
				<ul class="list-simple">
					{foreach from = $other item = item name = item}
						<li class="list-simple-item"><span class="glyphicon glyphicon-hand-right"></span>&nbsp;&nbsp;<a href="{$item.page_url}" >{$item.title}</a></li>
					{/foreach}
				</ul>
			</div>
		{/if}
		<div class="clear"></div>
	</div>
</div>
{php}
	loadModule("tool", 'fbfeedback');
{/php}