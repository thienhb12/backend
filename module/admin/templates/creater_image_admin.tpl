{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#image#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#product_type#}</li>
</ul>
<form class="form-horizontal form-admin" method="post" action="{$url_action}" role="form" enctype="multipart/form-data">
	<input type="hidden" name="id" value="{$detail.id}">
	<div class="form-group">
		<label for="avatar" class="col-sm-2 control-label">{#avatar#}</label>
		 <div class="col-sm-5" class="avatar-thumbnail" style="position: relative;">
			{if $detail.name != ''}
			<img src="{$detail.url}" alt="{#avatar#}" class="img-thumbnail" style="width: 200px;"><button type="button" class="close" aria-label="Close" style="position: absolute; left: 200px; top: 0; opacity: 0.8"><span aria-hidden="true">&times;</span></button>
			<input type="hidden" class="form-control" name="url" value="{$detail.url}" />
			{/if}
			
			<input type="file" id="avatar" name="avatar" />
		</div>
	</div>  
	<div class="form-group">
    <label for="description" class="col-sm-2 control-label">{#description#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="description" placeholder="{#description#}" name="description" value="{$detail.description}">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
      <button type="button" class="btn btn-default back" >{#back#}</button>
    </div>
  </div>
</form>