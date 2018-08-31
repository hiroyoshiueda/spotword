<?php
if (strpos(__FILE__, '/main.jp-e-sora/') !== false) {
	// 本番
	define('APP_DIR', '/home/users/0/main.jp-e-sora/apps/spotword');
	define('APP_WWW_DIR', '/home/users/0/main.jp-e-sora/web/spotword.jp');
	define('APP_CONF', APP_DIR . '/conf/app.conf');
	define('APP_ENV', 'release');
} else {
	// 開発
	define('APP_DIR', dirname(dirname(__FILE__)));
	define('APP_WWW_DIR', APP_DIR.'/www');
	define('APP_CONF', APP_DIR . '/conf/app_dev.conf');
	define('APP_ENV', 'dev');
}
require APP_DIR . '/simplity/Simplity.php';
require APP_DIR . '/libs/AppConst.php';
Sp::init();

// BatchRankingDaily or
$classname = $argv[1];

require './BaseBatch.php';
require './'.$classname.'.php';
Sp::executeBatch($classname);
?>
