<?php
//define('DIR_SEP', DIRECTORY_SEPARATOR);
define('SP_DIR', dirname(__FILE__));

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.SP_DIR);

define('SP_LIBS_DIR', SP_DIR.'/libs');
define('SP_CONF_DIR', SP_DIR.'/conf');
define('SP_PLUGINS_DIR', SP_LIBS_DIR.'/smarty/plugins');

define('SP_ACTION_DIR', APP_DIR.'/action');
define('SP_LOGIC_DIR', APP_DIR.'/logic');
define('SP_FORM_DIR', APP_DIR.'/form');
define('SP_DB_DIR', APP_DIR.'/db');
define('SP_DAO_DIR', APP_DIR.'/dao');
define('SP_CONTROLLER_DIR', APP_DIR.'/controllers');
define('SP_MODEL_DIR', APP_DIR.'/model');
define('SP_TEMPLATE_DIR', APP_DIR.'/templates');
define('SP_COMPILE_DIR', APP_DIR.'/templates_c');

define('SP_PAGE_DIR', '/page/');
define('SP_PAGE_ARG', 'page');
define('SP_ACTION_TAG', 'Action');
define('SP_ERROR_TEMPLATE', 'common/error');
define('SP_DEFAULT_TEMPLATE', 'common/default_page');

define('SP_CONF_DB_SERVER', 'db_server');
define('SP_CONF_DB_SCHEMA', 'db_schema');
define('SP_CONF_DB_USER', 'db_user');
define('SP_CONF_DB_PASSWORD', 'db_password');

define('SP_CONF_LOG_NAME', 'log_name');
define('SP_CONF_LOG_DIR', 'log_dir');
define('SP_CONF_LOG_LEVEL', 'log_level');
define('SP_CONF_LOG_TYPE', 'log_type');
define('SP_CONF_LOG_STDOUT', 'log_stdout');

define('SP_CONF_ERROR_TEMPLATE', 'error_template');
define('SP_CONF_DEFAULT_TEMPLATE', 'default_template');

mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");
//mb_detect_order("ASCII,JIS,UTF-8,eucjp-win,sjis-win");
mb_detect_order("ASCII,sjis-win,UTF-8,JIS,eucjp-win");
date_default_timezone_set('Asia/Tokyo');

?>