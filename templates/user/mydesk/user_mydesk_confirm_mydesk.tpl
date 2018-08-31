{literal}
<script type="text/javascript">
//<![CDATA[
function doSetup()
{
	$('#mainform').submit();
}
//]]>
</script>
{/literal}
<div id="main-contents">
<div id="mydesk">
<h2>{$form.htitle}</h2>
<form id="mainform" method="post" action="/user/mydesk/setup">
{include file="_parts/hidden.tpl"}
<div class="infomsg" style="text-align:center;padding:15px;margin:20px 0;">電子書籍を執筆するためにはマイデスクを開設（無料）する必要があります。<br />
<strong>マイデスクを開設しますか？</strong><br />
<div style="padding:5px;"><input class="btn-02" onclick="doSetup();" value="開設する" type="button" /></div>
<p style="color:#666;">※開設後はこの確認画面は表示されません</p>
<!-- #infomsg --></div>
</form>
<!-- #mydesk --></div>
<!-- #main-contents --></div>

<div id="main-side">
{include file="_parts/mydesk_side.tpl"}
<!-- #main-side --></div>

<div class="clear"></div>

