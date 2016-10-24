<div class="box border">
	<div class="box-header">
		<h3 class="title color{$cate.ordering}">Bình luận:</h3>
	</div>
	<div class="box-body">
		<div class="fb-comments" data-href="http://truyenhinhonline.net/{$detail.page_url}" data-num-posts="5" data-width="636" data-colorscheme="light" style="background: #fff"></div>
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