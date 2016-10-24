<script type="text/javascript">
 var pollID = '{$pollQuestion.Poll_ID}';
{literal}
	function statsPoll(id){
		mywindow= window.open("/index.php?mod=poll&task=stats&ajax&id="+ id,"newwindow","status=1,width=600,height=210");
	}
{/literal}
</script>
<!--
<div class="widget widget_text" id="poll">
	<h3>Ý kiến bình chọn</h3>
	<div class="box-body" style="padding: 10px;">
		{if $pollQuestion}
			<b>{$pollQuestion.question}</b>
			<div align="left"><form name="frmpoll" style="margin:0px;">
				<input type="hidden" value="{$pollQuestion.id}" name="hidPoll" id="hidPoll" />

					{foreach from=$pollAnswer item = answer key=idanswer name=poll}
						<div style="font-size:12px; margin-top:5px; ">
							{if $pollQuestion.type == '0'}
								<input type="radio" value="{$idanswer}" id="radPoll_{$smarty.foreach.poll.index}" name="radPoll" />
							{else}
								<input type="checkbox" value="{$idanswer}" id="radPoll_{$smarty.foreach.poll.index}" name="radPoll" />
							{/if}
							<label for="radPoll_{$smarty.foreach.poll.index}">{$answer}</label>
						 </div>
					 {/foreach}
				  <div style=" margin-top:20px;" id="div_poll">
						{if $hide == ''}
						<input type="button" value="Bình chọn" id="pollButton" onclick="poll()" style="cursor:pointer" />
						{/if}
						<input type="button" value="Kết quả"  border="0" onclick="statsPoll(document.frmpoll.hidPoll.value)" style="cursor:pointer"/>
				  </div></form>
			 </div>
		{else}
			<div style="padding:5px;color:#FF0000">Cảm ơn bạn đã bình chọn</div>
		{/if}
		<div class="clear"></div>
	</div>
</div>
-->

<div class="widget widget_text" id="poll">
	<h3>Chia sẻ mạng xã hội</h3>
	<div class="box-body" style="padding: 10px;">
		<iframe src="http://www.facebook.com/plugins/like.php?href=http://truyenhinhonline.net"
        scrolling="no" frameborder="0"
        style="border:none; width:190px; height:125px;color: #ccc"></iframe>
	</div>
</div>
