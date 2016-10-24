{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#}  {#fieldInfo#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#fieldInfo#}</li>
</ul>
<form class="form-horizontal form-admin" method="post" action="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&task={$smarty.get.task}&filter_title={$smarty.get.filter_title}&per_page={$smarty.get.per_page}&page={$smarty.get.page}" role="form">
	<input type="hidden" name="id" value="{$detail.id}">
	 <div class="form-group">
		<label for="fieldType" class="col-sm-2 control-label">{#typeFieldInfo#}</label>
		 <div class="col-sm-5">
			<select class="form-control" id="fieldType" name="type">
			  <option value="text"{if $detail.type == 'text'} selected {/if}>Text</option>
			  <option value="select"{if $detail.type == 'select'} selected {/if}>Select</option>
			  <option value="radio"{if $detail.type == 'radio'} selected {/if}>Radio</option>
			  <option value="checkbox"{if $detail.type == 'checkbox'} selected {/if}>Checkbox</option>
			  <option value="textarea"{if $detail.type == 'textarea'} selected {/if}>Textarea</option>
			  <option value="coordinates"{if $detail.type == 'coordinates'} selected {/if}>{#coordinates#}</option>
			  <option value="datetime"{if $detail.type == 'datetime'} selected {/if}>DateTime</option>
			  <option value="yesnotext"{if $detail.type == 'yesnotext'} selected {/if}>Yes/No/Text</option>
			</select>
		</div>
	</div>
  <div class="form-group">
    <label for="inputFieldInfo" class="col-sm-2 control-label">{#fieldInfo#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="inputFieldInfo" placeholder="{#fieldInfo#}" name="name" value="{$detail.name}">
    </div>
  </div>
  <div class="form-group">
    <label for="label" class="col-sm-2 control-label">{#label#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="label" name="label" value="{$detail.label}">
    </div>
  </div>
   <div class="form-group">
    <label for="inputUnit" class="col-sm-2 control-label">{#unit#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="inputUnit" placeholder="{#unit#}" name="unit" value="{$detail.unit}">
    </div>
  </div>
   <div class="form-group">
    <label for="inputDescription" class="col-sm-2 control-label">{#description#}</label>
    <div class="col-sm-8">
      <textarea type="text" class="form-control" id="inputDescription" placeholder="{#description#}" name="description" >{$detail.description}</textarea>
    </div>
  </div>
  <div class="form-group">
    <label for="inputSource" class="col-sm-2 control-label">{#source#}</label>
    <div class="col-sm-8">
       <textarea class="form-control" id="inputSource" placeholder="{#source#}" name="source"  rows="3">{$detail.source}</textarea>
	    <p class="help-block">{#note_source#}</p>
    </div>
  </div>
  <div class="form-group">
   <label for="inputRequired" class="col-sm-2 control-label" style="padding-top: 0">{#required#}</label>
	<div class="col-sm-5">
	  <input type="checkbox" name="required" {if $detail.required == 1} checked {/if} id="inputRequired" class="control-label"/>
	</div>
  </div>
  <div class="form-group">
	<label for="inputProvince" class="col-sm-2 control-label">{#status#}</label>
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