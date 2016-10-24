<div>
	{foreach item=item from=$items name=item}
		{if $item.type=='image'}
		<a href="{$item.Link}" title="{$item.name}" target="_blank"><img src="{$item.photo}" alt="{$item.name}" width="300" border="0" style="margin-bottom:5px"></a>
		{else}
			<embed src="{$item.photo}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="300" height="{$item.height|default:250}"></embed>
		{/if}
	{/foreach}
</div>