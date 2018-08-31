<div id="main-contents">
<div id="regist">

<h1>{$form.htitle}</h1>
{if !$form.nonavi}
<p id="step-navi-3">STEP3.登録完了</p>
{/if}

<div class="successmsg" style="margin:30px 0px;font-size:110%;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" style="vertical-align:middle;" /> スポットワードIDの登録が完了しました。</div>

<div class="infomsg">
<p style="font-weight:bold;font-size:110%;margin-bottom:5px;">この後はどうする？</p>
<ul style="margin-left:10px;">
	<li class="with_dot"><a href="/user/mydesk/">マイデスク</a>でスグに本を書く！</li>
	<li class="with_dot"><a href="/user/edit/">登録情報</a>で自己紹介文を書いたり、写真をアップ！</li>
</ul>
</div>

<div style="text-align:center;"><a href="/">とりあえずトップページへ</a></div>

<!-- #regist --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/regist_side.tpl"}
{if $smarty.const.APP_ENV=="release"}
{literal}
<!-- Google Code for &#30331;&#37682; Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1039231240;
var google_conversion_language = "ja";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "r-nACPTBhQIQiNLF7wM";
var google_conversion_value = 0;
if (1000) {
  google_conversion_value = 1000;
}
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1039231240/?value=1000&amp;label=r-nACPTBhQIQiNLF7wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
{/literal}
{/if}
<!-- #main-side --></div>

<div class="clear"></div>
