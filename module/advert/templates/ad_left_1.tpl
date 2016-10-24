<div class="ad_left_1" style="margin-bottom:10px;">
	{if $advert[0].type=='image'}
			<img src="/upload/advert/5121_item_257.gif" alt="{$advert[0].name}" height="90" width="670" border="0" />
	{else}
		<embed src="{$advert[0].photo}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="670" height="90"></embed>
	{/if}
</div>