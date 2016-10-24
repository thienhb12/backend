{php}
	loadModule("header");
{/php}
<div id="body" class="channel main">
	<div class="col-1">
		{php}
			if(!$_GET['task']){
				loadModule("channel","highlights");
				loadModule("channel","homeleft");
			}
			else {
				if ($_GET['task'] == 'list') loadModule("channel","highlights");

				loadModule($_GET['mod'],$_GET['task']);
			}
		{/php}
	</div>
	<div class="col-2">
			{php}
				loadModule("channel","homeright" );
				loadModule("advert","ad_right_1");
				loadModule("tintuc", 'tinmoi');
				loadModule("tool", 'likebox');
			{/php}
	</div>
	<div class="clear"></div>
</div>
{php}
	loadModule("footer");
{/php}
