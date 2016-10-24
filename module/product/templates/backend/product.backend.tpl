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
							<label for="title" class="col-lg-2 control-label">{#name#}</label>
							 <div class="col-lg-10">
								<input type="text" class="form-control" id="title" name="Name" value="{$detail.Name}" required />
							</div>
						</div>
					</div>	

					<div class="col-md-12">
						<div class="form-group">
							<label for="sub_title" class="col-lg-2 control-label">{#subtitle#}</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="sub_title" name="Sub_title" value="{$detail.Sub_title}" />
							</div>
						</div>
					</div>	
	
					<div class="col-md-12">
					  	<div class="form-group">
							<label for="title" class="col-lg-2 control-label">{#keyword#}</label>
							 <div class="col-lg-10">
								<input class="form-control" id="txtLead" name="Keyword" value="{$detail.Keyword}" />
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">{#Description#}</label>
							 <div class="col-sm-10">
								<textarea class="form-control" id="txtLead" name="Description" value="{$detail.Description}" />{$detail.Description}</textarea>
							</div>
						</div>
					</div>


					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">{#lead#}</label>
							 <div class="col-sm-10">
								<textarea class="form-control" id="txtLead" name="Summarise" value="{$detail.Summarise}" />{$detail.Summarise}</textarea>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="avatar" class="col-lg-2 control-label">{#avatar#}</label>
							 <div class="col-lg-5" class="" style="position: relative;">
								<div class="row" id="avatar-thumbnail">
								  <div class="col-xs-6 col-md-6">
									<a href="#" class="thumbnail thumb-1">
				                        <img  data-src="holder.js/210x180?text=Chọn ảnh đại diện" src="{$detail.Photo}" data-position="1">
				                        <input type="hidden" name="Photo" value="{$detail.Photo}" />
				                        <button type="button" class="close"><span aria-hidden="true">×</span></button>
									</a>
								  </div>
								</div>
							</div> 
						</div>
					</div>

					<div class="col-md-12">
	 					<div class="form-group">
	 						<label for="inputProvince" class="col-lg-2 control-label">{#categories#}</label>
							 <div class="col-lg-5">
								<select class="form-control" name="CatID" id="cate_id" required>
									<option value="-1">----- {#select#} {#categories#} -----</option>
									{html_options options=$category selected=$detail.CatID}
								</select>
							</div>
	 					</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="inputProvince" class="col-lg-2 control-label">{#categories_price#}</label>
							 <div class="col-lg-5">
								<select class="form-control" name="PriceID" id="cate_id" required>
									<option value="-1">----- {#select#} {#categories_price#} -----</option>
									{html_options options=$category_Price selected=$detail.PriceID}
								</select>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="inputProvince" class="col-lg-2 control-label">{#trademark#}</label>
							 <div class="col-lg-5">
								<select class="form-control" name="MacID" id="cate_id" required>
									<option value="-1">----- {#select#} {#categories_mac#} -----</option>
									{html_options options=$category_Mac selected=$detail.MacID}
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">{#tagcloud#}</label>
							<div class="col-sm-10">
								<input type='text' class="form-control" name="tagcloud" value="{$detail.tagcloud}" />
								<span class="help-block">{#tagcloud_help#}</span>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">{#product_price_pay#}</label>
							 <div class="col-sm-5">
								<input type="text" class="form-control" id="title" name="Price" value="{$detail.Price}" required />
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">{#product_price_old#}</label>
							 <div class="col-sm-5">
								<input type="text" class="form-control" id="title" name="Old_Price" value="{$detail.Old_Price}" required />
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">{#product_code#}</label>	
							 <div class="col-sm-5">
								<input type="text" class="form-control" id="title" name="Code" value="{$detail.Code}" required />
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">{#product_price_sale#}</label>
							 <div class="col-sm-5">
								<input type="text" class="form-control" id="title" name="giagoc" value="{$detail.giagoc}" required />
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">{#number#}</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="Soluong" name="Soluong" value="{$detail.Soluong}" required />
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label">{#content#}</label>
							 <div class="col-sm-9">
								<textarea class="form-control" id="article" name="Content" value="" />{$detail.Content}</textarea>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<div class="checkbox">
									<label>
										<input type="checkbox" value="1" name="IsHot" {if $detail.IsHot}checked{/if}> SP hot nhất
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
										<input type="checkbox" name="IsNew" value="1" {if $detail.IsNew}checked{/if}> SP mới nhất
									</label>
						  		</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="inputCongtac" class="col-sm-2 control-label">{#status#}</label>
							<div class="col-sm-3">
								<select name="Status" id="inputCongtac" class="form-control">
									<option value="1"  {if $detail.Status == '1'}selected{/if}>{#public_all#}</option>
									<option value="0" {if $detail.Status == '0'}selected{/if}>{#unpublic_all#}</option>
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

