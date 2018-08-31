<?php
define('APP_CONST_SITE_TITLE', '電子書籍の作成・配信サイト「スポットワード」');
define('APP_CONST_SITE_TITLE2', ' [スポットワード] 電子書籍の作成・配信サイト');
define('APP_CONST_SITE_TITLE_F', '電子書籍デビュー／電子書籍を作成するなら スポットワード');
define('APP_CONST_SITE_TITLE_J', 'スポットワード');
define('APP_CONST_SITE_TITLE_S', 'SPOTWORD');
define('APP_CONST_BIZ_NAME', 'スポットワード運営事務局');
define('APP_CONST_SITE_DOMAIN', 'spotword.jp');
define('APP_CONST_META_KEYWORDS', '電子書籍,投稿,共有,自費出版,小説,作品,作家');
define('APP_CONST_META_DESCRIPTION', '誰でも簡単に電子書籍を投稿し共有できる電子書籍コミュニティです。小説、コミック、ビジネス書などジャンルを問わず、電子書籍（ePub）として文章作品、画像作品を公開でき、iPadやiPhoneなどで読んでもらえます。');

define('APP_CONST_MAIN_FRAME', '_frame/main_frame');
define('APP_CONST_BLANK_FRAME', '_frame/blank_frame');
define('APP_CONST_ADMIN_FRAME', '_frame/admin_frame');
define('APP_CONST_EMPTY_FRAME', '_frame/empty_frame');
define('APP_CONST_POPUP_FRAME', '_frame/popup_frame');
define('APP_CONST_USER_FRAME', '_frame/user_frame');
define('APP_CONST_READER_FRAME', '_frame/reader_frame');
define('APP_CONST_NOTFOUND_FRAME', '_frame/notfound_frame');

define('APP_CONST_JS_PATH', '/js/');
define('APP_CONST_CSS_PATH', '/css/');
define('APP_CONST_TMP_DIR', APP_DIR . '/tmp');
define('APP_CONST_PAGE_LIMIT', 10);
define('APP_CONST_TMP_DIR_REMOVE_DAYS', 7);
define('APP_CONST_TMP_USER_REMOVE_DAYS', 7);
define('APP_CONST_TMP_PAGE_REMOVE_DAYS', 7);

if (APP_ENV == 'release') {
	define('APP_CONST_INFO_EMAIL', 'info@spotword.jp');
	define('APP_CONST_SERVICE_EMAIL', 'service@spotword.jp');
	define('APP_CONST_CONTACT_EMAIL', 'info@spotword.jp');
} else {
	define('APP_CONST_INFO_EMAIL', 'info@spotword.jp');
	define('APP_CONST_SERVICE_EMAIL', 'service@spotword.jp');
	define('APP_CONST_CONTACT_EMAIL', 'info@spotword.jp');
}
define('APP_CONST_CONTACT_TO_EMAIL', 'info@spotword.jp');

define('APP_CONST_REGIST_FIRST_TIME', 86400);

define('APP_CONST_PUB_BLANK_NAME', 'New Title');
define('APP_CONST_BOOK_DIR', APP_WWW_DIR . '/book');
define('APP_CONST_DOWNLOAD_DIR', APP_WWW_DIR . '/download');
define('APP_CONST_DOWNLOAD_EPUB_DIR', APP_CONST_DOWNLOAD_DIR . '/epub');
define('APP_CONST_BOOK_EPUB_DIR', APP_DIR . '/data/epub');

define('APP_CONST_PUBLICATION_CONTENTS_SIZE', 512);
define('APP_CONST_PUBLICATION_CONTENTS_DIR', APP_DIR . '/data/publication/[user_id]/page/[publication_id]');

define('APP_CONST_PAGE_MAX_WORD_SIZE', 800);
define('APP_CONST_PAGE_MAX_SIZE', 5);

define('APP_CONST_LOAD_USER_NAME', '__load_user__');
define('APP_CONST_USER_TYPE_U', 'user');
define('APP_CONST_BOOK_UNIQ_COOKIE_NAME', 'book_uniq');
define('APP_CONST_EPUB_UNIQ_COOKIE_NAME', 'epub_uniq');
define('APP_CONST_BOOK_EVALUATE_UNIQ_COOKIE_NAME', 'book_evaluate_uniq');
define('APP_CONST_USER_LOGINID_REG', '^[0-9a-zA-Z]{1}[-0-9a-zA-Z]{2,18}[0-9a-zA-Z]{1}$');
define('APP_CONST_BOOK_NEW_COOKIE_NAME', 'book_viewer');

