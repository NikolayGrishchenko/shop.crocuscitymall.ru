		</main>
		<footer class="globalfooter">
			<div class="container">
				<div class="footer-block contact-us">
					<ul>
						<li>
							<a href="https://crocuscitymall.ru/">О Молле</a>
						</li>
					</ul>
					<section class="bullets">
						<figure class="bullet">
							<span class="b-glyph b-shipping"></span>
							<p><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								[
									"AREA_FILE_SHOW" => "file", 
									"PATH" => SITE_DIR . '/include/footer_address.php', 
								]
							);?></p>
						</figure>
						<figure class="bullet">
							<span class="b-glyph b-payment"></span>
							<p><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								[
									"AREA_FILE_SHOW" => "file", 
									"PATH" => SITE_DIR . '/include/footer_phone.php', 
								]
							);?></p>
						</figure>
						<figure class="bullet">
							<span class="b-glyph b-location"></span>
							<p><?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								[
									"AREA_FILE_SHOW" => "file", 
									"PATH" => SITE_DIR . '/include/footer_email.php', 
								]
							);?></p>
						</figure>
					</section>
					<div class="clear-both"></div>
				</div>
				<div class="container">
					<div class="appfooter">
						<div class="copyright">
							© <a href="/">CROCUSCITYMALL</a> <?=date('Y')?>
						</div>
						<div class="footertextpages">
							<a href="/upload/Policy.pdf">Политика конфиденциальности</a>
						</div>
						<div class="footertextpages">
							<a href="/upload/Agreement.pdf">Пользовательское соглашение</a>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>