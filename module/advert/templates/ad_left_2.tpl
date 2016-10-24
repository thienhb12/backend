<div class="ad_left_2" style="margin-bottom:10px;">
	{if $advert[0].type=='image'}
		<a href="{$advert[0].link}" title="{$advert[0].name}" target="_blank"><img src="{$advert[0].photo}" alt="{$advert[0].name}" height="90" width="670" border="0" /></a>
	{else}
		<embed src="{$advert[0].photo}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="670" height="90"></embed>
	{/if}
</div>