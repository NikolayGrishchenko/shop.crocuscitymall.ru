<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR?>favicon.ico" />
		<? $APPLICATION->setAdditionalCss(SITE_TEMPLATE_PATH . '/css/bootstrap.min.css'); ?>

		<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery-3.7.1.min.js'); ?>
		<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/bootstrap.min.js'); ?>
		<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/script.js'); ?>
		
		<? $APPLICATION->ShowHead(); ?>
		<title><?$APPLICATION->ShowTitle()?></title>
	</head>
	<body>
		<?$APPLICATION->ShowPanel();?>
		<header class="header">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col d-flex align-items-center">
						<h2 class="pt-2">
							<a href="/">
								<img src="/images/logo.png">
							</a>
						</h2>
						<?$APPLICATION->IncludeComponent(
							"bitrix:menu",
							"header",
							[
								"ROOT_MENU_TYPE" => "catalog", 
								"MAX_LEVEL" => "1", 
								"CHILD_MENU_TYPE" => "catalog", 
								"USE_EXT" => "Y",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "Y",
								"MENU_CACHE_TYPE" => "N", 
								"MENU_CACHE_TIME" => "3600", 
								"MENU_CACHE_USE_GROUPS" => "Y", 
								"MENU_CACHE_GET_VARS" => "" 
							]
						);?>
					</div>
					<div class="col d-flex align-items-center justify-content-end">
						<form method="get" action="/search/" class="search">
							<input id="search" type="search" name="query" placeholder="Найти товары">
						</form>
						<div id="cart" class="cart">
							<a href="/basket/" class="cart-summary">
								<i class="cart-icon"></i>
								<i class="cart-count" data-basket-quantity></i>
							</a>
						</div>
						<div id="menuToggle">
							<input type="checkbox">
							<span></span>
							<span></span>
							<ul id="menu">
								<a href="/" class="bold"></a>
								<div class="flyout">
									<div class="container">
										<?$APPLICATION->IncludeComponent(
											"bitrix:menu",
											"dropdown",
											[
												"ROOT_MENU_TYPE" => "catalog", 
												"MAX_LEVEL" => "2", 
												"CHILD_MENU_TYPE" => "catalog", 
												"USE_EXT" => "Y",
												"DELAY" => "N",
												"ALLOW_MULTI_SELECT" => "Y",
												"MENU_CACHE_TYPE" => "N", 
												"MENU_CACHE_TIME" => "3600", 
												"MENU_CACHE_USE_GROUPS" => "Y", 
												"MENU_CACHE_GET_VARS" => "" 
											]
										);?>
									</div>
								</div>	
							</ul>
						</div>
						<button id="mobile-nav-toggle"></button>
						<div class="clear-both"></div>
					</div>
				</div>
			</div>
		</header>
		<main class="container position-relative pt-3 pb-3">