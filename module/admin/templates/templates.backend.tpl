{config_load file=$smarty.session.lang_file}
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin">{#home#}</a></li>
	<li><a href="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}">{#list#} {#site#}</a></li>
	<li>{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#templates#}</li>
</ul>

<form class="form-horizontal form-admin" method="post" action="{$action_url}" role="form"  enctype="multipart/form-data">
	<input type="hidden" name="id" value="{$detail.id}">
  <div class="form-group">
    <label for="title" class="col-sm-2 control-label">{#title#}</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" required id="title" name="title" value="{$detail.title}">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="button" class="btn btn-default attribute" >{#selectSite#}</button>
	  <ul id="related_link" style="width: 400px; margin: 10px 0 0; " class="list-group">
		{foreach $list_attr_of_site as $site}
			<li id="link_{$site.id}" class="list-group-item">
				<span class="badge" onclick="deleteRelatedLink(this)">{#delete#}</span>
				{$site.name}
				<input type="hidden" name="site_id[]" class="clear"  value="{$site.id}" />
			</li>
		{/foreach}
	  </ul>
    </div>
 <div class="form-group">
    <label for="title" class="col-sm-2 control-label">{#content#}</label>
     <div class="col-sm-9">
      <textarea class="form-control" required name="content" style="height: 100px;">{$detail.content}</textarea>
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