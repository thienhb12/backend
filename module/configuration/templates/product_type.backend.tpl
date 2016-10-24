{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#product_type#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#product_type#}</li>
</ul>
<form class="form-horizontal form-admin" method="post" action="{$url_action}" role="form">
	<input type="hidden" name="id" value="{$detail.id}">
	<div class="form-group">
		<label for="parent_id" class="col-sm-2 control-label">{#product_type#}</label>
		 <div class="col-sm-5">
			<select class="form-control" name="parent_id" id="parent_id">
				<option value="">----- {#select#} {#product_type#} -----</option>
				{html_options options=$parent selected=$detail.parent_id}
			</select>
		</div>
	</div>
  <div class="form-group">
    <label for="inputProvince" class="col-sm-2 control-label">{#name#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="inputProvince" placeholder="{#loaihinh#}" name="name" value="{$detail.name}">
    </div>
  </div>
  <div class="form-group">
    <label for="color" class="col-sm-2 control-label">{#color_picker#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control colorpicker" id="color" name="color" value="{$detail.color}">
    </div>
  </div>
  <div class="form-group">
    <label for="class_icon" class="col-sm-2 control-label">{#class_icon#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="class_icon" name="class_icon" value="{$detail.class_icon}">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="button" class="btn btn-default step" data-toggle="modal" data-target="#myModal">{#selectstep#}</button>
	  <ul id="related_link" style="width: 400px; margin: 10px 0 0; " class="list-group">
		{foreach $list_step_of_product as $step}
			<li id="link_{$step.id}" class="list-group-item">
				<span class="badge" onclick="deleteRelatedLink(this)">Xóa</span>
				{$step.name}
				<input type="hidden" name="attribute_id[]" class="clear"  value="{$step.id}" />
			</li>
		{/foreach}
	  </ul>
    </div>
  </div>
	<div class="form-group">
		 <div class="col-sm-offset-2 col-sm-10">
		  <div class="checkbox">
			<label>
			  <input type="checkbox" name="is_hot" {if $detail.is_hot}checked{/if}> {#hot#}
			</label>
		  </div>
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

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">{#selectstep#}</h4>
      </div>
      <div class="modal-body">
		<form class="navbar-form navbar-right" role="search">
		  <div class="form-group">
			<input type="text" class="form-control search" placeholder="Tìm kiếm">
		  </div>
		  <button type="submit" class="btn btn-default btn-search">Tìm</button>
		</form>
		<div class="clearfix"></div>
		<ul class="list-group" id="list-step"></ul>
		 <div id="pager"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>