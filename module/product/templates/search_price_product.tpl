<div class="box box-price">
	<link href="{$smarty.const.SITE_URL}/common/bootstrap/slider/css/slider.css" rel="stylesheet" type="text/css" />
	<h2 class="cate-title">KHOẢNG GIÁ (VNĐ)</h2>
	<div class="box-body">
           <i>15.000 đ</i> ------------
		   <i>10.000.000 đ</i>
		   <input type="text" class="span2" value="" data-slider-min="5000" data-slider-max="100000000" data-slider-step="5000" data-slider-value="[{$smarty.get.fprice|default:'5000'},{$smarty.get.tprice|default:'100000000'}]" id="sl2" />
		<form class="form-inline" action="">
			<div class="row">
				<input type="text" class="form-control m-input-price" id="minPrice" value="{$smarty.get.fprice|default:'5000'}">
				<input type="text" class="form-control m-input-price" id="maxPrice" value="{$smarty.get.tprice|default:'100000000'}">
			</div>
			<div class="row text-right">
				<button type="button" class="btn btn-primary btn-filter-price"><i class="glyphicon glyphicon-search"></i></button>
			</div>
		</form>
	</div>
</div>