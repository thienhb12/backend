{php}
	loadModule("header");
{/php}
<div id="body" class="container product clearfix">
	<div class="col-1">
		{php}
			loadModule('product', 'product_type');
			loadModule('product', 'price');
			loadModule('product', 'required');
		{/php}
	</div>
	<div class="col-2">
		{php}
			#loadModule($_GET['mod'],$_GET['task']);
		{/php}
	</div>
</div>
{php}
	loadModule("footer");
{/php}
