{config_load file=$smarty.session.lang_file}
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">STDQ.vn</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
		{foreach $parent_menu as $menu_item}
			{if is_array($menu_item.submenu) && count($menu_item.submenu) > 0}
			<li class="dropdown">
			  <a href="{$menu_item.page_url}" class="dropdown-toggle animate " data-toggle="dropdown">{$menu_item.title} <span class="caret"></span></a>
			  <ul class="dropdown-menu" role="menu">
				{foreach $menu_item.submenu as $submenu_item}
					<li><a href= "{$submenu_item.page_url}">{$submenu_item.title}</a></li>
				{/foreach}
			  </ul>
			</li>
			{else}
				<li class="menu-item menu-{$menu_item.id}" data-id="{$menu_item.id}">
					<a href="{$menu_item.page_url}">{$menu_item.title}</a>
				</li>
			{/if}
		{/foreach}      
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>