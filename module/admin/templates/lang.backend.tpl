{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&sys={if $smarty.get.sys}true{/if}">{#list#} {#task#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#task#}</li>
</ul>
<form class="form-horizontal" method="post" action="{$action_url}" role="form" data-toggle="validator">
	<input type="hidden" name="id" value="{$detail.id}">
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">{#name#}</label>
		<div class="col-sm-5">
		  <input type="text" class="form-control" required id="name" name="name" value="{$detail.name}">
		  <span class="help-block with-errors"></span>
		</div>
	</div>
    <div class="form-group">
	<label for="task" class="col-sm-2 control-label">{#task#}</label>
	<div class="col-sm-5">
		 <textarea style="height: 500px;" class="form-control" required id="content_config_file" placeholder="" name="content_config_file">{$detail.content_config_file}</textarea>
		 <span class="help-block with-errors"></span>
	</div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
      <button type="button" class="btn btn-default back">{#back#}</button>
    </div>
  </div>
</form>