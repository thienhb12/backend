{config_load file=$smarty.session.lang_file}
<html lang="en">
   <!-- Made by Tran Hoang Thien (thienhb12@gmail.com)-->
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{#page_title#}</title>
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="common/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/extras/animate.min.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/colors.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/style.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

  </head>  

  <body>
    <div id="loader-wrapper"> 
        <div id="loader"></div>        
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="navbar navbar-inverse">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php?mod=admin"><img src="common/assets/images/logo_light.png" alt=""></a>

        <ul class="nav navbar-nav visible-xs-block">
          <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
          <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
      </div>

      <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
          <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>

        <p class="navbar-text">
          <span class="label bg-success">Online</span>
        </p>

        <div class="navbar-right">
          <ul class="nav navbar-nav">
            <li class="dropdown dropdown-user">
              <a class="dropdown-toggle" data-toggle="dropdown">
                <img src="common/assets/images/demo/users/face11.jpg" alt="">
                <span>{$smarty.session.fullname}</span>
                <i class="caret"></i>
              </a>

              <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="?mod=admin&&amod=system_config&atask=account&sys=true"><i class="icon-user-plus"></i> {#accounts_info#}</a></li>
                <li><a href="?mod=admin&&amod=system_config&atask=account&task=change_password&sys=true"><i class="icon-lock2"></i> {#change_password#}</a></li>
                <li class="divider"></li>
                <li><a href="?mod=admin&amod=sys_login&atask=login&task=logout&sys=true"><i class="icon-switch2"></i> Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="page-container" style="min-height:218px">
      <div class="page-content">
