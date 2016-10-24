{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#province#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#province#}</li>
</ul>
<form class="form-horizontal" method="post" action="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&task={$smarty.get.task}&filter_title={$smarty.get.filter_title}&per_page={$smarty.get.per_page}&page={$smarty.get.page}" role="form">
	<input type="hidden" name="id" value="{$detail.id}">
  <div class="form-group">
    <label for="inputProvince" class="col-sm-2 control-label">{#province#} <span class="text-red">(*)</span></label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="inputProvince" placeholder="{#province#}" name="name" value="{$detail.name}">
    </div>
  </div>
  <div class="form-group">
	<label for="inputProvince" class="col-sm-2 control-label">{#status#}</label>
	<div class="col-sm-3">
		<select name="type" class="form-control">
			<option value="Tỉnh" {if $detail.type == 'Tỉnh'}selected{/if}>{#province#}</option>
			<option value="Thành Phố"  {if $detail.type == 'Thành Phố'}selected{/if}>{#city#}</option>
		</select>
	</div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-5">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
      <button type="button" class="btn btn-default back">{#back#}</button>
    </div>
  </div>
</form>