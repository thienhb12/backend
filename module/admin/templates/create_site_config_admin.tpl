{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#menu#}</li>
</ul>
<form class="form-horizontal" method="post" action="{$action_url}"  enctype="multipart/form-data" role="form">
	<input type="hidden" name="id" value="{$detail.id}">
	<div class="form-group">
		<label for="inputProvince" class="col-sm-2 control-label">{#name#}</label>
		<div class="col-sm-5">
		  <input type="text" class="form-control" required id="inputProvince" placeholder="{#name#}" name="name" value="{$detail.name}">
		</div>
	</div>
	<div class="form-group">
		<label for="inputProvince" class="col-sm-2 control-label">{#value#}</label>
		<div class="col-sm-5">
		  <input type="text" class="form-control" required id="inputProvince" placeholder="{#value#}" name="value" value="{$detail.value}">
		</div>
	</div>
	<div class="form-group">
		<label for="inputProvince" class="col-sm-2 control-label">{#description#}</label>
		<div class="col-sm-5">
		  <input type="text" class="form-control" required id="inputProvince" placeholder="{#description#}" name="description" value="{$detail.description}">
		</div>
	</div>


  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
      <button type="button" class="btn btn-default back">{#back#}</button>
    </div>
  </div>
</form>
