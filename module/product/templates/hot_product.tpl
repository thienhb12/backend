<div class="smartphonebrand">
	<div class="titlebrand">
		<div class="container">
			<a>Sản phẩm nổi bật</a>
		</div>
	</div>
	<div class="container clearfix">
		<div class="content_product">
			<div class="brandProduct">
				<ul>
					{foreach $product_hot_list as $product}
						<li style="border:1px solid #f4f4f4;" class="home{if $product@iteration is div by 5}_last{/if}">
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
						{if $product@iteration is div by 5}<div class="clearfix"></div>{/if}
					{/foreach}
			   </ul>       
			</div>
		 </div>
	</div>
</div>