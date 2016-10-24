{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#town#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#town#}</li>
</ul>
<form class="form-horizontal" method="post" action="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&task={$smarty.get.task}&filter_title={$smarty.get.filter_title}&per_page={$smarty.get.per_page}&page={$smarty.get.page}" role="form">
	<input type="hidden" name="id" value="{$detail.id}">
	<div class="form-group">
		<label for="inputProvince" class="col-sm-2 control-label">{#district#}</label>
		 <div class="col-sm-5">
			<select class="form-control" id="district_id" name="district_id"  required>
				<option value="-1">----- {#select#} {#district#} -----</option>
				{html_options options=$district selected=$detail.district_id}
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="inputProvince" class="col-sm-2 control-label">{#town#}</label>
		<div class="col-sm-5">
		  <input type="text" class="form-control" required id="inputProvince" placeholder="{#DISTRICT#}" name="name" value="{$detail.name}">
		</div>
	</div>
   <div class="form-group">
	<label for="type" class="col-sm-2 control-label">{#type#}</label>
	<div class="col-sm-3">
		 <input type="text" class="form-control" required id="type" placeholder="" name="type" value="{$detail.type}">
	</div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{#submit#}</button>
      <button type="button" class="btn btn-default back">{#back#}</button>
    </div>
  </div>
</form>

