<div class="home-right">
	<h2 class="cate-title"><a href="/news/c-110/thong-tin-khuyen-mai/">Thông tin khuyến mại</a></h2>
	<ul class="list-simple">
		{foreach item=item from=$items name=item}
			<li><a href="{$item.page_url}" title="{$item.title}" >{$item.title}</a></li>
		{/foreach}
	</ul>
</div>
