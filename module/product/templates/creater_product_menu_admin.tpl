
{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#product_type#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#product_type#}</li>
</ul>
<form class="form-horizontal form-admin" method="post" action="{$url_action}" role="form">
	<input type="hidden" name="id" value="{$detail.id}">
	<div class="form-group">
		<label for="product_type_id" class="col-sm-2 control-label">{#product_type#}</label>
		 <div class="col-sm-5">
			<select class="form-control" name="product_type_id" id="product_type_id" required>
				<option value="">----- {#select#} {#product_type#} -----</option>
				{html_options options=$parent selected=$detail.product_type_id}
			</select>
		</div>
	</div>

	 <div class="form-group clearfix">
		<label for="title" class="col-sm-2 control-label">
			{#avatar#}<span class="text-red">(*)</span>
		</label>
		<div class="col-sm-8">
			<div class="avatar-list clearfix" data-image-limit="2">
				{if $image_list}
					{foreach $image_list as $image}
						<div class="avatar-item" role="avatar-item">
						<button type="button" class="close"><span aria-hidden="true">×</span></button>
							<img src="{$image.url}" />
							<input type="hidden" name="avatar_id[]" value="{$image.id}" />
							<input type="hidden" name="avatar_position[]" value="{$image.position}" />
						</div>
				{/foreach}
				{/if}
			</div>
			<button type="button" class="btn btn-default select-avatar">Chọn thêm ảnh</button>
			<span class="help-block">Bạn được phép nhập 2 ảnh</span>
		</div>
	</div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="button" class="btn btn-default product" data-toggle="modal" data-target="#myModal">{#selectproduct#}</button>
	  <ul id="related_link" style="width: 400px; margin: 10px 0 0; " class="list-group">
		{if $detail}
			<li id="link_{$detail.product_id}" class="list-group-item">
				<span class="badge" onclick="deleteRelatedLink(this)">Xóa</span>
				{$detail.title}
				<input type="hidden" name="attribute_id[]" class="clear"  value="{$detail.product_id}" />
			</li>
		{/if}
	  </ul>
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
        <h4 class="modal-title" id="myModalLabel">{#selectproduct#}</h4>
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
