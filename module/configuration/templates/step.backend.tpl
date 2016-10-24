{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#step#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#step#}</li>
</ul>

<form class="form-horizontal form-admin" method="post" action="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&task={$smarty.get.task}&filter_title={$smarty.get.filter_title}&per_page={$smarty.get.per_page}&page={$smarty.get.page}" role="form">
	<input type="hidden" name="id" value="{$detail.id}">
  <div class="form-group">
    <label for="inputProvince" class="col-sm-2 control-label">{#step#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="inputProvince" placeholder="{#step#}" name="name" value="{$detail.name}">
    </div>
  </div>
   <div class="form-group">
    <label for="label" class="col-sm-2 control-label">{#label#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="label" name="label" value="{$detail.label}">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="button" class="btn btn-default attribute" >{#selectAttribute#}</button>
	  <ul id="related_link" style="width: 400px; margin: 10px 0 0; " class="list-group">
		{foreach $list_attr_of_step as $attr}
			<li id="link_{$attr.id}" class="list-group-item">
				<span class="badge" onclick="deleteRelatedLink(this)">{#delete#}</span>
				{$attr.name}
				<input type="hidden" name="attribute_id[]" class="clear"  value="{$attr.id}" />
			</li>
		{/foreach}
	  </ul>
    </div>
  </div>
  <div class="form-group">
	<label for="inputCongtac" class="col-sm-2 control-label">{#status#}</label>
	<div class="col-sm-5">
		<select name="status" id="inputCongtac" class="form-control">
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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">{#selectAttribute#}</h4>
      </div>
      <div class="modal-body">
		<form class="navbar-form navbar-right" role="search">
		  <div class="form-group">
			<input type="text" class="form-control search" placeholder="{#search#}">
		  </div>
		  <button type="submit" class="btn btn-default btn-search">{#search#}</button>
		</form>
		<div class="clearfix"></div>
			<ul class="list-group" id="list-step"></ul>
		 <div id="pager"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{#close#}</button>
      </div>
    </div>
  </div>
</div>