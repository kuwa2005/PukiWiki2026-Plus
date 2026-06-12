<?php
// PukiWiki2026 Plus - head plugin
// head.inc.php
// License: GPL v2 or (at your option) any later version
//
// Blog-style hero/header image for the main content pane.

define('PLUGIN_HEAD_USAGE', '#head([pagename/]attach-filename[,height-px])');
define('PLUGIN_HEAD_MAX_HEIGHT', 2000);
// Suffix check aligned with #ref (PLUGIN_REF_IMAGE) plus .webp
define('PLUGIN_HEAD_IMAGE_SUFFIX', '/\.(?:gif|png|jpe?g|webp)$/i');

/**
 * Image suffix test — prefer #ref's PLUGIN_REF_IMAGE when loaded.
 */
function plugin_head_is_image_filename($name)
{
	if (defined('PLUGIN_REF_IMAGE') && preg_match(PLUGIN_REF_IMAGE, $name)) {
		return TRUE;
	}
	return preg_match(PLUGIN_HEAD_IMAGE_SUFFIX, $name);
}

/**
 * Normalize full-width / half-width colons for attach name comparison.
 */
function plugin_head_normalize_colons($name)
{
	return str_replace('：', ':', $name);
}

/**
 * Candidate attach names (exact + colon variant).
 *
 * @return string[]
 */
function plugin_head_attach_name_candidates($attach_name)
{
	$attach_name = preg_replace('#^.*/#', '', $attach_name);
	$candidates = array($attach_name);
	$swapped = strtr($attach_name, array('：' => ':', ':' => '：'));
	if ($swapped !== $attach_name) {
		$candidates[] = $swapped;
	}
	return array_values(array_unique($candidates));
}

/**
 * Parse pagename/filename like #ref (ref.inc.php plugin_ref_body).
 *
 * @return array{attach_page:string,attach_name:string}|false
 */
function plugin_head_parse_attach_target($file_path, $page)
{
	$file_path = trim($file_path);
	if ($file_path === '') {
		return FALSE;
	}
	if (strpos($file_path, "\0") !== FALSE || strpos($file_path, '..') !== FALSE) {
		return FALSE;
	}

	$matches = null;
	if (preg_match('#^(.+)/([^/]+)$#', $file_path, $matches)) {
		if ($matches[1] === '.' || $matches[1] === '..') {
			$matches[1] .= '/';
		}
		$attach_name = $matches[2];
		$attach_page = get_fullname(strip_bracket($matches[1]), $page);
	} else {
		$attach_name = $file_path;
		$attach_page = $page;
	}

	$attach_name = preg_replace('#^.*/#', '', $attach_name);
	if ($attach_name === '') {
		return FALSE;
	}
	if (! is_pagename($attach_page)) {
		return FALSE;
	}

	return array('attach_page' => $attach_page, 'attach_name' => $attach_name);
}

/**
 * Resolve attach file on disk (#ref primary path + colon fallback via AttachPages).
 *
 * @return array{attach_name:string,disk_path:string}|false
 */
function plugin_head_lookup_disk($attach_page, $attach_name)
{
	if (! is_dir(UPLOAD_DIR)) {
		return FALSE;
	}

	// Primary: encode(page)_encode(name) — same as #ref / #img
	foreach (plugin_head_attach_name_candidates($attach_name) as $name) {
		$disk_path = UPLOAD_DIR . encode($attach_page) . '_' . encode($name);
		if (is_file($disk_path)) {
			return array('attach_name' => $name, 'disk_path' => $disk_path);
		}
	}

	// Fallback: scan attach index (handles rare encode/name mismatches)
	if (! class_exists('AttachPages', FALSE)) {
		exist_plugin('attach');
	}
	$pages = new AttachPages($attach_page, 0);
	if (! isset($pages->pages[$attach_page])) {
		return FALSE;
	}

	$normalized_want = array();
	foreach (plugin_head_attach_name_candidates($attach_name) as $name) {
		$normalized_want[plugin_head_normalize_colons($name)] = TRUE;
	}
	foreach (array_keys($pages->pages[$attach_page]->files) as $disk_name) {
		if (! isset($normalized_want[plugin_head_normalize_colons($disk_name)])) {
			continue;
		}
		$disk_path = UPLOAD_DIR . encode($attach_page) . '_' . encode($disk_name);
		if (is_file($disk_path)) {
			return array('attach_name' => $disk_name, 'disk_path' => $disk_path);
		}
	}

	return FALSE;
}

/**
 * @return object|false  url, attach_page, attach_name, disk_path — or ->_error
 */
