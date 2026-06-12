<?php
/**
 * PukiWiki2026 Plus — React スキン診断（本体）
 *
 * エントリ: DocumentRoot/diag-skin.php（推奨）
 * 事前に DATA_HOME / LIB_DIR を定義すること。
 */
if (! defined('DATA_HOME') || ! defined('LIB_DIR')) {
	http_response_code(500);
	echo 'skin-diag.php: DATA_HOME and LIB_DIR must be defined';
	exit;
}

if (! defined('PKWK_DIAG_DELETE_HINT')) {
	define('PKWK_DIAG_DELETE_HINT', 'diag-skin.php（DocumentRoot 直下）');
}

if (! defined('PKWK_DEBUG')) {
	define('PKWK_DEBUG', 1);
}
if (! defined('MUTIME')) {
	define('MUTIME', microtime(true));
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

$results = array();
$fatal_capture = '';

register_shutdown_function(function () use (&$fatal_capture) {
	$e = error_get_last();
	if (! $e) {
		return;
	}
	$fatal_types = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR);
	if (! in_array($e['type'], $fatal_types, true)) {
		return;
	}
	$fatal_capture = $e['message'] . ' in ' . $e['file'] . ':' . $e['line'];
});

function diag_row($label, $ok, $detail = '')
{
	global $results;
	$results[] = array('label' => $label, 'ok' => (bool)$ok, 'detail' => $detail);
}

