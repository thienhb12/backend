<div class="container clearfix">
	<div class="col-md-12 no-padding">
			{if !isMobile()}
			<div class="banner-top-1 clearfix">
				<div id="carousel-menu-generic" class="carousel slide" data-ride="carousel" data-interval="0" >
				  <!-- Indicators -->
				  <ol class="carousel-indicators">
					<li data-target="#carousel-menu-generic" data-slide-to="0" class="active"></li>
					<li data-target="#carousel-menu-generic" data-slide-to="1"></li>
				  </ol>

				  <!-- Wrapper for slides -->
				  <div class="carousel-inner" role="listbox">
					<div class="item active">
					  <img src="{$smarty.const.SITE_URL}common/image/ph1.gif" alt="..." />
					</div>
					<div class="item">
					  <img data-original="{$smarty.const.SITE_URL}common/image/ph2.png" alt="..." src="/common/image/grey.gif" />
					</div>
				  </div>

				  <!-- Controls -->
				  <a class="left carousel-control" href="#carousel-menu-generic" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				  </a>
				  <a class="right carousel-control" href="#carousel-menu-generic" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				  </a>
				</div>
			</div>
			{/if}
			<!-- duoi slide -->
			<div class="banner-top-2 clearfix">
				<div id="carousel-small" class="carousel slide" data-ride="carousel" data-interval="0">
				  <!-- Wrapper for slides -->
				  <div class="carousel-inner" role="listbox">
					<div class="item active">
						{if isMobile()}
						<img src="http://dongho.vietesoft.com/common/image/banner-giua-new.jpg" alt="..." class="img_three" />
						{else}
						<img src="http://dongho.vietesoft.com/common/image/banner-giua-new.jpg" alt="..." class="img_three" />
						<img src="http://chon.vn/Files/upload/Images/PromotionDeal/635651469146360000_Cocoxi_480x340.jpg" alt="..." class="img_three" />
						<img src="http://dongho.vietesoft.com/common/image/diamodd.jpg" alt="..." class="img_three nomar_right" />
						{/if}
					</div>
					<div class="item">
						{if isMobile()}
						<img data-original="http://dongho.vietesoft.com/common/image/epos.jpg"  src="/common/image/grey.gif" alt="..." class="img_three" />
						{else}
						<img data-original="http://dongho.vietesoft.com/common/image/epos.jpg"  src="/common/image/grey.gif" alt="..." class="img_three" />
						<img data-original="http://dongho.vietesoft.com/common/image/ariesGold.jpg"  src="/common/image/grey.gif" alt="..." class="img_three" />
						<img  src="/common/image/grey.gif" data-original="http://chon.vn/Files/upload/Images/PromotionDeal/635651467088192000_Vichy_480x340.jpg" alt="..." class="img_three nomar_right" />
						{/if}
					</div>
				  </div>

				  <!-- Controls -->
				  <a class="left carousel-control" href="#carousel-small" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				  </a>
				  <a class="right carousel-control" href="#carousel-small" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				  </a>
				</div>
			</div>
			<!-- End duoi slide -->
	</div>
</div>
