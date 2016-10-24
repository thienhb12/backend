{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb"><li><a href="/">{#home#}</a></li>{$str_nav}</ul>
<div class="list-product clearfix">
	<div class="brandProduct">
		<ul>
		{foreach $product_list as $product}
			<li style="border:1px solid #f4f4f4;" class="{if $product@iteration is div by 4}last{/if}">
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
			</li>
			{if $product@iteration is div by 4}<div class="clearfix"></div>{/if}
		{/foreach}
		</ul>
	</div>

	{if $num_rows > $limit}
		<div class="paging">
			{$num_rows|page:$limit:$smarty.get.page:$paging_path}
		</div>
	{/if}
</div>
