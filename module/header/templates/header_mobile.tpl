{config_load file=$smarty.session.lang_file}
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{$aPageinfo.title}</title>
	<meta name="robots" content="index, follow" />
	<meta name="keywords" content="{$aPageinfo.keyword}" />
	<meta name="description" content="{$aPageinfo.description}" />
	<meta property="og:title" content="{$aPageinfo.title}"/>
	<meta property="og:description" content="{$aPageinfo.description}"/>
	{if $aPageinfo.avatar}<meta property="og:image" content="{$aPageinfo.avatar}"/>{/if}
	{if $aPageinfo.page_url}<meta property="og:url" content="{$aPageinfo.page_url}"/>{/if}
	<meta property="og:type" content="website" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google-site-verification" content="NRsbDsoh63AdqS2mFJkYCKYGWzPSKPrJEEaVsgm2PrE" />
	<meta property="og:site_name" content="stdq.vn" />
	<link href="{$smarty.const.SITE_URL}common/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="{$smarty.const.SITE_URL}favicon.ico" type="image/x-icon" />
	<link href="{$smarty.const.SITE_URL}/common/css/mobile.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div id="header">
		{php}
			loadModule("header", 'menu');
		{/php}
	</div>