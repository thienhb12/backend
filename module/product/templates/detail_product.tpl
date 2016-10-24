{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb"><li><a href="/">{#home#}</a></li>{$str_nav}</ul>
<article id="article">
	<div id="product-detail" class="clearfix">
		<div class="col-1 clearfix">
			
			<div class="thumb-list">
				{if count($image_list) > 0}
					{foreach $image_list as $image}
						<img src="{$image.url}" alt="{$image.description}" />
					{/foreach}
				{else}
					<img src="{$detail.avatar}" alt="{$detail.title}" />
				{/if}
				
			</div>

			
			<div class="avatar">
				<img src="{$detail.avatar}" alt="{$detail.title}" />
			</div>
		</div>
		<div class="col-2">
			<h1 class="title">{$detail.title}</h1>
			<div class="code">Mã sản phẩm: {$detail.code}</div>
			<div class="price">Giá: {$detail.sale_price|number_format:0:".":","} VNĐ</div>
			

			{if $detail.price > $detail.sale_price}
				<div class="old_price">Giá cũ: <del>{$detail.price|number_format:0:".":","} VNĐ</del></div>
				<div class="old_price">Tiết kiệm: {(($detail.price - $detail.sale_price)*100/$detail.price)|@floor}%</div> 
			{/if}  
			<a href="/cart/{$detail.id}/dat-hang.html"><button class="btn btn btn-warning btn-lg cart">Mua ngay</button></a>
		</div>
	</div>
	
	<div class="content clearfix">
		<div class="col-1">
			<div id="content">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#tab-thong-tin-chung" role="tab" data-toggle="tab">Nội dung</a></li>
					{foreach $list_step as $item}
						<li role="presentation"><a href="#tab{$item.id}" role="tab" data-toggle="tab">{$item.name}</a></li>
					{/foreach}
				</ul>
				<div class="tab-content">
					<!--- TAB THONG TIN ATTRIBUTE BEGIN--->
					{foreach $list_step as $step_item}
						 <div role="tabpanel" class="tab-pane" id="tab{$step_item.id}">
							{foreach $list_attr as $attr_item}
								{if $attr_item.step_id == $step_item.id}
								<div class="form-group clearfix">
									<label for="input_{$step_item.id}_{$attr_item.id}" class="col-sm-2 control-label">{$attr_item.name}</label>
									<div class="col-sm-5">{$attr_item.value}</div>
								</div>
								{/if}
							{/foreach}
						 </div>
					{/foreach}
				<!--- TAB THONG TIN ATTRIBUTE END--->
					<div role="tabpanel" class="tab-pane active" id="tab-thong-tin-chung">
						{$detail.content}

						{php}
							loadModule("tool", 'fbfeedback');
						{/php}
					</div>
				</div>
			</div>
			
		</div>
		<div class="col-2">
			<div class="brandProduct">
				<ul >
					{foreach $other as $product}
						<li style="border:1px solid #f4f4f4;" class="{if $product@iteration is div by 4}last{/if}">
							<div class="smallLabel saleLabel"></div>                    
							<a href="{$product.page_url}">
								<div>
									<img class="lazy" src="/common/image/grey.gif" data-original="{$product.avatar}"  alt="{$product.title}" >
								</div>
								<h3 class="productName">{$product.title}</h3>
								<span class="productPrice">{$product.sale_price|number_format:0:".":","} VNĐ</span>
							</a>
						</li>
					{/foreach}
				</ul>
			</div>
		</div>
	</div>
</article>