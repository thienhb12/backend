<div class="box hotnews">
	<div class="box-body">
		<div class="list-item" id="player-hotnews">
			<div class="cycle-prev cycle-control glyphicon glyphicon-chevron-right"></div>
			<div class="cycle-next cycle-control glyphicon glyphicon-chevron-right"></div>
			{foreach $items as $item}
				{if $item@first}
					<div class="item">
				{else}
					<div class="item">
				{/if}
					<a href="{$item.page_url}" class="avatar">
						{if $item@first}
							<img src="{$item.avatar|replace:'/normal/':'/original/'}" alt="{$item.title}"  />
							<div class="detail">
							<h1>{$item.title}</h1>
						</div>
						{else}
							<img data-src="{$item.avatar|replace:'/normal/':'/original/'}" alt="{$item.title}" src=""  />
							<div class="detail">
							<h2>{$item.title}</h2>
						</div>
						{/if}
						
					</a>
				</div>
			{/foreach}
		</div>
	</div>
</div>