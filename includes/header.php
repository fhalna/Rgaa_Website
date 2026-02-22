	<body class="<?= $bodyClass ?? '' ?>">
<?php
$skipToMenu = !empty($rgaaVersion);
$skipMenuLabel = ($lang === 'fr') ? 'Aller au contenu' : 'Go to content';
$skipMenuRgaaLabel = '';
if ($skipToMenu) {
	$versionLabels = ['rgaa41' => 'RGAA 4.1', 'rgaa4' => 'RGAA 4', 'rgaa3' => 'RGAA 3'];
	$vLabel = $versionLabels[$rgaaVersion] ?? '';
	$skipMenuRgaaLabel = ($lang === 'fr')
		? 'Aller au menu ' . $vLabel
		: 'Go to menu ' . $vLabel;
}
?>
		<ul class="skiplinks-top">
<?php if ($skipToMenu): ?>
			<li><a id="go-to-menu" href="#nav"><?= $skipMenuRgaaLabel ?></a></li>
<?php endif; ?>
			<li><a href="#main"><?= $skipMenuLabel ?></a></li>
		</ul>
		<a id="top"></a>
		<header id="header" role="banner">
			<div class="headsite nav clearfix">
				<div class="container headsite-container">
					<p class="headsite-logo"><a href="<?= ($lang === 'fr') ? '/' : '/en/' ?>" title="<?= t('header.logo_title') ?>" class="headsite-logo-link"><img src="/images/tanaguru-logo.svg" alt="Tanaguru" width="40" height="40" class="headsite-logo-img" /> RGAA</a></p>

					<nav role="navigation" aria-label="<?= t('header.main_nav_label') ?>" class="main-nav">
						<ul class="main-nav-list"><!--
<?php
// Build the main nav items based on language
$currentVersion = $rgaaVersion ?? null;
if ($lang === 'fr'):
?>
							--><li class="main-nav-item"><?php if (($currentPage ?? '') !== 'index-ancien'): ?><a href="/index-ancien.php" class="main-nav-link"><?php endif; ?><?= t('nav.old_versions') ?><?php if (($currentPage ?? '') !== 'index-ancien'): ?></a><?php endif; ?></li><!--
							--><li class="main-nav-item"><?php if ($currentVersion !== 'rgaa3'): ?><a href="/rgaa3-criteres.php" class="main-nav-link"><?php endif; ?>RGAA 3.2017<?php if ($currentVersion !== 'rgaa3'): ?></a><?php endif; ?></li><!--
							--><li class="main-nav-item"><?php if ($currentVersion !== 'rgaa4'): ?><a href="/rgaa4-criteres.php" class="main-nav-link"><?php endif; ?>RGAA 4.0<?php if ($currentVersion !== 'rgaa4'): ?></a><?php endif; ?></li><!--
							--><li class="main-nav-item"><?php if ($currentVersion !== 'rgaa41'): ?><a href="/index.php" class="main-nav-link"><?php endif; ?>RGAA 4.1<?php if ($currentVersion !== 'rgaa41'): ?></a><?php endif; ?></li><!--
							--><li class="main-nav-item"><a href="/en/" lang="en" hreflang="en" class="main-nav-link"><?= t('nav.english') ?></a></li>
<?php else: ?>
							--><li class="main-nav-item"><?php if ($currentVersion !== 'rgaa3'): ?><a href="/en/rgaa3-criteria.php" class="main-nav-link"><?php endif; ?>RGAA 3.2017<?php if ($currentVersion !== 'rgaa3'): ?></a><?php endif; ?></li><!--
							--><li class="main-nav-item"><a href="/" lang="fr" hreflang="fr" class="main-nav-link"><?= t('nav.french') ?></a></li>
<?php endif; ?>
						</ul>
					</nav>

					<div class="btn-nav-rgaa-container">
<?php if (!empty($rgaaVersion)):
	$versionLabels = ['rgaa41' => 'RGAA 4.1', 'rgaa4' => 'RGAA 4', 'rgaa3' => 'RGAA 3'];
	$menuBtnLabel = ($lang === 'fr' ? 'Menu ' : 'Menu ') . ($versionLabels[$rgaaVersion] ?? '');
?>
						<button type="button" class="navbar-toggle collapsed btn-nav-rgaa" data-toggle="collapse" data-target="#nav" aria-controls="nav" aria-expanded="false" id="btnnav"><?= $menuBtnLabel ?></button>
<?php endif; ?>
					</div>
				</div>
			</div>
<?php if (!empty($rgaaVersion)):
	$navItems = get_rgaa_nav($rgaaVersion, $lang);
	$navLabel = ($lang === 'fr')
		? 'Menu des pages du ' . ($versionLabels[$rgaaVersion] ?? '')
		: ($versionLabels[$rgaaVersion] ?? '') . ' pages menu';
?>
			<nav id="nav" class="collapse navbar-collapse" role="navigation" aria-label="<?= $navLabel ?>">
				<ul><!--
<?php foreach ($navItems as $item):
	$isActive = (isset($currentPageUrl) && $item['url'] === $currentPageUrl);
?>
					--><li><?php if ($isActive): ?><strong class="on"><?= $item['label'] ?></strong><?php else: ?><a href="<?= $item['url'] ?>"><?= $item['label'] ?></a><?php endif; ?></li><!--
<?php endforeach; ?>
				--></ul>
			</nav>
<?php endif; ?>
		</header>
