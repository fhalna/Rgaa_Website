<?php
require_once __DIR__ . '/includes' . '/config.php';

$lang = 'fr';
$pageTitle = 'Base de référence - RGAA 3 2017 - Tanaguru';
$pageDescription = 'Environnements de test attendus par le RGAA 3 2017 pour vérifier la bonne restitution des contenus par les technologies d\'assistance, mis en page par Tanaguru';
$canonicalUrl = '/rgaa3-base.html';
$alternateUrls = array (
  'fr' => '/rgaa3-base.html',
  'en' => '/en/rgaa3-baseline.html',
);
$googleVerification = 'pzLp1bE8KeV2q7OeoMR_xpdXJ-mjfKyh00jw3ai75w0';
$rgaaVersion = 'rgaa3';
$currentPageUrl = '/rgaa3-base.php';
$bodyClass = 'toc-follow rgaa3';

include __DIR__ . '/includes' . '/head.php';
include __DIR__ . '/includes' . '/header.php';
?>
<main id="main" role="main">
			<div class="headrub page">
				<h1>Base de référence - RGAA 3 2017</h1>
				<p class="lead">Plusieurs critères RGAA (Référentiel Général d'Accessibilité des Administrations) font référence à des tests de restitution à effectuer sur un ensemble de technologies d'assistance, de navigateur et de systèmes d'exploitation. Ce&nbsp;document décrit et explique les combinaisons qui ont été retenues pour constituer la base de références.</p>
				<div id="navtoc">
					<h2 id="toc">Sommaire</h2>
					<nav class="nav" role="navigation" aria-labelledby="toc">
						<ul>
							<li><a href="#compatible">Compatible avec les technologies d'assistance - Base de référence</a></li>
							<li><a href="#baseline">Base de référence</a></li>
							<li><a href="#additional">Exigences complémentaires</a></li>
							<li><a href="#controlled">Environnement maitrisé</a></li>
						</ul>
					</nav>
				</div>
			</div>
			<div class="page">
				<h2 id="compatible">Compatible avec les technologies d'assistance - Base de référence</h2>
				<p>La base de référence est constituée des configurations (technologie d'assistance, système d'exploitation, navigateur) qui permettent de déclarer qu'un dispositif HTML5/ARIA est «&nbsp;compatible avec l'accessibilité&nbsp;» tel que défini par WCAG&nbsp;2.</p>
				<p>Elle est établie par consensus à partir de la liste des technologies d'assistance dont l'usage est suffisamment répandu, ou, dans certains cas (par&nbsp;exemple pour OSX) lorsqu'elle est fournie de manière native et constitue le moyen privilégié d'accès à l'information et aux fonctionnalités.</p>

				<h2 id="baseline">Base de référence</h2>
				<p>La base de référence permettant de couvrir la proportion la plus large des usages est constituée de combinaisons associant des technologies d'assistance d'usage suffisamment répandu, les deux systèmes d'exploitation Windows XP+ et OSX et les trois navigateurs IE9+, Firefox et Safari.</p>
				<p>Pour qu'un dispositif HTML5/ARIA ou son alternative soit considéré comme compatible avec l'accessibilité il faut qu'il soit pleinement fonctionnel, en termes de restitution et de fonctionnalités, sur au moins une des combinaisons suivantes&nbsp;:</p>

				<table class="table">
					<caption>Base de référence - Combinaison 1</caption>
					<thead>
						<tr>
							<th scope="col">Technologie d'assistance</th>
							<th scope="col">Version TA</th>
							<th scope="col">Navigateur</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>NVDA</td>
							<td>Dernière version</td>
							<td>Firefox</td>
						</tr>
						<tr>
							<td>JAWS</td>
							<td>Version précédente</td>
							<td>Firefox ou Internet Explorer 9+</td>
						</tr>
						<tr>
							<td>Voice Over</td>
							<td>Dernière Version</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<caption>Base de référence - Combinaison 2</caption>
					<thead>
						<tr>
							<th scope="col">Technologie d'assistance</th>
							<th scope="col">Version TA</th>
							<th scope="col">Navigateur</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>JAWS</td>
							<td>Version précédente</td>
							<td>Firefox</td>
						</tr>
						<tr>
							<td>NVDA</td>
							<td>Dernière Version</td>
							<td>Firefox ou Internet Explorer 9+</td>
						</tr>
						<tr>
							<td>Voice Over</td>
							<td>Dernière Version</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<caption>Base de référence - Combinaison 3</caption>
					<thead>
						<tr>
							<th scope="col">Technologie d'assistance</th>
							<th scope="col">Version TA</th>
							<th scope="col">Navigateur</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>JAWS</td>
							<td>Version précédente</td>
							<td>Firefox</td>
						</tr>
						<tr>
							<td>Window-Eyes</td>
							<td>Version précédente</td>
							<td>Firefox ou Internet Explorer 9+</td>
						</tr>
						<tr>
							<td>Voice Over</td>
							<td>Dernière Version</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<caption>Base de référence - Combinaison 4</caption>
					<thead>
						<tr>
							<th scope="col">Technologie d'assistance</th>
							<th scope="col">Version TA</th>
							<th scope="col">Navigateur</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Window-Eyes</td>
							<td>Version précédente</td>
							<td>Firefox</td>
						</tr>
						<tr>
							<td>JAWS</td>
							<td>Version précédente</td>
							<td>Firefox ou Internet Explorer 9+</td>
						</tr>
						<tr>
							<td>Voice Over</td>
							<td>Dernière Version</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>

				<p><strong>Note&nbsp;:</strong> Compte tenu que le lecteur d'écran NVDA ne nécessite pas l'achat d'une licence commerciale et couvre toutes les versions de Windows, les deux premières combinaisons devraient être privilégiées. La combinaison NVDA + Window-Eyes ne peut pas être retenue car elle ne couvre pas une proportion suffisamment large d'usages.</p>

				<h2 id="additional">Exigences complémentaires</h2>
				<p>Les règles suivantes doivent également être respectées&nbsp;:</p>
				<ol>
					<li>L'ensemble des dispositifs HTML5/ARIA ou leurs alternatives doivent être pleinement fonctionnels, sur l'ensemble des pages du site, sans nécessiter de changement de technologie d'assistance en cours d'utilisation&nbsp;;</li>
					<li>Lorsque des alternatives à des dispositifs HTML5/ARIA sont proposées, elles ne doivent pas nécessiter la désactivation d'une technologie (par&nbsp;exemple JavaScript ou le plugin Flash) sauf s'il s'agit d'une fonctionnalité proposée par le site lui-même.<br>
					Par&nbsp;exemple&nbsp;:
						<ul>
							<li>le site met à disposition une version alternative conforme pleinement fonctionnelle sans le recours aux technologies dont l'usage est non compatible avec l'accessibilité&nbsp;;</li>
							<li>le site met à disposition une fonctionnalité de remplacement des dispositifs HTML5/ARIA par des dispositifs alternatifs compatibles&nbsp;;</li>
						</ul>
					</li>
					<li>un moyen est mis à disposition des utilisateurs de technologies d'assistance pour signaler les problèmes rencontrés et obtenir, via un dispositif de compensation, les informations qui seraient rendues indisponibles&nbsp;;</li>
					<li>si une déclaration de conformité est établie, elle doit comporter la liste des technologies d'assistance avec lesquelles les dispositifs HTML5/ARIA ont été testés et les résultats de ces tests (par&nbsp;exemple «&nbsp;supporté&nbsp;», «&nbsp;non supporté&nbsp;», «&nbsp;supporté partiellement&nbsp;») au moins.</li>
				</ol>

				<h2 id="controlled">Environnement maitrisé</h2>
				<p>Lorsque le site web est destiné à être diffusé et utilisé dans un <a href="rgaa3-glossaire.html#environnement-maitrise">environnement maîtrisé</a>, la&nbsp;base de références est constituée des configurations (technologie d'assistance, système d'exploitation, navigateur) effectivement utilisés dans l'environnement maîtrisé.</p>
				<p>par&nbsp;exemple, lorsque le site web est exclusivement diffusé dans un environnement GNU/Linux, les tests devront être réalisés uniquement sur les navigateurs et les technologies d'assistance utilisés par les agents sur cette plateforme. Cette base de référence se substitue à la base de référence utilisée en environnement non maîtrisé.</p>
			</div>
        </main>
<?php include __DIR__ . '/includes' . '/footer.php'; ?>
