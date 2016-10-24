<div class="box lastest">
	<div class="box-body">
		<div class="list-semi">
			{foreach $items as $item}
				<div class="item">
					<a href="{$item.page_url}" class="avatar">
						<img src="{$item.avatar|replace:'/original/':'/normal/'}" alt="{$item.title}"  />
						<h3>{$item.title}</h3>
					</a>
					<div class="clear"></div>
				</div>
			{/foreach}
		</div>
	</div>
</div>