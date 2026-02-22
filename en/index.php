<?php
require_once dirname(__DIR__) . '/includes' . '/config.php';

$lang = 'en';
$pageTitle = 'RGAA (Reference Document for the Improvement of Accessibility) - Web version by Tanaguru';
$pageDescription = 'Discover the RGAA (Reference Document for the Improvement of Accessibility) in versions 3.2017 and 4 in French and English';
$canonicalUrl = '/en/';
$alternateUrls = array (
  'fr' => '',
  'en' => '/en/',
);
$googleVerification = 'pzLp1bE8KeV2q7OeoMR_xpdXJ-mjfKyh00jw3ai75w0';
$bodyClass = 'home toc-follow';

include dirname(__DIR__) . '/includes' . '/head.php';
include dirname(__DIR__) . '/includes' . '/header.php';
?>
<main id="main" role="main">
			<div class="headrub page">
				<h1>RGAA (Reference Document for the Improvement of Accessibility) - Web version by Tanaguru</h1>
				<p class="lead">This website aims to present the RGAA in a different form from the official version, which is more appropriate for us at Tanaguru. It has the same content as the official version. However, some typos may have slipped into our pages. Feel free to <a href="https://github.com/Tanaguru/Rgaa_Website">let us know about them on the Github project</a>!</p>
				<div id="navtoc">
					<h2 id="toc">Table of Contents</h2>
					<nav class="nav" role="navigation" aria-labelledby="toc">
						<ul>
							<li><a href="#rgaa4">RGAA 4.0</a></li>
							<li><a href="#rgaa3-2017">RGAA 3.2017</a></li>
						</ul>
					</nav>
				</div>
			</div>
			<div class="page">
				<h2 id="rgaa4">List of the RGAA 4.0 pages<br /> (work in progress)</h2>
				<ul>
					<li>RGAA 4.2019 <strong>Criteria</strong>: work in progress</li>
					<li>RGAA 4.2019 <strong>Glossary</strong>: work in progress</li>
					<li>RGAA 4.2019 <strong>Baseline</strong>: work in progress</li>
					<li>RGAA 4.2019 <strong>References</strong>: work in progress</li>
				</ul>

				<h2 id="rgaa3-2017">List of the RGAA 3.2017 pages</h2>
				<ul>
					<li><a href="/en/rgaa3-criteria.html">RGAA 3.2017 <strong>Criteria</strong></a></li>
					<li><a href="/en/rgaa3-glossary.html">RGAA 3.2017 <strong>Glossary</strong></a></li>
					<li><a href="/en/rgaa3-particular-cases.html">RGAA 3.2017 <strong>Particular cases</strong></a></li>
					<li><a href="/en/rgaa3-technical-notes.html">RGAA 3.2017 <strong>Technical notes</strong></a></li>
					<li><a href="/en/rgaa3-baseline.html">RGAA 3.2017 <strong>Baseline</strong></a></li>
					<li><a href="/en/rgaa3-references.html">RGAA 3.2017 <strong>References</strong></a></li>
				</ul>
			</div>
		</main>
<?php include dirname(__DIR__) . '/includes' . '/footer.php'; ?>
