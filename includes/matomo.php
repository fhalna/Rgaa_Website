		<!-- Matomo -->
		<script>
			var _paq = window._paq || [];

			/* CNIL - cookie duration */
			_paq.push([function() {
				var self = this;
				function getOriginalVisitorCookieTimeout() {
					var now = new Date(),
					nowTs = Math.round(now.getTime() / 1000),
					visitorInfo = self.getVisitorInfo();
					var createTs = parseInt(visitorInfo[2]);
					var cookieTimeout = 33696000; // 13 months in seconds
					var originalTimeout = createTs + cookieTimeout - nowTs;
					return originalTimeout;
				}
				this.setVisitorCookieTimeout( getOriginalVisitorCookieTimeout() );
			}]);

			/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
			_paq.push(["setDoNotTrack", true]);
			_paq.push(['trackPageView']);
			_paq.push(['enableLinkTracking']);
			(function() {
				var u="<?= MATOMO_URL ?>";
				_paq.push(['setTrackerUrl', u+'matomo.php']);
				_paq.push(['setSiteId', '<?= MATOMO_SITE_ID ?>']);
				var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
				g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
			})();
		</script>
		<!-- End Matomo Code -->
