{config_load file=$smarty.session.lang_file}
<div class="zone-3">
	<div class="container clearfix" style="position: relative;">
		<ul id="main-menu" class="clearfix">
		{foreach $parent_menu as $menu_item}
			<li class="menu-item menu-{$menu_item.id}" data-id="{$menu_item.id}">
				<a href="{$menu_item.page_url}">{$menu_item.title}</a>
			</li>
		{/foreach}
		</ul>
		{foreach $parent_menu as $menu_item}
			{if is_array($menu_item.submenu) && count($menu_item.submenu) > 0}
			<div class="submenu-content clearfix submenu-{$menu_item.id}"  data-id="{$menu_item.id}">
				{$count = 0}
				{$total_menu_item = 10}
				{foreach $menu_item.submenu as $submenu_item}
					{if $count <5}
					<ul>
						<li class="submenu-item level-1"><a href= "{$submenu_item.page_url}">{$submenu_item.title}</a></li>
						{$count = $count + 1}
						{if is_array($submenu_item.submenu_level_1)}
							{foreach $submenu_item.submenu_level_1 as $submenu_item_level_2}
								{if !$submenu_item_level_2@first && $submenu_item_level_2@index % $total_menu_item == 0 && $count <5}
									</ul><ul>
								{/if}
								{if $count <=5}
									<li class="submenu-item level-{$submenu_item_level_2.level} {if !$submenu_item_level_2@first && $submenu_item_level_2@index % $total_menu_item == 0 && $count <5}submenu-first{/if}"><a href= "{$submenu_item_level_2.page_url}">{$submenu_item_level_2.title}</a></li>
								{/if}
								{if !$submenu_item_level_2@first && $submenu_item_level_2@index % $total_menu_item == 0 && $count <5}
									{$count = $count + 1}
								{/if}
							{/foreach}
							</ul>
						{/if}
					{/if}
				{/foreach}
			</div>
			{/if}
		{/foreach}
	</div>
</div>
<div id="menu-over"></div>