define('APP_CONST_COVER_IAMGE_MAX_SIZE', 3145728);
define('APP_CONST_COVER_IAMGE_WIDTH', 724);
define('APP_CONST_COVER_IAMGE_HEIGHT', 1024);
define('APP_CONST_COVER_IAMGE_S_WIDTH', 90);
define('APP_CONST_COVER_IAMGE_S_HEIGHT', 120);
define('APP_CONST_COVER_IMAGE_EXT_TXT', 'GIF形式、JPEG形式、PNG形式');
define('APP_CONST_COVER_IMAGE_EXT_REG', '/^(gif|jpe?g|png)$/i');
define('APP_CONST_COVER_IMAGE_PATH', '/file/[user_id]/cover');
define('APP_CONST_COVER_IMAGE_DIR', APP_WWW_DIR . APP_CONST_COVER_IMAGE_PATH);
define('APP_CONST_COVER_IMAGE_TMP_DIR', APP_WWW_DIR . '/tmp');

define('APP_CONST_PUBLICATION_IAMGE_TOTAL_MAX_SIZE', 10485760);
define('APP_CONST_PUBLICATION_IAMGE_MAX_SIZE', 1048576);
define('APP_CONST_PUBLICATION_IMAGE_EXT_TXT', 'GIF形式、JPEG形式、PNG形式');
define('APP_CONST_PUBLICATION_IMAGE_EXT_REG', '/^(gif|jpe?g|png)$/i');
define('APP_CONST_PUBLICATION_IMAGE_PATH', '/file/[user_id]/publication/[publication_id]');
define('APP_CONST_PUBLICATION_IMAGE_DIR', APP_WWW_DIR . APP_CONST_PUBLICATION_IMAGE_PATH);
define('APP_CONST_PUBLICATION_IMAGE_TMP_DIR', APP_DIR . '/tmp');
define('APP_CONST_PUBLICATION_IAMGE_ZIP_MAX_SIZE', 10485760);
define('APP_CONST_PUBLICATION_IMAGE_ZIP_EXT_TXT', 'ZIP形式');
define('APP_CONST_PUBLICATION_IMAGE_ZIP_EXT_REG', '/^(zip)$/i');
define('APP_CONST_PUBLICATION_IMAGE_ZIP_TMP_DIR', APP_DIR . '/tmp');
define('APP_CONST_PUBLICATION_IMAGE_ZIP_MAX_NUM', 50);

define('APP_CONST_PROFILE_IAMGE_MAX_SIZE', 512000);
define('APP_CONST_PROFILE_IMAGE_EXT_TXT', 'GIF、JPEG、PNG');
define('APP_CONST_PROFILE_IMAGE_EXT_REG', '/^(gif|jpe?g|png)$/i');
define('APP_CONST_PROFILE_IMAGE_PATH', '/profile_images/[login]');
define('APP_CONST_PROFILE_IMAGE_DIR', APP_WWW_DIR.APP_CONST_PROFILE_IMAGE_PATH);
define('APP_CONST_PROFILE_IMAGE_TMP_DIR', APP_WWW_DIR.'/tmp');
define('APP_CONST_PROFILE_IMAGE_BIGGER_SIZE', 128);
define('APP_CONST_PROFILE_IMAGE_NORMAL_SIZE', 73);
define('APP_CONST_PROFILE_IMAGE_SMALL', 48);

define('APP_CONST_IMPORT_FILE_MAX_SIZE', 10485760);
define('APP_CONST_IMPORT_FILE_EXT_TXT', 'ePub形式');
define('APP_CONST_IMPORT_FILE_EXT_REG', '/^(epub)$/i');
define('APP_CONST_IMPORT_FILE_TMP_DIR', APP_DIR . '/tmp');

define('APP_CONST_BASE_FONT_FAMILY', '"Hiragino Kaku Gothic Pro","ヒラギノ角ゴ Pro W3","Meiryo","メイリオ","Osaka","MS Gothic",arial,helvetica,clean,sans-serif');
define('APP_CONST_SERIF_FONT_FAMILY', '"Hiragino Mincho Pro","ヒラギノ明朝 Pro W3","Osaka－等幅","MS Mincho",courier,clean,serif');

/** 認証 */
define('APP_CONST_USER_AUTH_NAME', 'userInfo');
define('APP_CONST_USER_AUTH_TIME', 86400);
define('APP_CONST_USER_LOGIN_FIRST_PAGE', '/user/edit/');

define('APP_CONST_SECURITY_CODE_NAME', 'security_code');
define('APP_CONST_SECURITY_TOKEN_NAME', 'security_token');

define('APP_CONST_TWITTER_OAUTH_CONSUMER_KEY', '');
define('APP_CONST_TWITTER_OAUTH_CONSUMER_SECRET', '');
define('APP_CONST_TWITTER_OAUTH_CALLBACK_PATH', 'twitter/signin');

define('APP_CONST_MIXI_DEV_KEY', '');

