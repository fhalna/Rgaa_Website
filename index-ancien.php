<?php
require_once __DIR__ . '/includes' . '/config.php';

$lang = 'fr';
$pageTitle = 'RGAA (Référentiel Général d\'Amélioration de l\'Accessibilité) - Version web par Tanaguru';
$pageDescription = 'Retrouvez le RGAA (Référentiel Général d\'Amélioration de l\'Accessibilité) en versions 3.2017 et 4 en français et en anglais';
$alternateUrls = array (
  'fr' => '',
  'en' => '/en/',
);
$googleVerification = 'zQBPqJNd_ExoycP0KGhFEqON9x6qMVEsGFw6sozLobE';
$currentPage = 'index-ancien';
$bodyClass = 'home toc-follow';

include __DIR__ . '/includes' . '/head.php';
include __DIR__ . '/includes' . '/header.php';
?>
<main id="main" role="main">
			<div class="headrub page">
				<h1>RGAA (Référentiel Général d'Amélioration de l'Accessibilité), version web par Tanaguru</h1>
				<p class="lead">Ce site web a pour vocation de présenter le RGAA sous une forme différente de la version officielle, qui nous convient mieux chez Tanaguru. Il présente les mêmes contenus que la version officielle. Cependant, des coquilles ont pu se glisser dans nos pages. N'hésitez pas à <a href="https://github.com/Tanaguru/Rgaa_Website">nous les signaler sur le projet Github</a> !</p>
				<div id="navtoc">
					<h2 id="toc">Sommaire</h2>
					<nav class="nav" role="navigation" aria-labelledby="toc">
						<ul>
							<li><a href="#rgaa4-1">RGAA 4.1</a></li>
							<li><a href="#rgaa4">RGAA 4.0</a></li>
							<li><a href="#rgaa3-2017">RGAA 3.2017</a></li>
						</ul>
					</nav>
				</div>
			</div>
			<div class="page">
				<h2 id="rgaa4-1">Liste des pages RGAA 4.1</h2>
				<ul>
					<li><a href="/rgaa4-1-criteres.html"><strong>Critères</strong> RGAA 4.1</a></li>
					<li><a href="/rgaa4-1-glossaire.html"><strong>Glossaire</strong> RGAA 4.1</a></li>
					<li><a href="/rgaa4-1-base.html"><strong>Environnement de test</strong> RGAA 4.1</a></li>
					<li><a href="/rgaa4-1-references.html"><strong>Références</strong> RGAA 4.1</a></li>
				</ul>

				<h2 id="rgaa4">Liste des pages RGAA 4.0</h2>
				<ul>
					<li><a href="/rgaa4-criteres.html"><strong>Critères</strong> RGAA 4</a></li>
					<li><a href="/rgaa4-glossaire.html"><strong>Glossaire</strong> RGAA 4</a></li>
					<li><a href="/rgaa4-base.html"><strong>Environnement de test</strong> RGAA 4</a></li>
					<li><a href="/rgaa4-references.html"><strong>Références</strong> RGAA 4</a></li>
				</ul>

				<h2 id="rgaa3-2017">Liste des pages RGAA 3.2017</h2>
				<ul>
					<li><a href="/rgaa3-criteres.html"><strong>Critères</strong> RGAA 3.2017</a></li>
					<li><a href="/rgaa3-glossaire.html"><strong>Glossaire</strong> RGAA 3.2017</a></li>
					<li><a href="/rgaa3-cas-particuliers.html"><strong>Cas particuliers</strong> RGAA 3.2017</a></li>
					<li><a href="/rgaa3-notes-techniques.html"><strong>Notes techniques</strong> RGAA 3.2017</a></li>
					<li><a href="/rgaa3-base.html"><strong>Base de référence</strong> RGAA 3.2017</a></li>
					<li><a href="/rgaa3-references.html"><strong>Références</strong> RGAA 3.2017</a></li>
				</ul>
			</div>
		</main>
<?php include __DIR__ . '/includes' . '/footer.php'; ?>
