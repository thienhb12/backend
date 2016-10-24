{if count($tags) > 0}
<ul class="list-tap">
	{foreach from = $tags item = tag name = tag}
		<li>
			{if $smarty.foreach.tag.first}<span class="color2">Từ khóa: </span>{/if}
			<a href="{$tag.page_url}" ><span class="label {$color[{0|rand:4}]}">{$tag.name}</span></a>
		</li>
	{/foreach}
</ul>
{/if}
