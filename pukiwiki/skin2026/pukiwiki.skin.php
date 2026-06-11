<?php
// PukiWiki2026 Plus — skin2026
// Copyright 2026 PukiWiki2026 Plus contributors
// License: GPL v2 or (at your option) any later version
//
// Modern 2026 skin for PukiWiki / PukiWiki2026

// ------------------------------------------------------------
// Settings

$_IMAGE['skin']['logo']     = 'pukiwiki.png';
$_IMAGE['skin']['favicon']  = '';

if (! defined('SKIN_DEFAULT_DISABLE_TOPICPATH'))
	define('SKIN_DEFAULT_DISABLE_TOPICPATH', 0);

if (! defined('PKWK_SKIN_SHOW_NAVBAR'))
	define('PKWK_SKIN_SHOW_NAVBAR', 1);

if (! defined('PKWK_SKIN_SHOW_TOOLBAR'))
	define('PKWK_SKIN_SHOW_TOOLBAR', 1);

// ------------------------------------------------------------
// Code start

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

pkwk_common_headers();
header('Cache-control: no-cache');
header('Pragma: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);

function skin2026_nav_link($key, $value = '', $javascript = '') {
	$lang = & $GLOBALS['_LANG']['skin'];
	$link = & $GLOBALS['_LINK'];
	if (! isset($lang[$key])) { echo 'LANG NOT FOUND'; return FALSE; }
	if (! isset($link[$key])) { echo 'LINK NOT FOUND'; return FALSE; }

	echo '<a class="skin2026-nav-link" href="' . $link[$key] . '" ' . $javascript . '>' .
		(($value === '') ? $lang[$key] : $value) .
		'</a>';
	return TRUE;
}

function skin2026_toolbar($key, $x = 20, $y = 20) {
	$lang  = & $GLOBALS['_LANG']['skin'];
	$link  = & $GLOBALS['_LINK'];
	$image = & $GLOBALS['_IMAGE']['skin'];
	if (! isset($lang[$key]) ) { echo 'LANG NOT FOUND';  return FALSE; }
	if (! isset($link[$key]) ) { echo 'LINK NOT FOUND';  return FALSE; }
	if (! isset($image[$key])) { echo 'IMAGE NOT FOUND'; return FALSE; }

	echo '<a class="skin2026-toolbar-link" href="' . $link[$key] . '" title="' . $lang[$key] . '">' .
		'<img src="' . IMAGE_DIR . $image[$key] . '" width="' . $x . '" height="' . $y . '" ' .
			'alt="' . $lang[$key] . '" />' .
		'</a>';
	return TRUE;
}

?>
<!DOCTYPE html>
<html lang="<?php echo LANG ?>">
<head>
 <meta charset="<?php echo CONTENT_CHARSET ?>" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php if ($nofollow || ! $is_read)  { ?> <meta name="robots" content="NOINDEX,NOFOLLOW" /><?php } ?>
<?php if ($html_meta_referrer_policy) { ?> <meta name="referrer" content="<?php echo htmlsc(html_meta_referrer_policy) ?>" /><?php } ?>

 <title><?php echo $title ?> - <?php echo $page_title ?></title>

<?php if ($image['favicon'] !== '') { ?>
 <link rel="icon" href="<?php echo $image['favicon'] ?>" />
<?php } ?>
 <link rel="stylesheet" href="<?php echo SKIN_DIR ?>pukiwiki.css" />
 <link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo $link['rss'] ?>" />
 <script src="<?php echo SKIN_DIR ?>skin2026.js" defer></script>
 <script src="<?php echo SKIN_DIR ?>main.js" defer></script>
 <script src="<?php echo SKIN_DIR ?>search2.js" defer></script>
 <script src="<?php echo SKIN_DIR ?>ref-popup.js" defer></script>
<?php if (arg_check('edit')) { ?>
 <script src="<?php echo SKIN_DIR ?>edit-dragdrop.js" defer></script>
<?php } ?>

<?php echo $head_tag ?>
</head>
<body class="skin2026">
<?php echo $html_scripting_data ?>

<header id="skin2026-header" class="skin2026-header">
 <div class="skin2026-header-inner">
  <a class="skin2026-brand" href="<?php echo $link['top'] ?>">
   <img class="skin2026-logo" src="<?php echo IMAGE_DIR . $image['logo'] ?>" width="48" height="48" alt="PukiWiki" />
   <span class="skin2026-site-title"><?php echo $title ?></span>
  </a>

  <div class="skin2026-header-actions">
   <button type="button" id="skin2026-theme-toggle" class="skin2026-theme-toggle" aria-label="Toggle color theme" aria-pressed="false" title="Toggle theme">&#9789;</button>
   <button type="button" id="skin2026-nav-toggle" class="skin2026-nav-toggle" aria-expanded="false" aria-controls="skin2026-nav-panel" aria-label="Open navigation">
    <span class="skin2026-nav-toggle-bar"></span>
    <span class="skin2026-nav-toggle-bar"></span>
    <span class="skin2026-nav-toggle-bar"></span>
   </button>
  </div>
 </div>

<?php if ($is_page) { ?>
 <div class="skin2026-page-meta">
  <h1 class="skin2026-page-title"><?php echo $page ?></h1>
  <?php if(SKIN_DEFAULT_DISABLE_TOPICPATH) { ?>
   <a class="skin2026-canonical" href="<?php echo $link['canonical_url'] ?>"><?php echo $link['canonical_url'] ?></a>
  <?php } else { ?>
   <nav class="skin2026-topicpath" aria-label="Topic path">
   <?php require_once(PLUGIN_DIR . 'topicpath.inc.php'); echo plugin_topicpath_inline(); ?>
   </nav>
  <?php } ?>
 </div>
<?php } ?>
</header>

<?php if(PKWK_SKIN_SHOW_NAVBAR) { ?>
<nav id="skin2026-nav-panel" class="skin2026-nav" aria-label="Site navigation">
 <div class="skin2026-nav-group">
  <?php skin2026_nav_link('top') ?>
 </div>

<?php if ($is_page) { ?>
 <div class="skin2026-nav-group">
  <?php if ($rw) { ?>
   <?php skin2026_nav_link('edit') ?>
   <?php if ($is_read && $function_freeze) { ?>
    <?php (! $is_freeze) ? skin2026_nav_link('freeze') : skin2026_nav_link('unfreeze') ?>
   <?php } ?>
  <?php } ?>
  <?php skin2026_nav_link('diff') ?>
  <?php if ($do_backup) { skin2026_nav_link('backup') } ?>
  <?php if ($rw && (bool)ini_get('file_uploads')) { skin2026_nav_link('upload') } ?>
  <?php skin2026_nav_link('reload') ?>
 </div>
<?php } ?>

 <div class="skin2026-nav-group">
  <?php if ($rw) { skin2026_nav_link('new') } ?>
  <?php skin2026_nav_link('list') ?>
  <?php if (arg_check('list')) { skin2026_nav_link('filelist') } ?>
  <?php skin2026_nav_link('search') ?>
  <?php skin2026_nav_link('recent') ?>
  <?php skin2026_nav_link('help') ?>
  <?php if ($enable_login) { skin2026_nav_link('login') } ?>
  <?php if ($enable_logout) { skin2026_nav_link('logout') } ?>
 </div>
</nav>
<?php } ?>

<main id="skin2026-main" class="skin2026-main">
 <div id="contents" class="skin2026-contents">
<?php if ($menu) { ?>
  <aside id="menubar" class="skin2026-sidebar skin2026-sidebar--left"><?php echo $menu ?></aside>
<?php } ?>

  <article id="body" class="skin2026-body"><?php echo $body ?></article>

<?php if ($rightbar) { ?>
  <aside id="rightbar" class="skin2026-sidebar skin2026-sidebar--right"><?php echo $rightbar ?></aside>
<?php } ?>
 </div>

<?php if ($notes != '') { ?>
 <section id="note" class="skin2026-note"><?php echo $notes ?></section>
<?php } ?>

<?php if ($attaches != '') { ?>
 <section id="attach" class="skin2026-attach">
  <?php echo $hr ?>
  <?php echo $attaches ?>
 </section>
<?php } ?>
</main>

<?php if (PKWK_SKIN_SHOW_TOOLBAR) { ?>
<footer id="skin2026-toolbar-wrap" class="skin2026-toolbar-wrap">
 <div id="toolbar" class="skin2026-toolbar">
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
$_IMAGE['skin']['rss10']    = & $_IMAGE['skin']['rss'];
$_IMAGE['skin']['rss20']    = 'rss20.png';
$_IMAGE['skin']['rdf']      = 'rdf.png';
?>
  <?php skin2026_toolbar('top') ?>
<?php if ($is_page) { ?>
  <?php if ($rw) { ?>
   <?php skin2026_toolbar('edit') ?>
   <?php if ($is_read && $function_freeze) { ?>
    <?php if (! $is_freeze) { skin2026_toolbar('freeze'); } else { skin2026_toolbar('unfreeze'); } ?>
   <?php } ?>
  <?php } ?>
  <?php skin2026_toolbar('diff') ?>
  <?php if ($do_backup) { skin2026_toolbar('backup') } ?>
  <?php if ($rw) { ?>
   <?php if ((bool)ini_get('file_uploads')) { skin2026_toolbar('upload') } ?>
   <?php skin2026_toolbar('copy') ?>
   <?php skin2026_toolbar('rename') ?>
  <?php } ?>
  <?php skin2026_toolbar('reload') ?>
<?php } ?>
  <?php if ($rw) { skin2026_toolbar('new') } ?>
  <?php skin2026_toolbar('list') ?>
  <?php skin2026_toolbar('search') ?>
  <?php skin2026_toolbar('recent') ?>
  <?php skin2026_toolbar('help') ?>
  <?php skin2026_toolbar('rss10', 36, 14) ?>
 </div>
</footer>
<?php } ?>

<footer id="footer" class="skin2026-footer">
<?php if ($lastmodified != '') { ?>
 <p id="lastmodified" class="skin2026-lastmodified">Last-modified: <?php echo $lastmodified ?></p>
<?php } ?>
<?php if ($related != '') { ?>
 <p id="related" class="skin2026-related">Link: <?php echo $related ?></p>
<?php } ?>
 <p class="skin2026-admin">Site admin: <a href="<?php echo $modifierlink ?>"><?php echo $modifier ?></a></p>
 <p class="skin2026-credits"><?php echo pkwk_footer_credits_html() ?></p>
</footer>
</body>
</html>
