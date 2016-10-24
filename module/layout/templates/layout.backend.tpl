{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#news#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#news#}</li>
</ul>
<form class="form-horizontal form-admin" method="post" action="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&task={$smarty.get.task}{if $smarty.get.id}&id={$smarty.get.id}{/if}{if $smarty.get.ticket_type_id}&ticket_type_id={$smarty.get.ticket_type_id} {/if}" role="form" enctype="multipart/form-data">
	<input type="hidden" name="id" value="{$detail.id}">
		 <div class="form-group">
			<label for="name" class="col-sm-2 control-label">{#name#}</label>
			 <div class="col-sm-5">
				<input type="text" class="form-control" id="name" name="name" value="{$detail.name}" required />
			</div>
		</div>
		<div class="form-group">
			<label for="title" class="col-sm-2 control-label">{#content#}</label>
			 <div class="col-sm-9">
				<textarea class="form-control" id="article" name="content" value=""  style="height: 450px; width: 800px;"/>{$detail.content}</textarea>
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
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-primary">{#submit#}</button>
			  <button type="button" class="btn btn-default back" >{#back#}</button>
			</div>
		</div>
	</div>
</form>
