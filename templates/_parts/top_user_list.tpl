<ul class="book-item">
{foreach item=u from=$user_list name=user_item_list}
	{if $smarty.foreach.user_item_list.index % $user_rows == 0}<li class="first">
	{elseif $smarty.foreach.user_item_list.index % $user_rows == ($user_rows - 1)}<li class="last">{else}<li>{/if}
		<a href="/{$u.login}/"><img class="item-cover" src="{$u|@profileimg:'normal'}" alt="{$u.penname}" width="73" height="73" /></a>
		<p class="item-desc">{$u.profile_msg|show_description|str_cut:'50'}</p>
		<a href="/{$u.login}/" class="item-title clear">{$u.penname}</a>
	</li>
{/foreach}
</ul>
<div class="clear"></div>