define('APP_CONST_OPENID_STORE_DIR', APP_CONST_TMP_DIR);
define('APP_CONST_OPENID_MIXI', 'https://mixi.jp/');
define('APP_CONST_OPENID_MIXI_CRT', APP_DIR.'/libs/crt/mixi.jp.crt');
define('APP_CONST_OPENID_CALLBACK_PATH', 'openid/signin');
define('APP_CONST_OPENID_TRUST_DIR', 'openid/');

class AppConst
{
	public static $book_category = array(
		1 => 'ビジネス',
		2 => 'ライフスタイル',
		3 => '漫画',
		4 => '童話・絵本',
		5 => '写真',
		6 => '純文学',
		7 => '恋愛',
		8 => '青春・友情',
		9 => 'ミステリー',
		10 => 'ファンタジー',
		11 => 'SF',
		12 => 'ホラー',
		13 => '歴史',
		14 => 'イラスト',
		15 => '詩',
		16 => 'エッセイ',
		17 => '学習・研究',
		18 => 'ノンフィクション',
		19 => 'テクノロジー',
		20 => 'レシピ',
		21 => 'ノウハウ',
		22 => 'マニュアル',
		23 => 'インタビュー',
		24 => 'アダルト',
		999 => 'その他'
	);

	public static $gender = array(
		1 => '男性',
		2 => '女性'
	);

	public static $commentFlag = array(
		1 => '許可する',
		2 => '拒否する'
	);

	public static $publicFlag = array(
		0 => '非公開',
		1 => '公開'
	);

	public static $pageStatus = array(
		0 => '完成',
		1 => '下書き'
	);

	public static $notLogin = array(
		'book',
		'reader',
		'list',
		'user',
		'contact',
		'login',
		'logout',
		'rule',
		'privacy',
		'about',
		'account',
		'mypage',
		'admin',
		'help',
		'info',
		'spotword',
		'blog',
		'search',
		'service',
		'default',
		'openid',
		'twitter',
		'facebook',
		'mixi',
		'livedoor',
		'yahoo'
	);

	public static $notIp = array(
		'^127\.0\.0\.1$'
	);

	public static $notUserAgent = array(
		'$_agentname',
		'Baiduspider',
		'bingbot',
		'Butterfly',
		'facebookexternalhit',
		'Googlebot',
		'Hatena',
		'Hatena::Bookmark',
		'HatenaScreenshot',
		'JS-Kit',
		'libwww-perl',
		'livedoor ScreenShot',
		'Mediapartners-Google',
		'mixi-check',
		'NHN Corp.',
		'NING',
		'NjuiceBot',
		'OneRiot',
		'PostRank',
		'PycURL',
		'Python-urllib',
		'TweetmemeBot',
		'Twib::Crawler',
		'Twitterbot',
		'Twitturly',
		'UnwindFetchor',
		'Voyager',
		'WWW::Document',
		'Y!J-AGENT',
		'Yahoo! Slurp',
		'Yeti/'
	);
}

function sw_get_profile_image(&$userInfo, $size_type='normal')
{
	if ($size_type == 'bigger') {
		$col = 'profile_b_path';
	} else if ($size_type == 'small') {
		$col = 'profile_s_path';
	} else {
		$col = 'profile_path';
	}
	if (empty($userInfo[$col])) {
		$num = substr($userInfo['user_id'], -1);
		if ($num>4) $num -= 5;
		$img = 'default/default_'.$num.'_'.$size_type.'.png';
	} else {
		$img = $userInfo['login'].'/'.$userInfo[$col];
	}
	return '/profile_images/'.$img;
}
function sw_convert_contents_image($matches)
{
	$img_tag = $matches[1];
	if (preg_match('/ src="(\/file\/[^"]+)"/i', $img_tag, $m)) {
		$img_src = $m[1];
		$imgpath = APP_WWW_DIR.$img_src;
		if (file_exists($imgpath)) {
			$maxwidth = 540;
			$sizeinfo = getimagesize($imgpath);
			$w = $sizeinfo[0];
			$h = $sizeinfo[1];
			if ($w > $maxwidth) {
				$minrate = $maxwidth / $w;
				$maxheight = (int)($h * $minrate);
				$img_tag = preg_replace(array('/ width="[0-9]+"/i', '/ height="[0-9]+"/i'), '', $img_tag);
				return str_replace('<img ', '<img width="'.$maxwidth.'" height="'.$maxheight.'" ', $img_tag);
			}
		}
	}
	return $img_tag;
}
function sw_is_notip()
{
	$ip = trim($_SERVER['REMOTE_ADDR']);
	$notip = AppConst::$notIp;
	if (empty($ip) || count($notip)==0) return false;
	foreach ($notip as $not) {
		if (preg_match('/'.$not.'/', $ip)) return true;
	}
	return false;
}
?>
