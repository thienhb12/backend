{if ($smarty.get.task = 'edit' && $smarty.get.id)}
<div role="tabpanel" class="tab-pane active" id="tab-avatar">
	<script type="text/javascript" src="/lib/swfupload/swfupload.js"></script>
<script type="text/javascript" src="/lib/swfupload/handlers.js"></script>

<form>
	<div style="width: 180px; height: 18px; border: solid 1px #7FAAFF; background-color: #C5D9FF; padding: 2px;">
		<span id="spanButtonPlaceholder"></span>
	</div>
</form>
<div id="divFileProgressContainer" style="height: 75px;"></div>
</div>
{/if}