{foreach from=$parameters item=d}
<input type="hidden" id="_{$d.key}" name="{$d.key}" value="{$d.value}" />
{/foreach}