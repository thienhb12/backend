<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
  <li class="active"><a href="#docnhieunhat" role="tab" data-toggle="tab">Đọc nhiều nhât</a></li>
  <li><a href="#tinmoi" role="tab" data-toggle="tab">Tin mới</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
	 <div class="tab-pane" id="tinmoi">
		<div class="list-group">
		{foreach $items as $item}
			{if $item@index <= 10}
				{if $item@last}
					{$last = 'last'} 
				{/if}
				<a href="{$item.page_url}" class="list-group-item {$first} {$last}">
					<img src="{$item.avatar|replace:'/original/':'/normal/'}" alt="{$item.title}" class="avatar" />
					<h5 class="list-group-item-heading">{$item.title}</h5>
					<div class="clear"></div>
				</a>
			{/if}
		{/foreach}
		</div>
	</div>
	<div class="tab-pane active" id="docnhieunhat">
		<div class="list-group">
		{foreach $mostread as $item}
			{if $item@index <= 10}
				{if $item@last}
					{$last = 'last'} 
				{/if}
				<a href="{$item.page_url}" class="list-group-item {$first} {$last}">
					<img src="{$item.avatar|replace:'/original/':'/normal/'}" alt="{$item.title}" class="avatar" />
					<h5 class="list-group-item-heading">{$item.title}</h5>
					<div class="clear"></div>
				</a>
			{/if}
		{/foreach}
		</div>
	</div>
</div>