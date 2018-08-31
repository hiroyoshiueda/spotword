
<div id="main-contents">
<div id="top-main">
<div id="top-main-banner">
<a href="/service/" title="スポットワードについて"><img src="/img/top_main_b.png" width="655" height="150" alt="本を書いてみない？スポットワードは、あなたの小説や漫画などの作品を電子書籍として投稿し、作家と読者、作家同士をつなげる電子書籍コミュニティです。" /></a>
</div>
<div id="top-contents">
<div class="top-section">
<h2 class="top-title">人気本<span class="top-title-rss"><a href="/rss/popular" title="人気本のRSS" target="_blank">人気本のRSS</a></span></h2>
{if $form.popular_book_list}
{include file="_parts/top_book_list.tpl" book_list=$form.popular_book_list comment_map=$form.comment_map book_rows=4}
{else}
<p style="color:#666;">該当する本はありません。</p>
{/if}
<!-- #top-section --></div>

<div class="top-section">
<h2 class="top-title">新着本<span class="top-title-rss"><a href="/rss/newarrivals" title="新着本のRSS" target="_blank">新着本のRSS</a></span></h2>
{if $form.new_book_list}
{include file="_parts/top_book_list.tpl" book_list=$form.new_book_list comment_map=$form.comment_map book_rows=4}
{else}
<p style="color:#666;">該当する本はありません。</p>
{/if}
<!-- #top-section --></div>

<div class="top-section">
<h2 class="top-title">人気作家</h2>
{if $form.popular_user_list}
{include file="_parts/top_user_list.tpl" user_list=$form.popular_user_list user_rows=4}
{else}
<p style="color:#666;">該当する作家はいません。</p>
{/if}
<!-- #top-section --></div>

{if $form.category_list}
<div class="top-section">
<h2 class="top-title">ジャンル別</h2>
{include file="_parts/top_category_list.tpl" category_list=$form.category_list category_rows=3}
<!-- #top-section --></div>
{/if}

<!-- #top-contents --></div>
<!-- #top-main --></div>
<!-- #main-contents --></div>

<div id="main-side">
<div id="top-side">
<div id="top-side-banner">
<a href="/user/regist/first" title="メンバー登録"><img src="/img/top_side_b.png" width="305" height="150" alt="ブログよりもっと伝えたいことがある人はメンバー登録" /></a>
</div>

<div id="top-ads">
<div id="top-ads-inner">
<script type="text/javascript">
<!--
google_ad_client = "ca-pub-5527425422179463";
google_ad_slot = "3970322304";
google_ad_width = 250;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
</div>

<div id="twitter-followme"><a href="http://twitter.com/spotwordjp" target="_blank" title="公式Twitter">公式Twitter～新着本や電子書籍の話題をつぶやくよ</a></div>

<div id="top-info">
<div id="top-info-header"><h3>スポットワードからのお知らせ</h3></div>
<div id="top-info-inner">
<ul>
	<li><a href="http://blog.spotword.jp/2016/08/spotword-2/">サービス終了のお知らせ</a><span>(2016/8/1)</span></li>
	<li><a href="http://blog.spotword.jp/2011/06/e-sora/" title="運営会社の変更に伴うお知らせ。">運営会社の変更に伴うお知らせ。</a><span>(2011/6/1)</span></li>
	<li><a href="http://blog.spotword.jp/2011/01/mixi/" title="mixiアカウントによるログインに対応しました。">mixiアカウントによるログインに対応しました。</a><span>(2011/1/13)</span></li>
	<li><a href="http://blog.spotword.jp/2010/12/ebookreader/" title="ページめくり対応の電子書籍リーダー機能を追加しました。">ページめくり対応の電子書籍リーダー機能を追加しました。</a><span>(2010/12/25)</span></li>
	<li><a href="http://blog.spotword.jp/2010/12/imageupload/" title="画像の一括アップロードが可能になりました。">画像の一括アップロードが可能になりました。</a><span>(2010/12/16)</span></li>
</ul>
<p style="text-align:right;margin-top:2px;"><a href="http://blog.spotword.jp/">お知らせをもっと見る</a></p>
</div>
</div>

{include file="_parts/side_request_form.tpl"}

<!-- #top-side --></div>
<!-- #main-side --></div>

<div class="clear"></div>

{literal}
<script type="text/javascript">
//<![CDATA[
$('span.item-price, span.item-reads, span.item-epubs, span.item-comments, span.item-evaluates').tipsy();
//]]>
</script>
{/literal}