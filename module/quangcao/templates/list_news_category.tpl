{foreach item=cat from=$category}
<a href="{$smarty.const.SITE_URL}Tin-tuc/c-{$cat.id}/{$cat.Name|remove_marks}.html" title="{$cat.Name}">{$cat.Name}></a>
    {foreach item=item from=$cat.news}
    <a href="{$smarty.const.SITE_URL}Tin-tuc/{$item.id}/{$item.Name|remove_marks}.html" title="{$item.Name}">{$item.Name}</a>
    {/foreach}
{/foreach}