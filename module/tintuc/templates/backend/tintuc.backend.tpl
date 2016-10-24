{config_load file=$smarty.session.lang_file}
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#news#}</span></h4>
			</div>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>
	<div class="content">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="collapse"></a></li>
                		<li><a data-action="reload"></a></li>
                		<li><a data-action="close"></a></li>
                	</ul>
            	</div>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
			<div class="panel-body">
					<form class="form-horizontal form-admin" method="post" action="index.php?mod={$smarty.get.mod}&amod={$smarty.get.amod}&atask={$smarty.get.atask}&task={$smarty.get.task}{if $smarty.get.id}&id={$smarty.get.id}{/if}{if $smarty.get.ticket_type_id}&ticket_type_id={$smarty.get.ticket_type_id} {/if}" role="form" enctype="multipart/form-data">
					<input type="hidden" name="id" value="{$detail.id}">
					{if $smarty.const.MULTI_LANGUAGE}
					<div class="form-group">
						<label for="lang_id" class="col-sm-2 control-label">{#language#}</label>
						 <div class="col-sm-3">
							<select class="form-control" name="lang_id" id="lang_id" required>
								<option value="">----- {#select#} {#language#} -----</option>
								{html_options options=$lang selected=$detail.lang_id}
							</select>
						</div>
					</div>
					{/if}
					<div class="col-md-12">
						<div class="form-group">
							<label for="sub_title" class="col-lg-2 control-label">{#subtitle#}</label>
							 <div class="col-lg-10">
								<input type="text" class="form-control" id="sub_title" name="sub_title" value="{$detail.sub_title}" />
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-lg-2 control-label">{#title#}</label>
							 <div class="col-lg-10">
								<input type="text" class="form-control" id="title" name="title" value="{$detail.title}" required />
							</div>
						</div>
					</div>

					<div class="col-md-12">
					  	<div class="form-group">
							<label for="title" class="col-lg-2 control-label">{#keyword#}</label>
							 <div class="col-lg-10">
								<input class="form-control" id="txtLead" name="keyword" value="{$detail.keyword}" />
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-lg-2 control-label">{#lead#}</label>
							 <div class="col-lg-10">
								<textarea class="form-control" id="txtLead" name="lead" value="" />{$detail.lead}</textarea>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-lg-2 control-label">{#content#}</label>
							 <div class="col-lg-10">
								<textarea class="form-control" id="article" name="content" value="" />{$detail.content}</textarea>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							 <label for="title" class="col-lg-2 control-label">{#tagcloud#}</label>
							 <div class="col-lg-10">
								<input type='text' class="form-control" name="tagcloud" value="{$detail.tagcloud}" />
								<span class="help-block">{#tagcloud_help#}</span>
							</div>
						</div>
					</div>

					<div class="col-sm-offset-2 col-sm-10">
                  		<button type="button" class="btn btn-default attribute" >{#related#}</button>
                      	<ul id="related_link" style="width: 400px; margin: 10px 0 0; " class="list-group">
                            {foreach $list_related as $related}
                                    <li id="link_{$attr.id}" class="list-group-item">
                                        <span class="badge" onclick="deleteRelatedLink(this)">{#delete#}</span>
                                        {$related.title}
                                        <input type="hidden" name="related_id[]" class="clear"  value="{$related.id}" />
                                    </li>
                            {/foreach}
                      	</ul>
	                </div>
	                
					<div class="col-md-12">
						<div class="form-group">
							 <div class="col-sm-offset-2 col-sm-10">
							  <div class="checkbox">
								<label>
								  <input type="checkbox" name="is_hot" {if $detail.is_hot}checked{/if}> {#hotnews#}
								</label>
							  </div>
							 </div>
						 </div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								 <div class="checkbox">
									<label>
									  <input type="checkbox" name="is_home" {if $detail.is_home}checked{/if}> Tin mới
									</label>
								 </div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="inputProvince" class="col-lg-2 control-label">{#categories#}</label>
							 <div class="col-lg-6">
								<select class="form-control" name="cate_id" id="cate_id" required>
									<option value="-1">----- {#select#} {#categories#} -----</option>
									{html_options options=$category selected=$detail.cate_id}
								</select>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-lg-2 control-label">{#status#}</label>
							<div class="col-lg-6">
								<select name="status" class="form-control">
									<option value="1"  {if $detail.status == '1'}selected{/if}>{#public_all#}</option>
									<option value="0" {if $detail.status == '0'}selected{/if}>{#unpublic_all#}</option>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
							  <button type="submit" class="btn btn-primary">{#submit#}</button>
							  <button type="button" class="btn btn-default back" >{#back#}</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="related" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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