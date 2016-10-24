<div class="ad_rigth_2">
	{foreach item=item from=$advert name=advert}
		 {if $item.type=='image'}
			<a href="{$item.link|default:''}" title="{$item.name}" target="_blank">
				<img src="{$item.photo}" alt="{$item.name}" width="300" border="0" style="margin-bottom:5px;" />
			</a>
		{else}
			<embed style="margin-bottom:5px;" src="{$item.photo}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="300" height="{$item.height|default:200}"></embed>
		{/if}
	{/foreach}
</div>