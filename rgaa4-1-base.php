<?php
require_once __DIR__ . '/includes' . '/config.php';

$lang = 'fr';
$pageTitle = 'Environnement de test - RGAA 4.1 2021 - Tanaguru';
$pageDescription = 'Environnements de test attendus par le RGAA 4.1 2021 pour vérifier la bonne restitution des contenus par les technologies d\'assistance, mis en page par Tanaguru';
$canonicalUrl = '/rgaa4-1-base.html';
$alternateUrls = array (
  'fr' => '/rgaa4-1-base.html',
);
$googleVerification = 'pzLp1bE8KeV2q7OeoMR_xpdXJ-mjfKyh00jw3ai75w0';
$rgaaVersion = 'rgaa41';
$currentPageUrl = '/rgaa4-1-base.php';
$bodyClass = 'toc-follow';

include __DIR__ . '/includes' . '/head.php';
include __DIR__ . '/includes' . '/header.php';
?>
<main id="main" role="main">
			<div class="headrub page">
				<h1>Environnement de test du RGAA 4.1 2021</h1>
				<p>Quelques critères RGAA, notamment ceux de la thématique JavaScript, incluent des tests de restitution à effectuer sur des technologies d’assistance associées à des navigateurs et des systèmes d’exploitation.</p>
				<p>Les tests effectués selon ces combinaisons (technologie d’assistance, système d’exploitation, navigateur) permettent de déclarer qu’un dispositif HTML / WAI-ARIA est “compatible avec l’accessibilité” tel que défini par WCAG.</p>
				<p>Les combinaisons ont été établies à partir de la liste des technologies d’assistance dont l’usage est suffisamment répandu, ou, dans certains cas lorsqu’elle est fournie de manière native et constitue le moyen privilégié d’accès à l’information et aux fonctionnalités.</p>

				<div id="navtoc">
					<h2 id="toc">Sommaire</h2>
					<nav class="nav" role="navigation" aria-labelledby="toc">
						<ul>
							<li><a href="#ordinateur">Environnement de test Ordinateur (<span lang="en">desktop</span>)</a></li>
							<li><a href="#mobile">Environnement de test Terminal mobile</a></li>
							<li><a href="#autres">Autres environnements</a></li>
							<li><a href="#maitrise">Environnement maîtrisé</a></li>
						</ul>
					</nav>
				</div>
			</div>
			<div class="page">
				<h2 id="ordinateur">Environnement de test Ordinateur (<span lang="en">desktop</span>)</h2>
				<p>Les systèmes d’exploitation retenus sont Windows et Mac OS X et les navigateurs, Internet Explorer, Firefox et Safari. Il appartient à l’auditeur de définir, en concertation avec les responsables du site audité, les versions de système d’exploitation et de navigateur en adéquation avec le contexte d’usage du site et l’environnement de test utilisé lors du développement du site. Les versions des technologies d’assistance à utiliser seront soit la dernière disponible en langue française sur le système d’exploitation retenu soit la version précédente.</p>

				<p>Lorsque le site ou l’application est destiné à un public dont l’équipement est maîtrisé, les tests devront se faire sur un environnement de test adapté au contexte de l’environnement maîtrisé.</p>

				<p>Pour qu’un dispositif HTML / WAI-ARIA ou son alternative soit considéré comme compatible avec l’accessibilité, il faut qu’il soit pleinement fonctionnel, en termes de restitution et de fonctionnalités, sur au moins une des combinaisons suivantes.</p>

				<table class="table">
					<caption>Environnement de test Ordinateur (<span lang="en">desktop</span>) - Combinaison 1</caption>
					<thead>
						<tr>
							<th scope="col">Technologie d’assistance</th>
							<th scope="col">Navigateur</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>NVDA (dernière version)</td>
							<td>Firefox</td>
						</tr>
						<tr>
							<td>JAWS (version précédente)</td>
							<td>Firefox ou Internet Explorer</td>
						</tr>
						<tr>
							<td>VoiceOver (dernière version)</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<caption>Environnement de test Ordinateur (<span lang="en">desktop</span>) - Combinaison 2</caption>
					<thead>
						<tr>
							<th scope="col">Technologie d’assistance</th>
							<th scope="col">Navigateur</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>JAWS (version précédente)</td>
							<td>Firefox</td>
						</tr>
						<tr>
							<td>NVDA (dernière version)</td>
							<td>Firefox ou Internet Explorer</td>
						</tr>
						<tr>
							<td>VoiceOver (dernière version)</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>

				<h2 id="mobile">Environnement de test Terminal mobile</h2>
				<p>Les systèmes d’exploitation retenus sont Android et iOS et les navigateurs Chrome et Safari. Il appartient à l’auditeur de définir, en concertation avec les responsables du site audité, les versions de système d’exploitation et de navigateur en adéquation avec le contexte d’usage du site et l’environnement de test utilisé lors du développement du site. Les versions des technologies d’assistance à utiliser seront soit la dernière disponible en langue française sur le système d’exploitation retenu, soit la version précédente. Pour tester un site web sur un terminal mobile, l’environnement de test devra comporter une des deux combinaisons complémentaires suivantes :</p>

				<table class="table">
					<caption>Environnement de test Terminal mobile - Combinaison 1</caption>
					<thead>
						<tr>
							<th>Technologie d’assistance</th>
							<th>Technologie d’assistance</th>
							<th>Navigateur</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>iOS</td>
							<td>VoiceOver (dernière version)</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<caption>Environnement de test Terminal mobile - Combinaison 2</caption>
					<thead>
						<tr>
							<th>Technologie d’assistance</th>
							<th>Technologie d’assistance</th>
							<th>Navigateur</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Android natif</td>
							<td>TalkBack (dernière version)</td>
							<td>Chrome</td>
						</tr>
					</tbody>
				</table>

				<p>À noter que dans le cas d’un site web mobile grand public, il est fortement conseillé de tester dans les deux environnements.</p>

				<h2 id="autres">Autres environnements</h2>
				<p>Enfin, en fonction du contexte du site audité, d’autres technologies d’assistance complémentaires peuvent être utiles telles que :</p>
				<ul>
					<li>ZoomText sur Windows ou Mac OSX.</li>
					<li>Dragon Naturally Speaking sur Windows ou Mac OSX.</li>
				</ul>

				<h2 id="maitrise">Environnement maîtrisé</h2>
				<p>Lorsque le site web est destiné à être diffusé et utilisé dans un environnement maîtrisé, l’environnement de test (base de référence) doit être constitué des configurations (technologie d’assistance, système d’exploitation, navigateur) effectivement utilisées dans l’environnement maîtrisé.</p>
				<p>Par exemple, lorsque le site web est exclusivement diffusé dans un environnement GNU/Linux, les tests devront être réalisés uniquement sur les navigateurs et les technologies d’assistance utilisés par les agents sur cette plateforme. Cet environnement de test (base de référence) se substitue à l’environnement de test (base de référence) utilisé en environnement non maîtrisé.</p>
			</div>
        </main>
<?php include __DIR__ . '/includes' . '/footer.php'; ?>
