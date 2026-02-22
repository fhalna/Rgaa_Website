<!DOCTYPE html>
<html lang="<?= $lang ?>">
	<head>
		<meta charset="utf-8">
		<title><?= htmlspecialchars($pageTitle) ?></title>
<?php if (!empty($pageDescription)): ?>
		<meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
<?php endif; ?>
<?php if (!empty($noindex)): ?>
		<meta name="robots" content="noindex">
<?php endif; ?>
<?php if (!empty($canonicalUrl)): ?>
		<link rel="canonical" href="<?= SITE_URL . $canonicalUrl ?>">
<?php endif; ?>
<?php if (!empty($alternateUrls)): ?>
<?php foreach ($alternateUrls as $hreflang => $url): ?>
		<link rel="alternate" href="<?= SITE_URL . $url ?>" hreflang="<?= $hreflang ?>">
<?php endforeach; ?>
<?php endif; ?>
<?php if (!empty($googleVerification)): ?>
		<meta name="google-site-verification" content="<?= $googleVerification ?>">
<?php endif; ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="/favicon.ico">
		<link rel="stylesheet" href="/css/style.css">
<?php if (empty($noindex)): ?>
		<?php include INCLUDES_DIR . '/matomo.php'; ?>
<?php endif; ?>
	</head>
