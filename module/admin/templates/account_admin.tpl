{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#accounts#}</li>
</ul>
<form class="form-horizontal" method="post" action="{$action_url}" role="form" data-toggle="validator">
	<input type="hidden" name="id" value="{$detail.id}">
	<div class="form-group">
		<label for="inputProvince" class="col-sm-2 control-label">{#username#}</label>
		<div class="col-sm-5">
		  <input type="text" pattern="{#pattern_username#}" class="form-control" required id="username" readonly placeholder="{#username#}" name="username" value="{$detail.username}">
		  <span class="help-block with-errors">{#required_username#}</span>
		</div>
	</div>
    <div class="form-group">
	<label for="type" class="col-sm-2 control-label">{#fullname#}</label>
	<div class="col-sm-5">
		 <input type="text" class="form-control" required id="fullname" placeholder="" name="fullname" value="{$detail.fullname}">
		 <span class="help-block with-errors"></span>
	</div>
  </div>
  <div class="form-group">
	<label for="type" class="col-sm-2 control-label">{#email#}</label>
	<div class="col-sm-5">
		 <input type="email" class="form-control" id="email" required  placeholder="" name="email" value="{$detail.email}">
		 <span class="help-block with-errors"></span>
	</div>
  </div>
  <div class="form-group">
	<label for="inputCongtac" class="col-sm-2 control-label">{#status#}</label>
	<div class="col-sm-3">
		<select name="status" readonly id="inputCongtac" class="form-control">
			<option value="1"  {if $detail.Status == '1'}selected{/if}>{#public_all#}</option>
			<option value="0" {if $detail.Status == '0'}selected{/if}>{#unpublic_all#}</option>
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