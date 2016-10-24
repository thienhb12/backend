{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#file_media#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#file_media#}</li>
</ul>
<form class="form-horizontal form-admin" method="post" action="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&task={$smarty.get.task}&filter_title={$smarty.get.filter_title}&per_page={$smarty.get.per_page}&page={$smarty.get.page}&id={$smarty.get.id}" role="form"  enctype="multipart/form-data">
	<input type="hidden" name="id" value="{$detail.id}">
  <div class="form-group">
	<label for="type" class="col-sm-2 control-label">{#type#}</label>
	<div class="col-sm-4">
		<select name="type" class="form-control">
			<option value="video"  {if $detail.type == 'video'}selected{/if}>Video</option>
			<option value="audio" {if $detail.type == 'audio'}selected{/if}>Audio</option>
		</select>
	</div>
  </div>
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">{#name#}</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" required id="name" placeholder="{#name#}" name="name" value="{$detail.name}">
    </div>
  </div>
<div class="form-group">
	<label for="file_media" class="col-sm-2 control-label">{#file_media#}</label>
	 <div class="col-sm-4" class="" style="position: relative;">
		 <input type="file" id="file_media" name="file_media" />
		{if $detail.url != ''}
			<div class="input-group">
			<input type="text" id="file_media" class="form-control" name="file_media" value="{$detail.url}"  readonly/><span class="input-group-addon delete-input">{#delete#}</span>
			</div>
		{/if}
	</div>
</div>
<div class="form-group">
	<label for="avatar" class="col-sm-2 control-label">{#avatar#}</label>
	 <div class="col-sm-4" class="" style="position: relative;">
		<input type="file" id="avatar" name="avatar" />
		{if $detail.avatar != ''}
			<div class="input-group">
			<input type="text" id="avatar" class="form-control" name="avatar" value="{$detail.avatar}"  readonly/><span class="input-group-addon delete-input">{#delete#}</span>
			</div>
		{/if}
	</div>
</div>
  <div class="form-group">
	<label for="inputProvince" class="col-sm-2 control-label">{#status#}</label>
	<div class="col-sm-4">
		<select name="status" class="form-control">
			<option value="1"  {if $detail.status == '1'}selected{/if}>{#public_all#}</option>
			<option value="0" {if $detail.status == '0'}selected{/if}>{#unpublic_all#}</option>
		</select>
	</div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
	  <input type="submit" name="saveback" class="btn btn-primary" value="{#saveback#}" />
      <button type="button" class="btn btn-default back" >{#back#}</button>
    </div>
  </div>
</form>