function plugin_head_resolve($file_path, $page)
{
	exist_plugin('ref'); // load PLUGIN_REF_IMAGE when available

	$target = plugin_head_parse_attach_target($file_path, $page);
	if ($target === FALSE) {
		return (object)array('_error' => 'invalid_arg');
	}
	$attach_page = $target['attach_page'];
	$attach_name = $target['attach_name'];

	if (! plugin_head_is_image_filename($attach_name)) {
		return (object)array(
			'_error' => 'bad_ext',
			'attach_page' => $attach_page,
			'attach_name' => $attach_name,
		);
	}

	$found = plugin_head_lookup_disk($attach_page, $attach_name);
	if ($found === FALSE) {
		return (object)array(
			'_error' => 'not_found',
			'attach_page' => $attach_page,
			'attach_name' => $attach_name,
		);
	}
	$attach_name = $found['attach_name'];
	$disk_path = $found['disk_path'];

	$size = @getimagesize($disk_path);
	$allowed_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP);
	if (! is_array($size) || ! in_array($size[2], $allowed_types, TRUE)) {
		return (object)array(
			'_error' => 'bad_image',
			'attach_page' => $attach_page,
			'attach_name' => $attach_name,
		);
	}

	$url = get_base_uri() . '?plugin=attach' .
		'&refer=' . rawurlencode($attach_page) .
		'&openfile=' . rawurlencode($attach_name);

	return (object)array(
		'url' => $url,
		'attach_page' => $attach_page,
		'attach_name' => $attach_name,
		'disk_path' => $disk_path,
	);
}

/**
 * @return int|false  fixed height in px, or FALSE for auto
 */
function plugin_head_parse_height($raw)
{
	if (! isset($raw) || $raw === '') {
		return FALSE;
	}
	$raw = trim($raw);
	if (! preg_match('/^\d+$/', $raw)) {
		return FALSE;
	}
	$h = (int)$raw;
	if ($h < 1 || $h > PLUGIN_HEAD_MAX_HEIGHT) {
		return FALSE;
	}
	return $h;
}

function plugin_head_format_resolve_error($file_path, $page, $resolved)
{
	$hint = (strpos($file_path, '/') === FALSE)
		? '（別ページの添付なら ページ名/ファイル名 を指定）'
		: '';

	if (! is_object($resolved) || ! isset($resolved->_error)) {
		return 'File not found or invalid image: ' . $file_path .
			' at page "' . $page . '"' . $hint;
	}

	switch ($resolved->_error) {
	case 'bad_ext':
		return 'Unsupported image extension: ' . $resolved->attach_name .
			'（.jpg / .jpeg / .png / .gif / .webp のみ。ファイル名そのものを指定）';
	case 'bad_image':
		return 'Not a valid image file: ' . $resolved->attach_name .
			' at page "' . $resolved->attach_page . '"';
	case 'not_found':
		return 'File not found: ' . $resolved->attach_name .
			' at page "' . $resolved->attach_page . '"' . $hint;
	case 'invalid_arg':
	default:
		return 'Invalid argument: ' . $file_path;
	}
}

function plugin_head_render($args)
{
	global $vars;

	if (! func_num_args()) {
		return plugin_head_error('Usage: ' . PLUGIN_HEAD_USAGE);
	}

	$page = isset($vars['page']) ? $vars['page'] : '';
	$file_path = isset($args[0]) ? $args[0] : '';
	$resolved = plugin_head_resolve($file_path, $page);
	if ($resolved === FALSE || (is_object($resolved) && isset($resolved->_error))) {
		return plugin_head_error(plugin_head_format_resolve_error($file_path, $page, $resolved));
	}

	$height = plugin_head_parse_height(isset($args[1]) ? $args[1] : '');
	if (isset($args[1]) && $args[1] !== '' && $height === FALSE) {
		return plugin_head_error('Invalid height (positive integer up to ' . PLUGIN_HEAD_MAX_HEIGHT . '): ' . $args[1]);
	}

	$h_url = htmlsc($resolved->url);
	$alt = htmlsc($resolved->attach_name);

	if ($height === FALSE) {
		return <<<EOD
<figure class="plugin-head plugin-head--auto">
<img class="plugin-head__img" src="$h_url" alt="$alt" decoding="async" />
</figure>

EOD;
	}

	$h_px = (int)$height;
	return <<<EOD
<figure class="plugin-head plugin-head--cover" style="--plugin-head-h: {$h_px}px">
<img class="plugin-head__img" src="$h_url" alt="$alt" decoding="async" />
</figure>

EOD;
}

function plugin_head_error($message)
{
	return '#head(): ' . htmlsc($message) . "\n";
}

function plugin_head_convert()
{
	return plugin_head_render(func_get_args());
}

function plugin_head_inline()
{
	$out = plugin_head_render(func_get_args());
	if (strpos($out, '#head():') === 0) {
		return preg_replace('/^#head\(\):/', '&amp;head():', $out, 1);
	}
	return $out;
}
