<ul class="book-item">
{foreach item=b from=$book_list name=book_item_list}
	{if $smarty.foreach.book_item_list.index % $book_rows == 0}<li class="first">
	{elseif $smarty.foreach.book_item_list.index % $book_rows == ($book_rows - 1)}<li class="last">{else}<li>{/if}
	<a href="/book/{$b.book_id}/"><img class="item-cover" src="{$b.cover_s_path|cover_img_src:$b.user_id}" alt="{$b.title}" width="60" height="80" /></a>
		<a href="/book/{$b.book_id}/" class="item-title">{$b.title}</a>
		<p class="item-desc">{$b.description|show_description|str_cut:'50'}</p>
		<div class="item-info">
			<span class="item-price" title="価格">{if $b.charge_flag==0}無料{/if}</span>
			<span class="item-reads" title="閲覧数">{$b.pv_total}</span>
			<span class="item-epubs" title="ePubダウンロード数">{$b.epub_total}</span>
			<span class="item-comments" title="コメント数">{$comment_map[$b.book_id].total|number_format}</span>
			<span class="item-evaluates" title="評価">{$b.evaluate_total}</span>
		</div>
		{if !$author_hide}
		<p class="item-author">{$b.user_id|username}</p>
		{/if}
	</li>
{/foreach}
</ul>
<div class="clear"></div>