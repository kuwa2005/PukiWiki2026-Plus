<?php
// PukiWiki2026 Plus — rental-server skin error log (opt-in)
// Enable: create empty file pukiwiki/cache/.skin-diag-enabled
// Log:    pukiwiki/cache/skin-error.log (gitignored)
// Disable: delete .skin-diag-enabled and this log after debugging.

if (! defined('DATA_HOME')) {
	return;
}

function pkwk_skin_diag_log_path()
{
	return DATA_HOME . 'cache/skin-error.log';
}

function pkwk_skin_diag_is_enabled()
{
	return is_readable(DATA_HOME . 'cache/.skin-diag-enabled');
}

if (! pkwk_skin_diag_is_enabled()) {
	return;
}

@ini_set('log_errors', '1');
@ini_set('display_errors', '0');
error_reporting(E_ALL);

set_error_handler(function ($severity, $message, $file, $line) {
	if (! (error_reporting() & $severity)) {
		return false;
	}
	$line_out = date('c') . " [{$severity}] {$message} in {$file}:{$line}\n";
	@file_put_contents(pkwk_skin_diag_log_path(), $line_out, FILE_APPEND | LOCK_EX);
	return false;
});

register_shutdown_function(function () {
	$e = error_get_last();
	if (! $e) {
		return;
	}
	$fatal_types = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR);
	if (! in_array($e['type'], $fatal_types, true)) {
		return;
	}
	$line_out = date('c') . " [FATAL] {$e['message']} in {$e['file']}:{$e['line']}\n";
	@file_put_contents(pkwk_skin_diag_log_path(), $line_out, FILE_APPEND | LOCK_EX);
});
