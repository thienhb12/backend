<ul>
	<li class="first"><a href="/about/">{#ABOUT_COMPANY#}</a></li>
	{foreach from = $about item = item name = item}
		<li><a href="{$smarty.const.SITE_URL}{$mod}/{$item.id}/{$item.Name|remove_marks}.html" >{$item.Name}</a></li>
	{/foreach}
</ul>