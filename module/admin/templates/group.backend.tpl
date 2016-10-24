{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&sys={if $smarty.get.sys}true{/if}">{#list#} {#accounts#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#accounts#}</li>
</ul>
<form class="form-horizontal" method="post" action="{$action_url}" role="form" data-toggle="validator">
	<input type="hidden" name="id" value="{$detail.id}">
    <div class="form-group">
	<label for="name" class="col-sm-2 control-label">{#name#}</label>
	<div class="col-sm-5">
		 <input type="text" class="form-control" required id="name" placeholder="" name="name" value="{$detail.name}">
		 <span class="help-block with-errors"></span>
	</div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		{$roll_list}
    </div>
  </div>
  <div class="form-group">
	<label for="inputCongtac" class="col-sm-2 control-label">{#status#}</label>
	<div class="col-sm-3">
		<select name="status" id="inputCongtac" class="form-control">
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


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">{#selectProvince#}</h4>
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