<div class="newstop">
	<div class="bg">
		<div class="latest_news">
			<div class="pagy">
				<a id="car1-prev" class="prev" href="#"><</a>
				<div id="car1-pager" class="pager"></div>
				<a id="car1-next" class="next" href="#">></a>
				<div class="clear"></div>
			</div>
			<ul id="car1">
				{foreach from = $items item = item name = item}
					{if $smarty.foreach.item.index < 5}
						<li>
							<div class="avatar">
								<a href="{$item.page_url}">
									<img src="{$item.avatar|replace:'thumb/':''}" alt="{$item.title}" />
								</a>
								<div class="title"><a class="title-1" href="">{$item.title} </a></div>
							</div>
							<!--<div class="lead">{$item.lead}</div>-->
						</li>
					{/if}
				{/foreach}
			</ul>
		</div>
		<div class="hot-news">
			<div class="cate-title">Tin nổi bật</div>
			<ul>
				{foreach from = $items item = item name = item}
					{if $smarty.foreach.item.index == 5}
						<li class="first">
							<div class="title">
								<a class="title-1" href="{$item.page_url}">{$item.title}</a>
							</div>
							<a href="{$item.page_url}">
								<img class="avatar" src="{$item.avatar}" alt="{$item.title}" />
							</a>
							<div class="lead">{$item.lead}</div>
						</li>
					{/if}
					{if $smarty.foreach.item.index > 5}
						<li><a class="title" href="{$item.page_url}">{$item.title}</a></li>
					{/if}
				{/foreach}
			</ul>
		</div>
		<div class="clear">.</div>
	</div>
</div>