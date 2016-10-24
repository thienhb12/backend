{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#ticket#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#ticket#}</li>
</ul>
<form class="form-horizontal form-admin" method="post" action="{$url_action}" role="form" id="product-form" enctype="multipart/form-data">
	<input type="hidden" id="product_id" name="id" value="{$detail.id}">
	 <div class="form-group">
		<label for="inputProvince" class="col-sm-2 control-label">{#product_type#}</label>
		 <div class="col-sm-5">
			<select class="form-control" name="product_type_id" id="product_type_id" {if $detail.product_type_id}disabled{/if} required>
				<option value="-1">----- {#select#} {#product_type#} -----</option>
				{html_options options=$list_product_type selected=$detail.product_type_id}
			</select>
		</div>
	</div>
	{if !$detail.product_type_id && !$smarty.get.product_type_id}
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="button" class="btn btn-primary" id="btn-next-attribute">{#next#}</button>
			</div>
		</div>
	{else}
	<div class="fieldAttribute">
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#tab-thong-tin-chung" role="tab" data-toggle="tab">{#thongtinchung#}</a></li>
			{foreach $list_step as $item}
				<li role="presentation"><a href="#tab{$item.id}" role="tab" data-toggle="tab">{$item.name}</a></li>
			{/foreach}
		</ul>

		<div class="tab-content" style="padding: 20px 0;" >

			<!--- TAB THONG TIN CHUNG BEGIN--->
			<div role="tabpanel" class="tab-pane active" id="tab-thong-tin-chung">
				<div class="form-group clearfix">
					<label for="title" class="col-sm-2 control-label">
						{#title#}<span class="text-red">(*)</span>
					</label>
					<div class="col-sm-5">
						 <input type="text" class="form-control" id="title" placeholder="" name="title" value="{$detail.title}"  maxlength="1000" />
					</div>
				</div>
				<div class="form-group clearfix">
					<label for="title" class="col-sm-2 control-label">
						{#avatar#}<span class="text-red">(*)</span>
					</label>
					<div class="col-sm-8">
						<div class="avatar-list clearfix" data-image-limit="5">
							{foreach $image_list as $image}
								<div class="avatar-item" role="avatar-item">
								<button type="button" class="close"><span aria-hidden="true">×</span></button>
									<img src="{$image.url}" />
									<input type="hidden" name="avatar_id[]" value="{$image.image_id}" />
									<input type="hidden" name="avatar_position[]" value="{$image.position}" />
								</div>
							{/foreach}
						</div>
						<button type="button" class="btn btn-default select-avatar">Chọn thêm ảnh</button>
						<span class="help-block">Bạn được phép nhập 5 ảnh</span>
					</div>
				</div>

				<div class="form-group clearfix">
					<label for="price" class="col-sm-2 control-label">
						{#price#}<span class="text-red">(*)</span>
					</label>
					<div class="col-sm-5">
						 <div class="input-group">
						 <input type="text" class="form-control" id="price" placeholder="" name="price" value="{$detail.price}"  maxlength="20" /><span class="input-group-addon">VNĐ</span>
						</div>
					</div>
				</div>
				<div class="form-group clearfix">
					<label for="sale_price" class="col-sm-2 control-label">
						{#saleprice#}<span class="text-red">(*)</span>
					</label>
					<div class="col-sm-5">
						<div class="input-group">
						 <input type="text" class="form-control" id="sale_price" placeholder="" name="sale_price" value="{$detail.sale_price}"  maxlength="20" /><span class="input-group-addon">VNĐ</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="title" class="col-sm-2 control-label">{#content#}</label>
					 <div class="col-sm-10">
						<textarea class="form-control" id="article" name="content" value="" />{$detail.content}</textarea>
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
				<label for="inputCongtac" class="col-sm-2 control-label">{#status#}</label>
				<div class="col-sm-3">
					<select name="status" id="inputCongtac" class="form-control">
						<option value="1"  {if $detail.status == '1'}selected{/if}>{#public_all#}</option>
						<option value="0" {if $detail.status == '0'}selected{/if}>{#unpublic_all#}</option>
					</select>
				</div>
			  </div>
			</div>
			<!--- TAB THONG TIN CHUNG END--->
			
			<!--- TAB THONG TIN ATTRIBUTE BEGIN--->
			{foreach $list_step as $step_item}
				 <div role="tabpanel" class="tab-pane" id="tab{$step_item.id}">
					{foreach $list_attr as $attr_item}
						{if $attr_item.step_id == $step_item.id}
						<div class="form-group clearfix">
							<label for="input_{$step_item.id}_{$attr_item.id}" class="col-sm-2 control-label">{$attr_item.name} {if $attr_item.required}<span class="text-red">(*)</span>{/if}</label>
							{if $attr_item.type == 'text'}
								<div class="col-sm-5">
								  {if $attr_item.unit}<div class="input-group">{/if}
									  <input type="text" class="form-control" id="input_{$step_item.id}_{$attr_item.id}" placeholder="" name="input_{$step_item.id}_{$attr_item.id}" value="{$attr_item.value}"  maxlength="1000">
								{if $attr_item.unit}<span class="input-group-addon">{$attr_item.unit}</span></div>{/if}
								  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
								</div>
							 {elseif $attr_item.type == 'coordinates'}
								<div class="col-sm-5">
									<div class="form-group col-sm-4" style="margin: 0">
										<label for="inputkinhdo" class="control-label">{#longitude#}</label>
										<input type="text" id="inputkinhdo"  class="form-control" name="input_{$step_item.id}_{$attr_item.id}[]" value="{$attr_item.source[0]}" >
									</div>
									<div class="form-group col-sm-4" style="margin: 0">
										<label for="inputvido" class="control-label">{#latitude#}</label>
										<input type="text" id="inputvido" class="form-control" name="input_{$step_item.id}_{$attr_item.id}[]" value="{$attr_item.source[1]}" >
									</div>
									<div class="col-sm-4" style="margin: 0; text-align: center;"><label class="control-label">{#frommap#}</label>
									<div><i style="color: red; font-size: 30px;" class="glyphicon glyphicon-map-marker" id="map-marker"></i></div></div>
								  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
								</div>
							 {elseif $attr_item.type == 'textarea'}
								<div class="col-sm-10">
								  <textarea type="text" class="form-control" id="input_{$step_item.id}_{$attr_item.id}" placeholder="" name="input_{$step_item.id}_{$attr_item.id}" value="{$attr_item.value}" ></textarea>
								  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
								</div>
							 {elseif $attr_item.type == 'file'}
								<div class="col-sm-5">
								  <input type="file" id="input_{$step_item.id}_{$attr_item.id}" placeholder="{$attr_item.name}" name="input_{$step_item.id}_{$attr_item.id}" >
									<ul class="list-group list-attribute-file"  style="margin-top: 5px;">
								  {if is_array($attr_item.value)}
										{foreach $attr_item.value as $value_item}
											{if $value_item != ''}
											 <li class="list-group-item">
												<input type="hidden" value="{$value_item}" name="input_{$step_item.id}_{$attr_item.id}[]" />
												<span class="badge">{#delete#}</span>
												{$value_item}
											  </li>
											 {/if}
										 {/foreach}
										
									{else}
										  <li class="list-group-item">
											<input type="hidden" value="{$attr_item.value}" name="input_{$step_item.id}_{$attr_item.id}" />
											<span class="badge">{#delete#}</span>
											{$attr_item.value}
										  </li>
									{/if}
									 </ul>
								  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
								</div>
							{elseif $attr_item.type == 'image'}
								<div class="col-sm-5">
								  <input type="file" id="input_{$step_item.id}_{$attr_item.id}" placeholder="{$attr_item.name}" name="input_{$step_item.id}_{$attr_item.id}" >
									<ul class="list-group list-attribute-file" id="" style="margin-top: 5px;">
								  {if is_array($attr_item.value)}
										{foreach $attr_item.value as $value_item}
											{if $value_item != ''}
											
											 <li class="list-group-item">
												<input type="hidden" value="{$value_item}" name="input_{$step_item.id}_{$attr_item.id}[]" />
												<span class="badge">{#delete#}</span>
												<img src="{$value_item}" height="40px" />
											  </li>
											 {/if}
										 {/foreach}
										
									{else}
										  <li class="list-group-item">
										  <input type="hidden" value="{$attr_item.value}" name="input_{$step_item.id}_{$attr_item.id}" />
											<span class="badge">{#delete#}</span>
											<img src="{$attr_item.value}" height="40px" />
										  </li>
									{/if}
									 </ul>
								  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
								</div>
							 {elseif $attr_item.type == 'datetime'}
								<div class="col-sm-5">
									<div class='input-group date datetimepicker'>
									  <input type="text" class="form-control" id="input_{$step_item.id}_{$attr_item.id}"  name="input_{$step_item.id}_{$attr_item.id}" value="{$attr_item.value}"  {$attr_item.source} />
									  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
								  </div>
								  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
								</div>
							{elseif $attr_item.type == 'checkbox'}
								<div class="col-sm-5">
									{if $attr_item.source != ''}
										{foreach $attr_item.source as $source_item}
										 <input type="checkbox"  id="input_{$step_item.id}_{$attr_item.id}" placeholder="{$attr_item.name}" name="input_{$step_item.id}_{$attr_item.id}[]" value="{$source_item}"  {if $attr_item.value[$source_item@key] == $source_item}checked{/if}> {$source_item} </br>
										 {/foreach}
									{else}
										 <input type="checkbox"  id="input_{$step_item.id}_{$attr_item.id}" placeholder="{$attr_item.name}" name="input_{$step_item.id}_{$attr_item.id}"  {if $attr_item.value == 'on'}checked{/if} style="margin-top: 12px;">
									{/if}
									{if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
								</div>
							{elseif $attr_item.type == 'radio'}
								<div class="col-sm-5">
									{if $attr_item.source != ''}
										{foreach $attr_item.source as $source_item}
										 <input type="radio"  id="input_{$step_item.id}_{$attr_item.id}" placeholder="{$attr_item.name}" name="input_{$step_item.id}_{$attr_item.id}[]" value="{$source_item}"  {if $attr_item.value == $source_item}checked{/if}> {$source_item}</br>
										 {/foreach}
									{else}
										 <input type="radio"  id="input_{$step_item.id}_{$attr_item.id}" placeholder="{$attr_item.name}" name="input_{$step_item.id}_{$attr_item.id}" {if $attr_item.value == 'on'}checked{/if}>
									{/if}
									{if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
								</div>
							{elseif $attr_item.type == 'select'}
								<div class="col-sm-5">
								  <select class="form-control" id="input_{$step_item.id}_{$attr_item.id}" name="input_{$step_item.id}_{$attr_item.id}" >
										<option value="">----- {#select#} {$attr_item.name} -----</option>
										{foreach $attr_item.source as $source_item}
											<option value="{$source_item}"  {if $attr_item.value == $source_item}selected{/if}>{$source_item}</option>
									 {/foreach}
									</select>
									{if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
								</div>
							{/if}
							</div>
						{/if}
					{/foreach}
				 </div>
			{/foreach}
			<!--- TAB THONG TIN ATTRIBUTE END--->
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-primary">{#submit#}</button>
			  <button type="button" class="btn btn-default back" >{#back#}</button>
			</div>
		</div>
	</div>
	{/if}
</form>
