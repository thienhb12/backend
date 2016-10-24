<div class="box" style="margin-top: 10px;">
	<div class="box-body">
		
		<script>
		{literal}
			document.write('<div class="fb-comments" data-href="'+location.href+'" data-num-posts="5" data-width="100%" data-colorscheme="light" style="background: #fff"></div>')
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
			 js.src = "//connect.facebook.net/vi_VN/all.js";
			 ref.parentNode.insertBefore(js, ref);
		   }(document));
		  {/literal}
		</script>
	</div>
 </div>
