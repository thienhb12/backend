{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li>{#reset_password#}</li>
</ul>
<form class="form-horizontal" method="post" action="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&task={$smarty.get.task}&filter_title={$smarty.get.filter_title}&per_page={$smarty.get.per_page}&page={$smarty.get.page}&sys={$smarty.get.sys}&id={$smarty.get.id}" role="form" data-toggle="validator">
   <div class="form-group">
	<label for="type" class="col-sm-2 control-label">{#new_password#}</label>
	<div class="col-sm-5">
		 <input type="password" data-minlength="8" class="form-control" required id="newpassword" placeholder="" name="newpassword" value="" data-minlength-error="{#error_minlength_password#}" data-password-error="{#required_password#}">
		 <span class="help-block with-errors">{#required_password#}</span>
	</div>
  </div>
   <div class="form-group">
	<label for="type" class="col-sm-2 control-label">{#confirm_password#}</label>
	<div class="col-sm-5">
		 <input type="password" class="form-control" required id="cfpassword" placeholder="" name="cfpassword" value="" data-match="#newpassword" data-match-error="{#error_confirm_password#}">
		<span id="helpBlock" class="help-block with-errors"></span>
	</div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
      <button type="button" class="btn btn-default back">{#back#}</button>
    </div>
  </div>
</form>