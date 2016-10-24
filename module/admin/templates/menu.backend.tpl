{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&sys={$smarty.get.sys}">{#list#} {#categories#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#categories#}</li>
</ul>
<form class="form-horizontal form-admin" method="post" action="{$url_action}" role="form">
	<input type="hidden" name="id" value="{$detail.id}">
	<div class="form-group">
		<label for="lang_id" class="col-sm-2 control-label">{#language#}</label>
		 <div class="col-sm-3">
			<select class="form-control" name="lang_id" id="lang_id" required>
				<option value="">----- {#select#} {#language#} -----</option>
				{html_options options=$lang selected=$detail.lang_id}
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="parent_id" class="col-sm-2 control-label">{#categories#}</label>
		 <div class="col-sm-5">
			<select class="form-control" name="parent_id" id="parent_id">
				<option value="">----- {#select#} {#categories#} -----</option>
				{html_options options=$menu selected=$detail.parent_id}
			</select>
		</div>
	</div>
  <div class="form-group">
    <label for="title" class="col-sm-2 control-label">{#title#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="title" placeholder="" name="title" value="{$detail.title}">
    </div>
  </div>
  <div class="form-group">
    <label for="page_url" class="col-sm-2 control-label">{#url#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="page_url" placeholder="" name="page_url" value="{$detail.page_url}">
    </div>
  </div>
  <div class="form-group">
    <label for="title" class="col-sm-2 control-label">{#avatar#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="avatar" placeholder="" name="avatar" value="{$detail.avatar}">
    </div>
  </div>
  <div class="form-group">
	<label for="status" class="col-sm-2 control-label">{#status#}</label>
	<div class="col-sm-3">
		<select name="status" class="form-control">
			<option value="1"  {if $detail.status == '1'}selected{/if}>{#public_all#}</option>
			<option value="0" {if $detail.status == '0'}selected{/if}>{#unpublic_all#}</option>
		</select>
	</div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
      <button type="button" class="btn btn-default back" >{#back#}</button>
    </div>
  </div>
</form>