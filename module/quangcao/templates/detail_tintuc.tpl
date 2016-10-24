<div class="box">
	<div class="box-header">
		<h3 class="color11 nav"><a href="/tin-tuc/" class="first">Tin tức</a>{$str_nav}</a></h3>
	</div>
	<div class="box-body" id="article">
		{if $detail.avatar}
			<a href="{$detail.page_url}" title="" class="avatar">
				<img src="{$detail.avatar}" alt="{$detail.title}" style="width: 120px; float: left; vertical-align: middle; margin-right: 10px;"/>
			</a>
		{/if}
		<h1 class="title">{$detail.title}</h1>
		<div class="clear"></div>
		<div class="content" style="margin: 10px 0;">{$detail.content}</div>
		<div class="clear"></div>
		{php}
			loadModule('tagcloud','article');
		{/php}
		{if $other}
			<div class="other list1">
				<ul class="list-simple">
					<li class="first">{#other_news#}</li>
					{foreach from = $other item = item name = item}
						<li><a href="{$item.page_url}" >{$item.title}</a></li>
					{/foreach}
				</ul>
			</div>
		{/if}
		<div class="clear"></div>
	</div>
</div>


	{php}
		loadModule('tool','sharetool');
	{/php}

<div class="box" style="margin-top: 10px;">
	<div class="line-hor">
		<h3 class="title color{$cate.ordering}">Bình luận:</h3>
	</div>
	<div class="inner2">
		<div class="fb-comments" data-href="{$detail.page_url}" data-num-posts="5" data-width="542" data-colorscheme="light" style="background: #fff"></div>
		<script>
		{literal}
		  window.fbAsyncInit = function() {
			FB.init({
			  appId      : 'feedback', // App ID
			  status     : true, // check login status
			  cookie     : true, // enable cookies to allow the server to access the session
			  xfbml      : true  // parse XFBML
			});
		  };

		  (function(d){
			 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement('script'); js.id = id; js.async = true;
			 js.src = "//connect.facebook.net/en_US/all.js";
			 ref.parentNode.insertBefore(js, ref);
		   }(document));
		  {/literal}
		</script>
	</div>
 </div>
