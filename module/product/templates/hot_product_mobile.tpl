<div class="container">
<div class="items-list clearfix">
{foreach $product_hot_list as $product}
	<div class="item home{if $product@iteration is div by 2}_last{/if}">
		{if $product.price > $product.sale_price}
			<div class="smallLabel saleLabel">{(($product.price - $product.sale_price)*100/$product.price)|@floor}%</div> 
		{/if}                      
		<a href="{$product.page_url}">
			<div>
				<img class="lazy" src="/common/image/grey.gif" data-original="{$product.avatar}"  alt="{$product.title}" >
			</div>
			<h3 class="productName">{$product.title}</h3>
			<span class="productPrice">{$product.sale_price|number_format:0:".":","} VNĐ</span>
		</a>
	</div>
	{if $product@iteration is div by 2}<div class="clearfix"></div>{/if}
{/foreach}
</div>
</div>