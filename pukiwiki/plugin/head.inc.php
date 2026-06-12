<?php
// PukiWiki2026 Plus - head plugin
// head.inc.php
// License: GPL v2 or (at your option) any later version
//
// Blog-style hero/header image for the main content pane.

define('PLUGIN_HEAD_USAGE', '#head(attach-filename[,height-px])');
define('PLUGIN_HEAD_MAX_HEIGHT', 2000);
define('PLUGIN_HEAD_ALLOWED_EXT', '/^(?:jpe?g|png|gif|webp)$/i');

/**
 * @return object|false  url, attach_page, attach_name, disk_path
 */
function plugin_head_resolve($file_path, $page)
{
	$file_path = trim($file_path);
	if ($file_path === '') {
		return FALSE;
	}
	if (preg_match('/[\x00-\x1f\x7f]/', $file_path)) {
		return FALSE;
	}
	if (strpos($file_path, '..') !== FALSE) {
		return FALSE;
	}

	$matches = null;
	if (preg_match('#^(.+)/([^/]+)$#', $file_path, $matches)) {
		if ($matches[1] === '.' || $matches[1] === '..') {
			$matches[1] .= '/';
		}
		$attach_name = $matches[2];
		$attach_page = get_fullname($matches[1], $page);
	} else {
		$attach_name = $file_path;
		$attach_page = $page;
	}

	$attach_name = preg_replace('#^.*/#', '', $attach_name);
	if ($attach_name === '' || ! preg_match(PLUGIN_HEAD_ALLOWED_EXT, $attach_name)) {
		return FALSE;
	}
	if (! is_pagename($attach_page)) {
		return FALSE;
	}

	$disk_path = UPLOAD_DIR . encode($attach_page) . '_' . encode($attach_name);
	if (! is_file($disk_path)) {
		return FALSE;
	}

	$size = @getimagesize($disk_path);
	$allowed_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP);
	if (! is_array($size) || ! in_array($size[2], $allowed_types, TRUE)) {
		return FALSE;
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

function plugin_head_render($args)
{
	global $vars;

	if (! func_num_args()) {
		return plugin_head_error('Usage: ' . PLUGIN_HEAD_USAGE);
	}

	$page = isset($vars['page']) ? $vars['page'] : '';
	$file_path = isset($args[0]) ? $args[0] : '';
	$resolved = plugin_head_resolve($file_path, $page);
	if ($resolved === FALSE) {
		return plugin_head_error('File not found or invalid image: ' . $file_path);
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
