<div style=" width:240px; height:auto; float:left; padding-bottom:5px; text-align:center">
	{foreach item=item from=$advert name=advert}
    {if $item.Type=='image'}
    <a href="{$item.Link}" title="{$item.Name}" target="_blank"><img src="{$item.Photo}" alt="{$item.Name}" width="240" border="0" style="margin-top:5px;"></a>
    {else}
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="240" height="{$item.Height|default:200}" style="margin-top: 10px;">
              <param name="movie" value="{$item.Photo}" />
              <param name="quality" value="high" />
              <embed src="{$item.Photo}" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="240" height="{$item.Height|default:200}"></embed>
            </object>
    {/if}
    {/foreach}
</div>