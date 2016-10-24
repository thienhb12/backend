<div class="widget widget_text">
	<div class="box-header"><h3 class="color8"><span>Tin nổi bật</span></h3></div>
	<div class="box-body">
		<ul class="list-semi">
			{foreach from = $items item = item name = item}
				<li class="item">
					<table>
						<tr>
							<td><a href="{$item.page_url}" class="avatar"><img src="{$item.avatar}" alt="{$item.title}"  /></a></td>
							<td><a href="{$item.page_url}">{$item.title}</a></td>
						</tr>
					</table>
				</li>
			{/foreach}
		</ul>
	</div>
</div>

