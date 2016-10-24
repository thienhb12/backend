<ul class="list-group" id="list-step">
	{foreach $itemsAttribute as $item}
	  <li class="list-group-item" data-id="{$item.id}">
		<span class="badge btn btn-default ">↓</span>
		{$item.name}
	  </li>
	{/foreach}
</ul>
{if $num_rows > $limit}
	<div class="paging">
		{$num_rows|page:$limit:$smarty.get.page:$paging_path}
	</div>
{/if}

