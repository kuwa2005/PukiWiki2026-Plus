<?php
// PukiWiki2026 Plus — default skin (React shell)
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

function skin_app_nav_item($key, $icon = 'link') {
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

function skin_app_build_config(array $scope) {
	global $page_title, $function_freeze, $do_backup;
	$lang = & $GLOBALS['_LANG']['skin'];
	$link = & $GLOBALS['_LINK'];

	$title          = $scope['title'];
	$page           = $scope['page'];
	$is_page        = ! empty($scope['is_page']);
	$is_read        = ! empty($scope['is_read']);
	$is_freeze      = ! empty($scope['is_freeze']);
	$rw             = ! empty($scope['rw']);
	$menu           = isset($scope['menu']) ? $scope['menu'] : FALSE;
	$rightbar       = isset($scope['rightbar']) ? $scope['rightbar'] : FALSE;
	$lastmodified   = isset($scope['lastmodified']) ? $scope['lastmodified'] : '';
	$enable_login   = ! empty($scope['enable_login']);
	$enable_logout  = ! empty($scope['enable_logout']);

	$labels = array();
	foreach ($lang as $k => $v) {
		if (is_string($v)) $labels[$k] = $v;
	}

	$nav = array();

	if (PKWK_SKIN_SHOW_NAVBAR) {
		$page_items = array();
		$site_items = array();

		$item = skin_app_nav_item('top', 'home');
		if ($item) $site_items[] = $item;

		if ($is_page) {
			if ($rw) {
				$item = skin_app_nav_item('edit', 'edit');
				if ($item) $page_items[] = $item;
				if ($is_read && $function_freeze) {
					$item = skin_app_nav_item($is_freeze ? 'unfreeze' : 'freeze', 'freeze');
					if ($item) $page_items[] = $item;
				}
			}
			$item = skin_app_nav_item('diff', 'diff');
			if ($item) $page_items[] = $item;
			if ($do_backup) {
				$item = skin_app_nav_item('backup', 'backup');
				if ($item) $page_items[] = $item;
			}
			if ($rw && (bool)ini_get('file_uploads')) {
				$item = skin_app_nav_item('upload', 'upload');
				if ($item) $page_items[] = $item;
			}
			$item = skin_app_nav_item('reload', 'reload');
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
			$item = skin_app_nav_item('new', 'new');
			if ($item) $site_items[] = $item;
		}
		$item = skin_app_nav_item('list', 'list');
		if ($item) $site_items[] = $item;
		if (arg_check('list')) {
			$item = skin_app_nav_item('filelist', 'list');
			if ($item) $site_items[] = $item;
		}
		$item = skin_app_nav_item('search', 'search');
		if ($item) $site_items[] = $item;
		$item = skin_app_nav_item('recent', 'clock');
		if ($item) $site_items[] = $item;
		$item = skin_app_nav_item('help', 'help');
		if ($item) $site_items[] = $item;
		if ($enable_login) {
			$item = skin_app_nav_item('login', 'login');
			if ($item) $site_items[] = $item;
		}
		if ($enable_logout) {
			$item = skin_app_nav_item('logout', 'logout');
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
		'isLoggedIn'   => (bool)$enable_logout,
		'showToolbars' => ! $enable_login,
		'rw'           => $rw,
		'hasMenu'      => (bool)$menu,
		'hasRightbar'  => (bool)$rightbar,
		'labels'       => $labels,
		'links'        => $links,
		'nav'          => $nav,
		'lastmodified' => $lastmodified,
	);
}

$skin_app_config = skin_app_build_config(get_defined_vars());

pkwk_common_headers();
header('Cache-control: no-cache');
header('Pragma: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);

function skin_app_toolbar_hidden($key, $x = 20, $y = 20) {
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
<html lang="<?php echo LANG ?>" class="skin-app-boot">
<head>
 <meta charset="<?php echo CONTENT_CHARSET ?>" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php if ($nofollow || ! $is_read)  { ?> <meta name="robots" content="NOINDEX,NOFOLLOW" /><?php } ?>
<?php if ($html_meta_referrer_policy) { ?> <meta name="referrer" content="<?php echo htmlsc($html_meta_referrer_policy) ?>" /><?php } ?>

 <title><?php echo $title ?> - <?php echo $page_title ?></title>

<?php if ($image['favicon'] !== '') { ?>
 <link rel="icon" href="<?php echo $image['favicon'] ?>" />
<?php } ?>
 <link rel="stylesheet" href="<?php echo SKIN_DIR ?>dist/skin-app.css" />
 <link rel="stylesheet" href="<?php echo SKIN_DIR ?>pukiwiki.css" />
 <link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo $link['rss'] ?>" />

<?php echo $head_tag ?>
</head>
<body class="skin-app">
<?php echo $html_scripting_data ?>

<div id="skin-app-ssr" hidden aria-hidden="true">
<?php if ($menu) { ?>
 <aside id="menubar"><?php echo $menu ?></aside>
<?php } ?>

 <article id="body"><?php echo $body ?></article>

<?php if ($rightbar) { ?>
 <aside id="rightbar"><?php echo $rightbar ?></aside>
<?php } ?>

<?php if ($is_page) { ?>
 <div id="skin-app-page-title">
  <h1 class="title"><a href="<?php echo $link['canonical_url'] ?>" title="Backlinks"><?php echo $page ?></a></h1>
 </div>
 <nav id="skin-app-topicpath" aria-label="Topic path">
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
 <p class="skin-app-admin">Site admin: <a href="<?php echo $modifierlink ?>"><?php echo $modifier ?></a></p>
 <p class="skin-app-credits"><?php echo pkwk_footer_credits_html() ?></p>
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
  <?php skin_app_toolbar_hidden('top') ?>
<?php if ($is_page) { ?>
  <?php if ($rw) { ?>
   <?php skin_app_toolbar_hidden('edit') ?>
   <?php if ($is_read && $function_freeze) { ?>
    <?php if (! $is_freeze) { skin_app_toolbar_hidden('freeze'); } else { skin_app_toolbar_hidden('unfreeze'); } ?>
   <?php } ?>
  <?php } ?>
  <?php skin_app_toolbar_hidden('diff') ?>
  <?php if ($do_backup) { skin_app_toolbar_hidden('backup') } ?>
  <?php if ($rw) { ?>
   <?php if ((bool)ini_get('file_uploads')) { skin_app_toolbar_hidden('upload') } ?>
   <?php skin_app_toolbar_hidden('copy') ?>
   <?php skin_app_toolbar_hidden('rename') ?>
  <?php } ?>
  <?php skin_app_toolbar_hidden('reload') ?>
<?php } ?>
  <?php if ($rw) { skin_app_toolbar_hidden('new') } ?>
  <?php skin_app_toolbar_hidden('list') ?>
  <?php skin_app_toolbar_hidden('search') ?>
  <?php skin_app_toolbar_hidden('recent') ?>
  <?php skin_app_toolbar_hidden('help') ?>
 </div>
<?php } ?>
</div>

<div id="skin-app-root"></div>

<script type="application/json" id="skin-app-config"><?php
echo json_encode(
	$skin_app_config,
	JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
);
?></script>

<script src="<?php echo SKIN_DIR ?>dist/skin-app.js"></script>
<script src="<?php echo SKIN_DIR ?>main.js" defer></script>
<script src="<?php echo SKIN_DIR ?>search2.js" defer></script>
<script src="<?php echo SKIN_DIR ?>ref-popup.js" defer></script>
<?php if (arg_check('edit')) { ?>
<script src="<?php echo SKIN_DIR ?>edit-dragdrop.js" defer></script>
<?php } ?>
</body>
</html>
