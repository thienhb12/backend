<div class="box box-attribute">
	{foreach $search_by_attribute as $attr}
		<h2 class="cate-title">{$attr.name}</h2>
		<div class="box-body">
			<ul>
			   {foreach $attr.source as $item}
					<li><span class="attribute {$item.active}" data-index="{$attr@index}" data-attr-id="{$attr.id}" data-attr-val="{trim($item.val)}"><i class="glyphicon glyphicon-{$item.active}"></i>
					{$item.val}</span></li>
			   {/foreach}
		   </ul>
		</div>
	{/foreach}
</div>