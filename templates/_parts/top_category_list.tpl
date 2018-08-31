<ul class="book-item" style="margin-left:5px;">
{foreach item=b from=$category_list name=category_item_list}
	{if $smarty.foreach.category_item_list.index % $category_rows == 0}<li class="first" style="width:200px;">
	{elseif $smarty.foreach.category_item_list.index % $category_rows == ($category_rows - 1)}<li class="last" style="width:200px;">{else}<li style="width:200px;">{/if}
	{*<a href="/list/category/{$b.category_id}/"><img class="item-cover" src="{$b.cover_s_path|cover_img_src:$b.user_id}" alt="{$b.title}" width="60" height="80" /></a>*}
		<a href="/list/category/{$b.category_id}/" class="item-title" style="font-size:108%;">{$b.category_name}({$b.cnt})</a>
		<div class="item-desc" style="margin-top:5px;">
{if $b.book_datas}
		<ul>
	{foreach item=d from=$b.book_datas}
			<li class="with_dot" style="margin:0;width:190px;float:none;"><a href="/book/{$d.book_id}/">{$d.title}</a></li>
	{/foreach}
		</ul>
{/if}
		</div>
	</li>
{/foreach}
</ul>
<div class="clear"></div>