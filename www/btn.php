<?php
function twentyten_social_btn() {
//get_permalink() get_title()
	print('<div style="text-align:right;">');
	print('<a href="http://twitter.com/share" class="twitter-share-button" data-url="'.get_permalink().'" data-count="horizontal" data-text="');
	the_title();
	print(' | 電子書籍作成サービス「スポットワード」公式ブログ" data-via="spotwordjp" data-lang="ja">Tweet</a>');
	print('<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>');
	print('<iframe src="http://www.facebook.com/plugins/like.php?href='.urlencode(get_permalink()).'&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;" allowTransparency="true"></iframe>');
	// data-hatena-bookmark-title=""
	print('<a href="http://b.hatena.ne.jp/entry/'.get_permalink().'" class="hatena-bookmark-button" data-hatena-bookmark-layout="simple" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a>');
	print('<script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>');
	print('</div>');
	/**
	 * <!-- はてなブックマーク -->
<a href="http://b.hatena.ne.jp/entry/<?php the_permalink() ?>" class="hatena-bookmark-button" data-hatena-bookmark-title="<?php the_title() ?>" data-hatena-bookmark-layout="standard" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a><script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>

<!-- Twitter -->
<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-lang="ja">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>

<!-- Evernote -->
<script type="text/javascript" src="http://static.evernote.com/noteit.js"></script><a href="#" onclick="Evernote.doClip({}); return false;"><img src="http://static.evernote.com/article-clipper-jp.png" alt="Clip to Evernote" /></a>
<!-- Facebook -->
<iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink() ?>&amp;layout=button_count&amp;show_faces=false&amp;width=90&amp;action=like&amp;colorscheme=light&amp;height=20" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:20px;" allowTransparency="true"></iframe>
	 */
}
?>
