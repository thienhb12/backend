{php}
	loadModule("header");
{/php}
<div id="body" class="home main">
	{php}
		loadModule($_GET['mod'],$_GET['task']);
	{/php}
	<div class="clear"></div>
</div>
{php}
	loadModule("footer");
{/php}