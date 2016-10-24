<div class="box box-product-type">
	<h2 class="cate-title">Danh mục</h2>
	<ul class="product-type-list">
		<li class="item level-0 {if $detail.id == $smarty.get.id}active{/if} "><a href="{$detail.page_url}">{$detail.name}</a></li>
		{foreach $sub_cates as $item}
			<li class="item level-{$item.level} {if $item.id == $smarty.get.id}active{/if} "><a href="{$item.page_url}">{$item.name}</a></li>
		{/foreach}
	</ul>
</div>