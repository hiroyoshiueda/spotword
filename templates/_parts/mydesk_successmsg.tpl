{if $form.public}
<div class="successmsg" style="font-size:110%;margin:10px 0;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" align="top" /> この作品を公開しました。</div>
{elseif $form.closed}
<div class="successmsg" style="font-size:110%;margin:10px 0;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" align="top" /> この作品を非公開にしました。</div>
{elseif $form.update}
<div class="successmsg" style="font-size:110%;margin:10px 0;">
<img src="/img/icon-success.png" width="16" height="16" alt="OK" align="top" /> 保存内容が公開されました。</div>
{elseif $form.public_err}
<div class="errormsg" style="font-weight:bold;font-size:110%;margin:10px 0;">
<img src="/img/icon-exclamation.png" width="16" height="16" alt="ERROR" align="top" /> エラー発生：作品を公開できませんでした。</div>
{elseif $form.closed_err}
<div class="errormsg" style="font-weight:bold;font-size:110%;margin:10px 0;">
<img src="/img/icon-exclamation.png" width="16" height="16" alt="ERROR" align="top" /> エラー発生：作品を非公開にできませんでした。</div>
{/if}
