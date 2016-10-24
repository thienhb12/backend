{php}
	loadModule("header");
{/php}
<div id="body" class="main home-page">
	<div>
		{php}
			loadModule("tintuc", 'hot_news');
		{/php}
		<div class="clear"></div>
	</div>
		{php}
			loadModule("clip","hot_clip");
			loadModule("tintuc","vietnam", 160);
			loadModule("tintuc","c1", 164);
			loadModule("tintuc","anh", 162);
			loadModule("tintuc","duc", 161);
			loadModule("tintuc","taybannha", 227);
			loadModule("tintuc","italy", 165);
			loadModule("tintuc","phap", 253);
			loadModule("tintuc","tinthethao", 126);
			loadModule("tintuc","hautruong", 254);
	{/php}
	<div class="clear"></div>
</div>
{php}
	loadModule("footer");
{/php}
