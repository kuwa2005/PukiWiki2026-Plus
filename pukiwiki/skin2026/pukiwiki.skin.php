<?php
// PukiWiki2026 Plus — skin2026 (React shell)
// Copyright 2026 PukiWiki2026 Plus contributors
// License: GPL v2 or (at your option) any later version

$_IMAGE['skin']['logo']     = 'pukiwiki.png';
$_IMAGE['skin']['favicon']  = '';

if (! defined('SKIN_DEFAULT_DISABLE_TOPICPATH'))
	define('SKIN_DEFAULT_DISABLE_TOPICPATH', 0);

if (! defined('PKWK_SKIN_SHOW_NAVBAR'))
	define('PKWK_SKIN_SHOW_NAVBAR', 1);

if (! defined('PKWK_SKIN_SHOW_TOOLBAR'))
	define('PKWK_SKIN_SHOW_TOOLBAR', 1);

if (! defined('UI_LANG')) die('UI_LANG is not set');
if (! isset($_LANG)) die('$_LANG is not set');
if (! defined('PKWK_READONLY')) die('PKWK_READONLY is not set');

$lang  = & $_LANG['skin'];
$link  = & $_LINK;
$image = & $_IMAGE['skin'];
$rw    = ! PKWK_READONLY;

$menu = arg_check('read') && exist_plugin_convert('menu') ? do_plugin_convert('menu') : FALSE;
$rightbar = FALSE;
if (arg_check('read') && exist_plugin_convert('rightbar')) {
	$rightbar = do_plugin_convert('rightbar');
}

function skin2026_nav_item($key, $icon = 'link') {
	$lang = & $GLOBALS['_LANG']['skin'];
	$link = & $GLOBALS['_LINK'];
	if (! isset($lang[$key]) || ! isset($link[$key])) return NULL;
	return array(
		'key'   => $key,
		'label' => $lang[$key],
		'href'  => $link[$key],
		'icon'  => $icon,
	);
}

function skin2026_build_config() {
	global $title, $page_title, $page, $is_page, $is_read, $lang, $link, $rw;
	global $function_freeze, $is_freeze, $do_backup, $enable_login, $enable_logout;
	global $menu, $rightbar, $lastmodified;

	$labels = array();
	foreach ($lang as $k => $v) {
		if (is_string($v)) $labels[$k] = $v;
	}

	$nav = array();

	if (PKWK_SKIN_SHOW_NAVBAR) {
		$page_items = array();
		$site_items = array();

		$item = skin2026_nav_item('top', 'home');
		if ($item) $site_items[] = $item;

		if ($is_page) {
			if ($rw) {
				$item = skin2026_nav_item('edit', 'edit');
				if ($item) $page_items[] = $item;
				if ($is_read && $function_freeze) {
					$item = skin2026_nav_item($is_freeze ? 'unfreeze' : 'freeze', 'freeze');
					if ($item) $page_items[] = $item;
				}
			}
			$item = skin2026_nav_item('diff', 'diff');
			if ($item) $page_items[] = $item;
			if ($do_backup) {
				$item = skin2026_nav_item('backup', 'backup');
				if ($item) $page_items[] = $item;
			}
			if ($rw && (bool)ini_get('file_uploads')) {
				$item = skin2026_nav_item('upload', 'upload');
				if ($item) $page_items[] = $item;
			}
			$item = skin2026_nav_item('reload', 'reload');
			if ($item) $page_items[] = $item;
		}

		if ($page_items) {
			$nav[] = array(
				'id'    => 'page',
				'label' => isset($lang['edit']) ? 'Page' : 'Page',
				'items' => $page_items,
			);
		}

		if ($rw) {
			$item = skin2026_nav_item('new', 'new');
			if ($item) $site_items[] = $item;
		}
		$item = skin2026_nav_item('list', 'list');
		if ($item) $site_items[] = $item;
		if (arg_check('list')) {
			$item = skin2026_nav_item('filelist', 'list');
			if ($item) $site_items[] = $item;
		}
		$item = skin2026_nav_item('search', 'search');
		if ($item) $site_items[] = $item;
		$item = skin2026_nav_item('recent', 'clock');
		if ($item) $site_items[] = $item;
		$item = skin2026_nav_item('help', 'help');
		if ($item) $site_items[] = $item;
		if ($enable_login) {
			$item = skin2026_nav_item('login', 'login');
			if ($item) $site_items[] = $item;
		}
		if ($enable_logout) {
			$item = skin2026_nav_item('logout', 'logout');
			if ($item) $site_items[] = $item;
		}

		$nav[] = array(
			'id'    => 'site',
			'label' => 'Site',
			'items' => $site_items,
		);
	}

	$links = array();
	foreach ($link as $k => $v) {
		if (is_string($v)) $links[$k] = $v;
	}

	return array(
		'siteTitle'    => $title,
		'pageTitle'    => $page_title,
		'page'         => $is_page ? $page : '',
		'isPage'       => (bool)$is_page,
		'isRead'       => (bool)$is_read,
		'isEdit'       => arg_check('edit'),
		'rw'           => $rw,
		'hasMenu'      => (bool)$menu,
		'hasRightbar'  => (bool)$rightbar,
		'labels'       => $labels,
		'links'        => $links,
		'nav'          => $nav,
		'lastmodified' => $lastmodified,
	);
}

