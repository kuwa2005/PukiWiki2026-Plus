<?php
// PukiWiki2026 Plus — minimal HTML fallback (no React)
// Enable: touch pukiwiki/cache/.skin-minimal-fallback on server

pkwk_common_headers();
header('Cache-control: no-cache');
header('Pragma: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);

$__nofollow = ! empty($nofollow);
$__referrer = isset($html_meta_referrer_policy) ? $html_meta_referrer_policy : '';

?>
<!DOCTYPE html>
<html lang="<?php echo LANG ?>">
<head>
 <meta charset="<?php echo CONTENT_CHARSET ?>" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php if ($__nofollow || ! $is_read) { ?> <meta name="robots" content="NOINDEX,NOFOLLOW" /><?php } ?>
<?php if ($__referrer !== '') { ?> <meta name="referrer" content="<?php echo htmlsc($__referrer) ?>" /><?php } ?>
 <title><?php echo $title ?> - <?php echo $page_title ?></title>
 <link rel="stylesheet" href="<?php echo pkwk_effective_skin_dir() ?>pukiwiki.css" />
<?php echo $head_tag ?>
</head>
<body>
<?php if ($menu) { ?><aside id="menubar"><?php echo $menu ?></aside><?php } ?>
<article id="body"><?php echo $body ?></article>
<?php if ($rightbar) { ?><aside id="rightbar"><?php echo $rightbar ?></aside><?php } ?>
<?php if ($is_page) { ?>
 <h1 class="title"><a href="<?php echo $link['canonical_url'] ?>"><?php echo $page ?></a></h1>
<?php } ?>
<?php if ($notes != '') { ?><section id="note"><?php echo $notes ?></section><?php } ?>
<?php if ($attaches != '') { ?><section id="attach"><?php echo $hr ?><?php echo $attaches ?></section><?php } ?>
<footer id="footer">
<?php if ($lastmodified != '') { ?><p>Last-modified: <?php echo $lastmodified ?></p><?php } ?>
<?php if ($related != '') { ?><p>Link: <?php echo $related ?></p><?php } ?>
 <p>Site admin: <a href="<?php echo $modifierlink ?>"><?php echo $modifier ?></a></p>
 <p><?php echo function_exists('pkwk_footer_credits_html') ? pkwk_footer_credits_html() : S_COPYRIGHT ?></p>
</footer>
</body>
</html>
