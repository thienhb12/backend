{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#district#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#district#}</li>
</ul>
<div class="fieldAttribute">
	<ul class="nav nav-tabs" role="tablist">
		{foreach $list_step as $item}
			<li role="presentation" {if $item@index == 0}class="active"{/if}><a href="#tab{$item.id}" role="tab" data-toggle="tab">{$item.name}</a></li>
		{/foreach}
	</ul>

	<div class="tab-content" style="padding: 20px 0;" >
		{foreach $list_step as $step_item}
			 <div role="tabpanel" class="tab-pane {if $step_item@index == 0}active{/if}" id="tab{$step_item.id}">
				{$isFirst = 1}
				{$isLast = 0}
				{foreach $list_attr as $attr_item}
					{if $attr_item.step_id == $step_item.id}
					{if $step_item.congtac == 1 && $isFirst == 1}
						<div class="form-group">
							<label for="input_{$step_item.id}_congtac" class="col-sm-2 control-label" style="padding-top: 0;">{$step_item.label_congtac}</label>
							<div class="col-sm-5">
								<input type="checkbox" class="cong-tac"  id="input_{$step_item.id}_congtac"  name="input_{$step_item.id}_congtac" {if $attr_item.step_value == 1}checked{/if} />
							 </div>
						 </div>
						<div id="congtac_content" class="cong-tac-content {if $attr_item.step_value == 0}hidden{/if}">
						{$isFirst = 0}
					{/if}
					<div class="form-group">
						<label for="input_{$step_item.id}_{$attr_item.id}" class="col-sm-2 control-label">{$attr_item.name} {if $attr_item.required}<span class="text-red">(*)</span>{/if}</label>
						{if $attr_item.type == 'text'}
							<div class="col-sm-5">
							  {if $attr_item.unit}<div class="input-group">{/if}
								  <input type="text" class="form-control" id="input_{$step_item.id}_{$attr_item.id}" placeholder="" name="input_{$step_item.id}_{$attr_item.id}" value="{$attr_item.value}" {if $attr_item.required}required{/if} maxlength="1000">
							{if $attr_item.unit}<span class="input-group-addon">{$attr_item.unit}</span></div>{/if}
							  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
							</div>
						 {elseif $attr_item.type == 'coordinates'}
							<div class="col-sm-5">
								<div class="form-group col-sm-4" style="margin: 0">
									<label for="inputkinhdo" class="control-label">{#longitude}</label>
									<input type="text" id="inputkinhdo"  class="form-control" name="input_{$step_item.id}_{$attr_item.id}[]" value="{$attr_item.source[0]}" {if $attr_item.required}required{/if}>
								</div>
								<div class="form-group col-sm-4" style="margin: 0">
									<label for="inputvido" class="control-label">{#latitude}</label>
									<input type="text" id="inputvido" class="form-control" name="input_{$step_item.id}_{$attr_item.id}[]" value="{$attr_item.source[1]}" {if $attr_item.required}required{/if}>
								</div>
								<div class="col-sm-4" style="margin: 0; text-align: center;"><label class="control-label">{#frommap#}</label>
								<div><i style="color: red; font-size: 30px;" class="glyphicon glyphicon-map-marker" id="map-marker"></i></div></div>
							  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
							</div>
						 {elseif $attr_item.type == 'textarea'}
							<div class="col-sm-10">
							  <textarea type="text" class="form-control" id="input_{$step_item.id}_{$attr_item.id}" placeholder="" name="input_{$step_item.id}_{$attr_item.id}" value="{$attr_item.value}" {if $attr_item.required}required{/if}></textarea>
							  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
							</div>
						 {elseif $attr_item.type == 'file'}
							<div class="col-sm-10">
							  <input type="file" id="input_{$step_item.id}_{$attr_item.id}" placeholder="{$attr_item.name}" name="input_{$step_item.id}_{$attr_item.id}" value="{$attr_item.value}" {if $attr_item.required}required{/if}>
							  {if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
							</div>
						 {elseif $attr_item.type == 'datetime'}
							<div class="col-sm-5">
								<div class='input-group date datetimepicker'>
								  <input type="text" class="form-control" id="input_{$step_item.id}_{$attr_item.id}"  name="input_{$step_item.id}_{$attr_item.id}" value="{$attr_item.value}" {if $attr_item.required}required{/if} {$attr_item.source} />
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
									 <input type="checkbox"  id="input_{$step_item.id}_{$attr_item.id}" placeholder="{$attr_item.name}" name="input_{$step_item.id}_{$attr_item.id}"  {if $attr_item.value}checked{/if} style="margin-top: 12px;">
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
							  <select class="form-control" id="input_{$attr_item.source_type}" name="input_{$step_item.id}_{$attr_item.id}" {if $attr_item.required}required{/if}>
									<option value="-1">----- {#selec#} {$attr_item.name} -----</option>
									{html_options options=$attr_item.source selected=$attr_item.value}
								</select>
								{if $attr_item.description}<span class="help-block">{$attr_item.description}</span>{/if}
							</div>
						{/if}
						</div>
						{if $step_item.congtac == 1 && $step_item.id != $list_attr[($attr_item@index + 1)].step_id}
							</div>
						{/if}
					{/if}
				{/foreach}
			 </div>
		{/foreach}
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		  <button type="submit" class="btn btn-primary">{#submit#}</button>
		  <button type="button" class="btn btn-default back" >{#back#}</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function() {
		function getDistrictByProvince(province_id) {
			var url = '/index.php?mod=admin&amod=phieukhaosat&atask=district&task=province&ajax&province_id=' + province_id;
			$.getJSON( url, function( data ) {
				$('#input_district').html('')
				for (var i = 0; i < data.length; i++) {
					$('#input_district').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
				}
				getTownByDistrict(data[0].id);
			});

			
		}

		function getTownByDistrict(district_id) {
			var url = '/index.php?mod=admin&amod=phieukhaosat&atask=town&task=district&ajax&district_id=' + district_id;
			$.getJSON( url, function( data ) {
				$('#input_ward').html('')
				for (var i = 0; i < data.length; i++) {
					if (data[i].name != null) {
						$('#input_ward').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
					}
				}
			});
		}

		$('#input_province').change(function() {
			getDistrictByProvince($(this).val());
		});

		$('#input_district').change(function() {
			getTownByDistrict($(this).val());
		});

		$('.datetimepicker').datetimepicker();

		$('#map-marker').on('click', function() {
			url = 'map.php?1';
			if ($('#input_province').val() == -1) {
				$('#input_province').focus();
				return;
			}
			if ($('#input_district').val() == -1) {
				$('#input_district').focus();
				return;
			}
			if ($('#input_ward').val() == -1) {
				$('#input_ward').focus();
				return;
			}

			url += '&province=' + $('#input_province  option:selected').text();
			url += '&district=' + $('#input_district option:selected').text();
			url += '&ward=' + $('#input_ward option:selected').text();
			window.open(url , '', 'height=500,width=600');
		})

		$('.cong-tac').on('click', function() {
			var tab_active = $(this).parents('.tab-pane.active');
			if (tab_active.find('.cong-tac-content').hasClass('hidden')) {
				tab_active.find('.cong-tac-content').removeClass('hidden');
			}else {
				tab_active.find('.cong-tac-content').addClass('hidden');
			}
		})
	});
</script>
