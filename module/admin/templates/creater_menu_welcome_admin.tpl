{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#menu#}</li>
</ul>
<form class="form-horizontal" method="post" action="{$action_url}"  enctype="multipart/form-data" role="form">
	<input type="hidden" name="id" value="{$detail.id}">
	<div class="form-group">
		<label for="inputProvince" class="col-sm-2 control-label">{#title#}</label>
		<div class="col-sm-5">
		  <input type="text" class="form-control" required id="inputProvince" placeholder="{#title#}" name="title" value="{$detail.title}">
		</div>
	</div>
	<div class="form-group">
		<label for="title_en" class="col-sm-2 control-label">{#title_en#}</label>
		<div class="col-sm-5">
		  <input type="text" class="form-control" required id="title_en" placeholder="{#title_en#}" name="title_en" value="{$detail.title_en}">
		</div>
	</div>
	<div class="form-group">
		<label for="avatar" class="col-sm-2 control-label">{#avatar#}</label>
		 <div class="col-sm-5" class="avatar-thumbnail" style="position: relative;">
			{if $detail.avatar != ''}
			<img src="{$detail.avatar}" alt="{#avatar#}" class="img-thumbnail" style="width: 200px;"><button type="button" class="close" aria-label="Close" style="position: absolute; left: 200px; top: 0; opacity: 0.8"><span aria-hidden="true">&times;</span></button>
			<input type="hidden" id="avatar" class="form-control" name="avatar" value="{$detail.avatar}" />
			{/if}
			
			<input type="file" id="avatar" name="avatar" />
		</div>
	</div>
   <div class="form-group">
	<label for="type" class="col-sm-2 control-label">{#link#}</label>
	<div class="col-sm-5">
		 <input type="text" class="form-control" required id="link" placeholder="" name="link" value="{$detail.link}">
	</div>
  </div>
  <div class="form-group">
	<label for="inputCongtac" class="col-sm-2 control-label">{#status#}</label>
	<div class="col-sm-3">
		<select name="status" id="inputCongtac" class="form-control">
			<option value="1"  {if $detail.status == '1'}selected{/if}>{#public_all#}</option>
			<option value="0" {if $detail.status == '0'}selected{/if}>{#unpublic_all#}</option>
		</select>
	</div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
      <button type="button" class="btn btn-default back">{#back#}</button>
    </div>
  </div>
</form>


