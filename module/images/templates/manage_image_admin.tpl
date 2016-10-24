{config_load file=$smarty.session.lang_file}
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<title>{#system_name#}</title>
	<link rel="stylesheet" href="/common/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/common/bootstrap/css/bootstrap-datetimepicker.min.css">
	<link href="/common/admin/admin.css" rel="stylesheet">
	<style type="text/css">
		{literal}
		.form-horizontal .row,
		.form-horizontal .form-group {
			margin: 0;
		}
		.form-horizontal .row {
			margin-bottom: 15px;
		}
		{/literal}
	</style>
  </head>
  <body  marginheight="0" marginwidth="0">
	<div class="container" id="search-image">
		<h2>Quản lý ảnh</h2>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#tab-manage" role="tab" data-toggle="tab">{#select_image#}</a></li>
			<li role="presentation"><a href="#tab-upload" role="tab" data-toggle="tab">{#upload_image_to_server#}</a></li>
		</ul>

		<div class="tab-content" style="padding: 20px 0;" >
			<div role="tabpanel" class="tab-pane active" id="tab-manage">
				<form class="form-horizontal form-admin" method="post" action="{$url_action}" role="form">
					<div class="clearfix row">
						<div class="pull-left">
							<div class="form-group">
								<label for="from_date" class=" control-label">{#from_date#}</label>
								<div class="input-group date datetimepicker">
								  <input type="text" class="form-control" id="from_date"  name="from_date" value="{$from_date}" />
								  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
						</div>
						<div class="pull-right">
							<div class="form-group">
								<label for="to_date" class=" control-label">{#to_date#}</label>
								<div class='input-group date datetimepicker'>
								  <input type="text" class="form-control" id="to_date"  name="to_date" value="{$to_date}"   />
								  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="row clearfix">
						<div class="pull-left">
							<div class="form-group">
								<label for="filter" class=" control-label">{#keyword#}</label>
								  <input type="text" class="form-control" id="filter"  name="filter" value="{$smarty.post.filter}" placeholder="{#keyword#}"  />
							</div>
						</div>
						<div class="pull-right">
							<div class="form-group">
							  <button type="submit" class="btn btn-primary">{#search#}</button>
							</div>
						</div>
					</div>
				</form>

				<ul class="media-list">
				{foreach $images as $image}
				  <li class="media" style="border-bottom: 1px solid #ccc; padding-bottom: 15px;">
					<div class="media-left media-middle">
						<img class="media-object" src="{$image.url}" alt="Click vào ảnh để chọn" style="max-width: 120px; height: 120px; border: 1px solid #ccc;" data-id="{$image.id}">
					</div>
					<div class="media-body" style="vertical-align: middle;">{$image.description}</div>
				  </li>
				 {/foreach}
				</ul>

				{if $num_rows > $limit}
					<div class="paging">
						{$num_rows|page:$limit:$smarty.get.page:$paging_path}
					</div>
				{/if}
			</div>
			<div role="tabpanel" class="tab-pane" id="tab-upload">
				<form class="form-horizontal form-admin" method="post" action="{$action_url}" role="form" enctype="multipart/form-data">
					<div class="form-group row">
						<label for="avatar" class="control-label">{#avatar#}</label>
						<input type="file" id="avatar" name="avatar" />
					</div>  
					<div class="form-group row">
					<label for="description" class="control-label">{#description#}</label>
					  <input type="text" class="form-control" required id="description" placeholder="{#description#}" name="description" value="{$detail.description}">
				  </div>
				  <div class="form-group">
					  <button type="submit" class="btn btn-primary">{#submit#}</button>
					  <button type="button" class="btn btn-default back" >{#back#}</button>
				  </div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="/common/jquery/jquery-1.9.0.min.js"></script>
	<script type="text/javascript" src="/common/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/common/bootstrap/js/jquery.bootpag.min.js"></script>
	<script type="text/javascript" src="/common/bootstrap/js/bootstrap-datetimepicker.moment.js"></script>
	<script type="text/javascript" src="/common/bootstrap/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript">
	{literal}
		function getURLParams(name) {
			name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
			var regexS = "[\\?&]"+name+"=([^&#]*)";
			var regex = new RegExp( regexS );
			var results = regex.exec(window.location.href);

			if( results == null ) {
				return "";
			}
			else {
				return decodeURI(results[1].split('+').join(' '));
			}
		}
		$(document).ready(function() {
			$('.datetimepicker').datetimepicker({format: 'DD-MM-YYYY'});

			$('.media-object').click(function() {
				var obj_caller = window.opener;
				obj_caller.makeImage($(this).attr('src'), $(this).data('id'), getURLParams('position'));
				window.close();
			})
		});

	{/literal}
	</script>
  </body>
 </html>
