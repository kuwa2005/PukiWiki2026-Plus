<?php
/**
 * PukiWiki2026 Plus — React スキン診断（レンタルサーバー向け）
 *
 * URL: https://example.com/diag-skin.php?token=plus-skin-diag-2026
 * 診断後は本ファイルを削除してください。
 *
 * pukiwiki/.htaccess が /pukiwiki/tools/ を 403 にするため DocumentRoot 直下に配置。
 */
header('Content-Type: text/html; charset=UTF-8');
header('Cache-Control: no-store');

const PKWK_DIAG_TOKEN = 'plus-skin-diag-2026';

$token = isset($_GET['token']) ? (string)$_GET['token'] : '';
if ($token !== PKWK_DIAG_TOKEN) {
	http_response_code(403);
	echo '<!DOCTYPE html><html><body><h1>403 Forbidden</h1>'
		. '<p>token クエリが必要です。例: <code>?token=' . PKWK_DIAG_TOKEN . '</code></p></body></html>';
	exit;
}

define('DATA_HOME', __DIR__ . '/pukiwiki/');
define('LIB_DIR', DATA_HOME . 'lib/');
define('PKWK_DIAG_DELETE_HINT', 'diag-skin.php（DocumentRoot 直下）');

require LIB_DIR . 'skin-diag.php';
