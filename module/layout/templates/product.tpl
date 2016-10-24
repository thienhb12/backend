{php}
	loadModule("header");
{/php}
<div id="body" class="container product clearfix">
	{if $smarty.get.task == 'list' && !isMobile()}
		<div class="col-1">
			{php}
				loadModule('product', 'product_type');
				loadModule('product', 'required');
				loadModule('product', 'price');
			{/php}
		</div>
		<div class="col-2">
			{php}
				loadModule($_GET['mod'],$_GET['task']);
			{/php}
		</div>
	{else}
		{php}
			loadModule($_GET['mod'],$_GET['task']);
		{/php}
	{/if}
</div>
{php}
	loadModule("footer");
{/php}
