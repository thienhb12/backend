{config_load file=$smarty.session.lang_file}
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{if $smarty.get.task == 'add'}{#add#}{else}{#edit#}{/if} {#categories#}</span></h4>
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
				<form class="form-horizontal form-admin" method="post" action="{$url_action}" role="form">
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
							<label for="parent_id" class="control-label col-lg-2">{#categories#}</label>
							<div class="col-lg-10">
								<select class="form-control" name="parent_id" id="parent_id">
									<option value="">----- {#select#} {#categories#} -----</option>
									{html_options options=$categories selected=$detail.parent_id}
								</select>
							</div>	
						</div>
					</div>
				
					<div class="col-md-12">
						<div class="form-group">
						    <label for="name" class="col-lg-2 control-label">{#name#}</label>
						    <div class="col-lg-10">
						      <input type="text" class="form-control" required id="name" placeholder="{#categories#}" name="name" value="{$detail.name}">
						    </div>
					  	</div>	
				  	</div>

				  	<div class="col-md-12">
					  	<div class="form-group">
							<label for="title" class="col-lg-2 control-label">{#title#}</label>
							 <div class="col-lg-10">
								<input class="form-control" id="txtLead" name="title" value="{$detail.title}" />
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
							<label for="txtOrder" class="control-label  col-lg-2">{#ordering#}</label>
							<div class="col-lg-10">
								<select class="form-control" name="ordering" id="ordering">
									<option value="">----- {#select#} {#ordering#} -----</option>
									{html_options options=$ordering selected=$detail.ordering}
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-lg-2 control-label">{#status#}</label>
							<div class="col-lg-10">
								<select name="status" class="form-control">
									<option value="1"  {if $detail.status == '1'}selected{/if}>{#public_all#}</option>
									<option value="0" {if $detail.status == '0'}selected{/if}>{#unpublic_all#}</option>
								</select>
							</div>
						</div>
					</div>
					<div class="text-right">
						<button type="submit" class="btn btn-primary">{#submit#}<i class="icon-arrow-right14 position-right"></i></button>
						<button type="button" class="btn btn-default back" >{#back#}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>