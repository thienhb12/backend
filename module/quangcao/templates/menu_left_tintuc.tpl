<div class="box">
	<div class="inner menu-left">
		  <h3 class="color11"><a href="/tin-tuc/"><span>Tin tức</span></a></h3>
		  <ul class="list1">
				{foreach from = $cates item = cate name = cate}
					<li>
						{if $cate.parent_id > 0}
							&nbsp;&nbsp;&nbsp;&nbsp;<a href="{$cate.page_url}">{$cate.name}</a>
						{else}
							<a href="{$cate.page_url}">{$cate.name}</a>
						{/if}
					</li>
				{/foreach}
		  </ul>
	</div>
</div>
