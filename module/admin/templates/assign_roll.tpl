{literal}
<script type="text/javascript">
    function show_hide(div_id)
    {
        if($("#"+div_id).css("display") == "block")
            $("#"+div_id).css("display","none");
        else
            $("#"+div_id).css("display","block");
    }
    function checkAllModule(obj,checkbox_id)
    {
		if ($(obj).is(':checked')) {
			  $("#"+checkbox_id+" > input[type='checkbox']").attr("checked", 'checked');
		}
      
        obj_check = $("#"+checkbox_id+" > input[type='checkbox']");
        obj_check.each(function (i) {
                checkAllRoll(this,"roll_"+$(this).attr("value"));
            }
        )
    }
     function checkAllRoll(obj,checkbox_id)
    {
		if ($(obj).is(':checked')) {
			$("#"+checkbox_id+" > div > input[type='checkbox']").attr("checked",'checked');
		}
    }
</script>
{/literal}
<div style="float: left;">
    {foreach from = $module item = item name = item}
        <input type="checkbox" name="module[]" value="{$item.id}" onclick="{if $item.sub_module}checkAllModule(this,'module_{$item.id}'){else}checkAllRoll(this,'module_{$item.id}'){/if}" {if $item.is_full_roll == 1}checked="checked"{/if} /><img src="/common/admin/image/icon/plus.jpg" style="cursor: pointer;" onclick="show_hide('module_{$item.id}')" /> {$item.name}<br />
    {if $item.sub_module}
        <div style="float: left; margin-left: 20px; margin-top: 5px; display: none;" id="module_{$item.id}">
        {foreach from = $item.sub_module item = sub_item name = sub_item}
            <input type="checkbox" name="module[]" value="{$sub_item.id}" onclick="checkAllRoll(this,'roll_{$sub_item.id}')" {if $sub_item.is_full_roll == 1}checked="checked"{/if} /><img src="/common/admin/image/icon/plus.jpg" style="cursor: pointer;" onclick="show_hide('roll_{$sub_item.id}')" /> {$sub_item.name} {$sub_item.id}<br />
            <div style="float: left; margin-top: 5px; display: none;" id="roll_{$sub_item.id}">
                {foreach from = $sub_item.full_roll item = roll_item name = roll_item}
                    <div style="float: left; margin-left: 20px;"><input type="checkbox" name="roll[]" value="{$sub_item.id}-{$roll_item.id}" {if in_array($roll_item.id,$sub_item.current_roll)}checked="checked"{/if} />{$roll_item.name}</div>
                {/foreach}
            </div>
            <div style="clear: both;"></div>
        {/foreach}
        </div>
        <div style="clear: both;"></div>
    {else}
        <div style="float: left; margin-top: 5px; display: none;" id="module_{$item.id}">
            {foreach from = $item.full_roll item = roll_item name = roll_item}
                <div style="float: left; margin-left: 20px;"><input type="checkbox" name="roll[]" value="{$item.id}-{$roll_item.id}" {if in_array($roll_item.id,$item.current_roll)}checked="checked"{/if} />{$roll_item.name}</div>
            {/foreach}
        </div>
        <div style="clear: both;"></div>
    {/if}
    {/foreach}
</div>