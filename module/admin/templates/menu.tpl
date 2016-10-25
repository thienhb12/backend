<div class="sidebar sidebar-main">
	<div class="sidebar-content">

		<!-- Main navigation -->
		<div class="sidebar-category sidebar-category-visible">
			<div class="category-content no-padding">
				<ul class="navigation navigation-main navigation-accordion">
					<!-- Main -->
					<li class="navigation-header"><span>Main</span> <i class="icon-menu" title="" data-original-title="Main pages"></i></li>
					<li><a href="index.html"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
					{foreach $menuItems as $item}
						<li class="{$subitem.selected}">
							<a href="?mod=admin&{$item.Link}" class="has-ul"><i class="icon-stack2"></i> <span>{$item.Name}</span></a>
							{if count($item.submenu) > 0}
								<ul >
									{foreach $item.submenu as $subitem}
										{if substr($subitem.Link,0,7) neq 'http://'}
											<li class="{$subitem.selected}"><a href="?mod=admin&{$subitem.Link}">{$subitem.Name}</a></li>
										{else}
											<li class="{$subitem.selected}"><a href="{$subitem.Link}">{$subitem.Name}</a></li>
										{/if}
									{/foreach}
								</ul>
							{/if}
						</li>
					{/foreach}
				</ul>
			</div>
		</div>
		<!-- /main navigation -->

	</div>
</div>