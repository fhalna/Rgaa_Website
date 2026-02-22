<?php
require_once dirname(__DIR__) . '/includes' . '/config.php';

$lang = 'en';
$pageTitle = 'Baseline - RGAA 3 2017 (English translation) - Tanaguru';
$canonicalUrl = '/en/rgaa3-baseline.html';
$alternateUrls = array (
  'fr' => '/rgaa3-base.html',
  'en' => '/en/rgaa3-baseline.html',
);
$googleVerification = 'pzLp1bE8KeV2q7OeoMR_xpdXJ-mjfKyh00jw3ai75w0';
$rgaaVersion = 'rgaa3';
$currentPageUrl = '/en/rgaa3-baseline.php';
$bodyClass = 'toc-follow rgaa3';

include dirname(__DIR__) . '/includes' . '/head.php';
include dirname(__DIR__) . '/includes' . '/header.php';
?>
<main id="main" role="main">
			<div class="headrub page">
				<h1>Baseline - RGAA 3 2017</h1>
				<p class="lead">Several <abbr lang="fr" title="Référentiel Général d'Accessibilité des Administrations">RGAA</abbr> criteria refer  to rendering tests on a range of assistive technologies, browsers and operating systems. This document describes and explains the combinations selected to constitute the baseline.</p>
				<div id="navtoc">
					<h2 id="toc">Table of Contents</h2>
					<nav class="nav" role="navigation" aria-labelledby="toc">
						<ul>
							<li><a href="#compatible">Compatible with assistive technologies - Baseline</a></li>
							<li><a href="#baseline">Baseline</a></li>
							<li><a href="#additional">Additional requirements</a></li>
							<li><a href="#controlled">Controlled environment</a></li>
						</ul>
					</nav>
				</div>
			</div>
			<div class="page">
				<h2 id="compatible">Compatible with assistive technologies - Baseline</h2>
				<p>The reference baseline consists of configurations (assistive technology, operating system, browser) used to declare a HTML5&nbsp;/ ARIA  based piece of software is <a rel="external" class="external" href="http://www.w3.org/Translations/WCAG20-fr/#accessibility-supporteddef">"accessibility supported" as defined by WCAG&nbsp;2.0</a>.</p>
				<p>It is established by consensus from the list of assistive technologies that are in a sufficiently widespread used, or in some cases (eg.: OS&nbsp;X) when provided natively, and is the users' preferred means of access to information and functionality.</p>
				<p>The methodology used to establish the baseline is  described in detail in this document: "Base de référence pour la compatibilité avec l'accessibilité" (translation: Baseline for compatibility with assistive technologies). You may download it as an <a href="http://references.modernisation.gouv.fr/sites/default/files/base_de_reference_RGAA.odt" hreflang="fr" class="download">ODT <small>(25 kb, in&nbsp;French)</small></a> or <a href="http://references.modernisation.gouv.fr/sites/default/files/base_de_reference_RGAA.pdf" hreflang="fr" class="download">PDF <small>(610 kb, in&nbsp;French)</small></a> file.
				</p>

				<h2 id="baseline">Baseline</h2>
				<p>The baseline that cover the widest proportion of uses consists of combinations involving assistive technologies sufficiently widespread, the two operating systems Windows XP (and later) and OS&nbsp;X + and the three browsers IE9+, Firefox and Safari.
				</p>
				<p>For a HTML5&nbsp;/&nbsp;ARIA device or its alternative to be considered compatible with accessibility, it has to be fully functional in terms of restitution and features, at least for one of the following combinations:
				</p>
				<table class="table">
					<caption>Baseline - Combination 1</caption>
					<thead>
						<tr>
							<th scope="col">Assistive technology</th><th scope="col">AT version</th><th scope="col">Browser</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>NVDA</td><td>Latest version</td><td>Firefox</td>
						</tr>
						<tr>
							<td>JAWS</td><td>Previous version</td><td>Firefox or Internet Explorer 9+</td>
						</tr>
						<tr>
							<td>Voice Over</td><td>Latest version</td><td>Safari</td>
						</tr>
					</tbody>
				</table>
				<table class="table">
					<caption>Baseline - Combination 2</caption>
					<thead>
						<tr>
							<th scope="col">Assistive technology</th>
							<th>AT version</th>
							<th scope="col">Browser</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>JAWS</td>
							<td>Previous version</td>
							<td>Firefox</td>
						</tr>
						<tr>
							<td>NVDA</td>
							<td>Latest version</td>
							<td>Firefox or Internet Explorer 9+</td>
						</tr>
						<tr>
							<td>Voice Over</td>
							<td>Latest version</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>
				<table class="table">
					<caption>Baseline - Combination 3</caption>
					<thead>
						<tr>
							<th scope="col">Assistive technology</th>
							<th scope="col">AT version</th>
							<th scope="col">Browser</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>JAWS</td>
							<td>Previous version</td>
							<td>Firefox</td>
						</tr>
						<tr>
							<td>Window-Eyes</td>
							<td>Previous version</td>
							<td>Firefox or Internet Explorer 9+</td>
						</tr>
						<tr>
							<td>Voice Over</td>
							<td>Latest version</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>
				<table class="table">
					<caption>Baseline - Combination 4</caption>
					<thead>
						<tr>
							<th scope="col">Assistive technology</th>
							<th scope="col">AT version</th>
							<th scope="col">Browser</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Window-Eyes</td>
							<td>Previous version</td>
							<td>Firefox</td>
						</tr>
						<tr>
							<td>JAWS</td>
							<td>Previous version</td>
							<td>Firefox or Internet Explorer 9+</td>
						</tr>
						<tr>
							<td>Voice Over</td>
							<td>Latest version</td>
							<td>Safari</td>
						</tr>
					</tbody>
				</table>
				<p><strong>Note:</strong> Since the NVDA screen reader does not require the purchase of a commercial license and covers all versions of Windows, the first two combinations should be preferred. The combination NVDA + Window-Eyes can not be accepted because it does not cover a sufficiently large proportion of uses.</p>

				<h2 id="additional">Additional requirements</h2>
				<p>The following rules must be observed:
				</p>
				<ol>
					<li>All HTML5&nbsp;/&nbsp;ARIA devices, or their alternatives, must be fully functional on all pages of the site without needing to use a different assistive technology;</li>
					<li>When alternatives to HTML5&nbsp;/&nbsp;ARIA devices are proposed, they should not require deactivation of a technology (eg.: JavaScript or Flash plugin) unless there is a feature offered by the website itself. For example:
						<ul>
							<li>the site provides a fully functional, compliant alternative version, that does not require technologies  that are not accessibility supported;
							</li>
							<li>the site offers feature allowing to replace HTML5 / ARIA devices by compatible alternative devices;
							</li>
						</ul>
					</li>
					<li>Users of assistive technologies are provided with a means to report issues, and get through a compensation system, the information they would be blocked from otherwise;
					</li>
					<li>if a conformance declaration is provided, it must include a list of assistive technologies with which the HTML5&nbsp;/&nbsp;ARIA devices were tested, and the results of these tests (eg. "supported", "not supported", "partially supported"), at least.
					</li>
				</ol>
				<h2 id="controlled">Controlled environment</h2>
				<p>When the website is intended to be distributed and used in a controlled environment, the baseline consists of configurations (assistive technology, operating system, browser) actually used in the controlled environment.</p>
				<p>For example, when the website is exclusively distributed in a GNU&nbsp;/&nbsp;Linux environment, the tests must be carried out only on browsers and assistive technologies actually used by users in this environment. This baseline replaces the baseline used in uncontrolled environments.
				</p>
			</div>
        </main>
<?php include dirname(__DIR__) . '/includes' . '/footer.php'; ?>
