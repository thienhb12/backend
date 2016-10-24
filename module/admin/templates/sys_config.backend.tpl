{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&sys={$smarty.get.sys}">{#list#} {#sys_config#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#sys_config#}</li>
</ul>

<form class="form-horizontal form-admin" method="post" action="{$action_url}" role="form"  enctype="multipart/form-data">
	<input type="hidden" name="id" value="{$detail.id}">
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">{#name#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" {if !$is_oops}readonly{/if} required id="name" name="name" value="{$detail.name}">
	  <span class="help-block with-errors"></span>
    </div>
  </div>
  <div class="form-group">
    <label for="value" class="col-sm-2 control-label">{#value#}</label>
    <div class="col-sm-5">
      <textarea type="text" class="form-control" required id="value" name="value" col="10">{$detail.value}</textarea>
	  <span class="help-block with-errors"></span>
    </div>
  </div>
  <div class="form-group">
    <label for="description" class="col-sm-2 control-label">{#description#}</label>
    <div class="col-sm-5">
      <textarea type="text" class="form-control" id="description" name="description" col="10">{$detail.description}</textarea>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
      <button type="button" class="btn btn-default back" >{#back#}</button>
    </div>
  </div>
</form>