$skin2026_config = skin2026_build_config();

pkwk_common_headers();
header('Cache-control: no-cache');
header('Pragma: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);

function skin2026_toolbar_hidden($key, $x = 20, $y = 20) {
	$lang  = & $GLOBALS['_LANG']['skin'];
	$link  = & $GLOBALS['_LINK'];
	$image = & $GLOBALS['_IMAGE']['skin'];
	if (! isset($lang[$key]) || ! isset($link[$key]) || ! isset($image[$key])) return FALSE;
	echo '<a href="' . $link[$key] . '" title="' . $lang[$key] . '">' .
		'<img src="' . IMAGE_DIR . $image[$key] . '" width="' . $x . '" height="' . $y . '" alt="' . $lang[$key] . '" />' .
		'</a>';
	return TRUE;
}

?>
<!DOCTYPE html>
<html lang="<?php echo LANG ?>" class="s26-boot">
<head>
 <meta charset="<?php echo CONTENT_CHARSET ?>" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php if ($nofollow || ! $is_read)  { ?> <meta name="robots" content="NOINDEX,NOFOLLOW" /><?php } ?>
<?php if ($html_meta_referrer_policy) { ?> <meta name="referrer" content="<?php echo htmlsc(html_meta_referrer_policy) ?>" /><?php } ?>

 <title><?php echo $title ?> - <?php echo $page_title ?></title>

<?php if ($image['favicon'] !== '') { ?>
 <link rel="icon" href="<?php echo $image['favicon'] ?>" />
<?php } ?>
 <link rel="stylesheet" href="<?php echo SKIN_DIR ?>dist/skin2026-app.css" />
 <link rel="stylesheet" href="<?php echo SKIN_DIR ?>pukiwiki.css" />
 <link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo $link['rss'] ?>" />

<?php echo $head_tag ?>
</head>
<body class="skin2026">
<?php echo $html_scripting_data ?>

<div id="s26-ssr" hidden aria-hidden="true">
<?php if ($menu) { ?>
 <aside id="menubar"><?php echo $menu ?></aside>
<?php } ?>

 <article id="body"><?php echo $body ?></article>

<?php if ($rightbar) { ?>
 <aside id="rightbar"><?php echo $rightbar ?></aside>
<?php } ?>

<?php if ($is_page) { ?>
 <div id="s26-page-title">
  <h1 class="title"><a href="<?php echo $link['canonical_url'] ?>" title="Backlinks"><?php echo $page ?></a></h1>
 </div>
 <nav id="s26-topicpath" aria-label="Topic path">
 <?php if(SKIN_DEFAULT_DISABLE_TOPICPATH) { ?>
  <a href="<?php echo $link['canonical_url'] ?>"><?php echo $link['canonical_url'] ?></a>
 <?php } else { ?>
  <?php require_once(PLUGIN_DIR . 'topicpath.inc.php'); echo plugin_topicpath_inline(); ?>
 <?php } ?>
 </nav>
<?php } ?>

<?php if ($notes != '') { ?>
 <section id="note"><?php echo $notes ?></section>
<?php } ?>

<?php if ($attaches != '') { ?>
 <section id="attach">
  <?php echo $hr ?>
  <?php echo $attaches ?>
 </section>
<?php } ?>

<footer id="footer">
<?php if ($lastmodified != '') { ?>
 <p id="lastmodified">Last-modified: <?php echo $lastmodified ?></p>
<?php } ?>
<?php if ($related != '') { ?>
 <p id="related">Link: <?php echo $related ?></p>
<?php } ?>
 <p class="s26-admin">Site admin: <a href="<?php echo $modifierlink ?>"><?php echo $modifier ?></a></p>
 <p class="s26-credits"><?php echo pkwk_footer_credits_html() ?></p>
</footer>

<?php if (PKWK_SKIN_SHOW_TOOLBAR) { ?>
 <div id="toolbar">
<?php
$_IMAGE['skin']['reload']   = 'reload.png';
$_IMAGE['skin']['new']      = 'new.png';
$_IMAGE['skin']['edit']     = 'edit.png';
$_IMAGE['skin']['freeze']   = 'freeze.png';
$_IMAGE['skin']['unfreeze'] = 'unfreeze.png';
$_IMAGE['skin']['diff']     = 'diff.png';
$_IMAGE['skin']['upload']   = 'file.png';
$_IMAGE['skin']['copy']     = 'copy.png';
$_IMAGE['skin']['rename']   = 'rename.png';
$_IMAGE['skin']['top']      = 'top.png';
$_IMAGE['skin']['list']     = 'list.png';
$_IMAGE['skin']['search']   = 'search.png';
$_IMAGE['skin']['recent']   = 'recentchanges.png';
$_IMAGE['skin']['backup']   = 'backup.png';
$_IMAGE['skin']['help']     = 'help.png';
$_IMAGE['skin']['rss']      = 'rss.png';
?>
  <?php skin2026_toolbar_hidden('top') ?>
<?php if ($is_page) { ?>
  <?php if ($rw) { ?>
   <?php skin2026_toolbar_hidden('edit') ?>
   <?php if ($is_read && $function_freeze) { ?>
    <?php if (! $is_freeze) { skin2026_toolbar_hidden('freeze'); } else { skin2026_toolbar_hidden('unfreeze'); } ?>
   <?php } ?>
  <?php } ?>
  <?php skin2026_toolbar_hidden('diff') ?>
  <?php if ($do_backup) { skin2026_toolbar_hidden('backup') } ?>
  <?php if ($rw) { ?>
   <?php if ((bool)ini_get('file_uploads')) { skin2026_toolbar_hidden('upload') } ?>
   <?php skin2026_toolbar_hidden('copy') ?>
   <?php skin2026_toolbar_hidden('rename') ?>
  <?php } ?>
  <?php skin2026_toolbar_hidden('reload') ?>
<?php } ?>
  <?php if ($rw) { skin2026_toolbar_hidden('new') } ?>
  <?php skin2026_toolbar_hidden('list') ?>
  <?php skin2026_toolbar_hidden('search') ?>
  <?php skin2026_toolbar_hidden('recent') ?>
  <?php skin2026_toolbar_hidden('help') ?>
 </div>
<?php } ?>
</div>

<div id="skin2026-root"></div>

<script type="application/json" id="s26-config"><?php
echo json_encode(
	$skin2026_config,
	JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
);
?></script>

<script src="<?php echo SKIN_DIR ?>dist/skin2026-app.js"></script>
<script src="<?php echo SKIN_DIR ?>main.js" defer></script>
<script src="<?php echo SKIN_DIR ?>search2.js" defer></script>
<script src="<?php echo SKIN_DIR ?>ref-popup.js" defer></script>
<?php if (arg_check('edit')) { ?>
<script src="<?php echo SKIN_DIR ?>edit-dragdrop.js" defer></script>
<?php } ?>
</body>
</html>
