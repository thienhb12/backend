<div class="container clearfix">
	<div class="test_title"><a href="#">Sản phẩm nổi bật</a></div>
		<div class="box-cate-home cate-home-{$cate.id} clearfix">
			<div class="list-product clearfix">
				<div class="brandProduct">
					<ul>
					{foreach $product_hot as $product}
						<li style="border:1px solid #f4f4f4;">
							<div class="smallLabel saleLabel"></div>                    
							<a href="{$product.page_url}">
								<div>
									<img src="http://www.dangquangwatch.vn//upload/product_small/1731077072_Dong-ho-Epos-Swiss-3426.132.22.20.32.jpg"  title="{$product.title}" alt="YotaPhone YD201">
								</div>
								<h3 class="productName">Đồng hồ Stuhrling 686868</h3>
								<span class="productPrice">11,990,000 VNĐ</span>
							</a>
						</li> 	
						<li style="border:1px solid #f4f4f4;">
							<div class="smallLabel saleLabel"></div>                    
							<a href="{$product.page_url}">
								<div>
									<img src="http://nhansamviet.com/uploads/images/product/thumbs/6_MZ_1427507424.JPG"  title="{$product.title}" alt="YotaPhone YD201">
								</div>
								<h3 class="productName">Đồng hồ Stuhrling 686868</h3>
								<span class="productPrice">11,990,000 VNĐ</span>
							</a>
						</li> 	
					{/foreach}
					</ul>
				</div>
			</div>
		</div>
</div>