function diag_h($s)
{
	return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

diag_row('PHP version', true, PHP_VERSION);

$index_php = dirname(DATA_HOME) . '/index.php';
diag_row('readable: index.php', is_readable($index_php), $index_php);

foreach (array(
	'pukiwiki.ini.php' => DATA_HOME . 'pukiwiki.ini.php',
	'skin/pukiwiki.skin.php' => DATA_HOME . 'skin/pukiwiki.skin.php',
	'lib/func.php' => LIB_DIR . 'func.php',
	'lib/html.php' => LIB_DIR . 'html.php',
	'lib/init.php' => LIB_DIR . 'init.php',
	'cache/' => DATA_HOME . 'cache/',
) as $label => $path) {
	diag_row(
		'readable: ' . $label,
		is_readable($path),
		$path . (is_writable($path) ? ' (writable)' : '')
	);
}

$bootstrap_ok = false;
$bootstrap_error = '';

try {
	require_once(LIB_DIR . 'func.php');
	require_once(LIB_DIR . 'file.php');
	require_once(LIB_DIR . 'plugin.php');
	require_once(LIB_DIR . 'html.php');
	require_once(LIB_DIR . 'backup.php');
	require_once(LIB_DIR . 'convert_html.php');
	require_once(LIB_DIR . 'make_link.php');
	require_once(LIB_DIR . 'diff.php');
	require_once(LIB_DIR . 'config.php');
	require_once(LIB_DIR . 'link.php');
	require_once(LIB_DIR . 'auth.php');
	require_once(LIB_DIR . 'comment.php');
	require_once(LIB_DIR . 'security.php');
	require_once(LIB_DIR . 'csrf.php');
	require_once(LIB_DIR . 'proxy.php');
	require_once(LIB_DIR . 'akismet.php');
	require_once(LIB_DIR . 'captcha.php');
	require_once(LIB_DIR . 'spamfilter.php');
	if (! extension_loaded('mbstring')) {
		require_once(LIB_DIR . 'mbstring.php');
	}
	require_once(LIB_DIR . 'init.php');
	$bootstrap_ok = true;
} catch (Throwable $t) {
	$bootstrap_error = $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine();
}

diag_row('PukiWiki bootstrap (init.php)', $bootstrap_ok, $bootstrap_error ?: 'OK');

if ($bootstrap_ok) {
	diag_row('function pkwk_resolve_skin_file', function_exists('pkwk_resolve_skin_file'));
	diag_row('function pkwk_effective_skin_dir', function_exists('pkwk_effective_skin_dir'));
	diag_row('function pkwk_footer_credits_html', function_exists('pkwk_footer_credits_html'));

	$skin_file = function_exists('pkwk_resolve_skin_file')
		? pkwk_resolve_skin_file()
		: (defined('SKIN_FILE') ? SKIN_FILE : DATA_HOME . 'skin/pukiwiki.skin.php');
	diag_row('resolved SKIN_FILE', is_readable($skin_file), $skin_file);

	global $http_response_custom_headers, $html_meta_referrer_policy, $nofollow;
	diag_row(
		'$http_response_custom_headers is array',
		isset($http_response_custom_headers) && is_array($http_response_custom_headers),
		isset($http_response_custom_headers) ? ('count=' . count($http_response_custom_headers)) : 'unset (foreach TypeError の原因)'
	);
	diag_row('$nofollow isset', isset($nofollow), isset($nofollow) ? (string)$nofollow : 'unset');
	diag_row(
		'$html_meta_referrer_policy isset',
		isset($html_meta_referrer_policy),
		isset($html_meta_referrer_policy) ? (string)$html_meta_referrer_policy : 'unset'
	);

	if (function_exists('skin_app_build_config')) {
		global $_LANG, $_LINK, $page_title, $function_freeze, $do_backup;
		$scope = array(
			'title' => 'Diag',
			'page' => isset($defaultpage) ? $defaultpage : 'FrontPage',
			'is_page' => true,
			'is_read' => true,
			'is_freeze' => false,
			'rw' => ! PKWK_READONLY,
			'menu' => false,
			'rightbar' => false,
			'lastmodified' => '',
			'enable_login' => false,
			'enable_logout' => false,
		);
		$config_ok = false;
		$config_detail = '';
		try {
			$cfg = skin_app_build_config($scope);
			$json = json_encode(
				$cfg,
				JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
			);
			$config_ok = ($json !== false);
			$config_detail = $config_ok
				? ('json bytes=' . strlen($json))
				: ('json_encode failed: ' . json_last_error_msg());
		} catch (Throwable $t) {
			$config_detail = $t->getMessage();
		}
		diag_row('skin_app_build_config + json_encode', $config_ok, $config_detail);
	} else {
		diag_row('skin_app_build_config', false, '関数未定義 — skin/pukiwiki.skin.php が未反映');
	}

	$render_ok = false;
	$render_detail = '';
	$render_bytes = 0;
	if (is_readable($skin_file)) {
		ob_start();
		try {
			$title = 'Diag';
			$page = isset($defaultpage) ? $defaultpage : 'FrontPage';
			$body = '<p>diag-skin render test</p>';
			$is_page = true;
			$is_read = true;
			$is_freeze = false;
			$rw = ! PKWK_READONLY;
			$menu = false;
			$rightbar = false;
			$lastmodified = '';
			$enable_login = false;
			$enable_logout = false;
			$notes = '';
			$attaches = '';
			$related = '';
			$head_tag = '';
			$html_scripting_data = '';
			$_LINK = array('rss' => '?cmd=rss', 'canonical_url' => '/');
			$_IMAGE = array('skin' => array());
			require($skin_file);
			$out = ob_get_clean();
			$render_bytes = strlen($out);
			$render_ok = ($render_bytes > 100);
			$render_detail = 'output bytes=' . $render_bytes;
		} catch (Throwable $t) {
			ob_end_clean();
			$render_detail = $t->getMessage() . ' in ' . $t->getFile() . ':' . $t->getLine();
		}
	}
	diag_row('full skin require() render', $render_ok, $render_detail);
}

if ($fatal_capture !== '') {
	diag_row('shutdown fatal capture', false, $fatal_capture);
}

$pass = true;
foreach ($results as $r) {
	if (! $r['ok']) {
		$pass = false;
	}
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
 <meta charset="UTF-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <meta name="robots" content="noindex,nofollow" />
 <title>PukiWiki Plus skin diag</title>
 <style>
  body { font-family: system-ui, sans-serif; margin: 1.5rem; line-height: 1.5; }
  h1 { font-size: 1.25rem; }
  table { border-collapse: collapse; width: 100%; max-width: 56rem; }
  th, td { border: 1px solid #ccc; padding: 0.4rem 0.6rem; text-align: left; vertical-align: top; }
  .ok { background: #e8f5e9; }
  .ng { background: #ffebee; }
  code { font-size: 0.9em; }
 </style>
</head>
<body>
 <h1>PukiWiki2026 Plus — スキン診断</h1>
 <p>DocumentRoot 配下 DATA_HOME: <code><?php echo diag_h(DATA_HOME) ?></code></p>
 <p>総合: <strong><?php echo $pass ? 'PASS（要目視確認）' : 'FAIL' ?></strong></p>
 <table>
  <thead><tr><th>項目</th><th>結果</th><th>詳細</th></tr></thead>
  <tbody>
<?php foreach ($results as $r) { ?>
   <tr class="<?php echo $r['ok'] ? 'ok' : 'ng' ?>">
    <td><?php echo diag_h($r['label']) ?></td>
    <td><?php echo $r['ok'] ? 'OK' : 'NG' ?></td>
    <td><?php echo diag_h($r['detail']) ?></td>
   </tr>
<?php } ?>
  </tbody>
 </table>
 <p>診断後は <code><?php echo diag_h(PKWK_DIAG_DELETE_HINT) ?></code> を削除してください。</p>
</body>
</html>
