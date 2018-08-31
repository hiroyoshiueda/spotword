<div class="book-comment-item"{if $is_hide} style="display:none;"{/if}>
	<div class="book-comment-user">{$comment|@comment_user}　<span>{$comment.createdate|datetime_f}</span></div>
	<div class="book-comment-body"><p>{$comment.body|nl2br}</p></div>
	{if $is_admin}
	<div class="book-comment-admin"><span class="book-comment-admin-title">『<a href="/book/{$book.book_id}/" target="_blank">{$book.title}</a>』へのコメント</span>
		<span class="book-comment-admin-delete"><img src="/img/icon-trash.gif" width="16" height="16" class="icon-middle" /> <a href="#" onclick="return doDeleteComment({$comment.comment_id}, {$comment.book_id});">削除する</a></span></div>
	{/if}
</div>