<!doctype html>
<html>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri() ?>/resources/icon.ico">

	<?php wp_enqueue_style('bootstrap-style', get_template_directory_uri() . '/assets/plugins/bootstrap-4.3.1-dist/css/bootstrap.min.css'); ?>
	<?php wp_enqueue_style('jquery-ui-style', get_template_directory_uri() . '/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.css'); ?>
	<?php wp_enqueue_style('datatable-style', get_template_directory_uri() . '/assets/plugins/datatables/datatables.min.css'); ?>
	<?php wp_enqueue_style('material-design-style', get_template_directory_uri() . '/assets/plugins/material-design/css/material-design-iconic-font.min.css'); ?>
	<?php wp_enqueue_style('pragmatica-style', get_template_directory_uri() . '/assets/fonts/pragmatica/stylesheet.css'); ?>
	<?php wp_enqueue_style('fontawesome-style', get_template_directory_uri() . '/assets/plugins/fontawesome-free-5.10.2-web/css/fontawesome.min.css'); ?>
	<?php wp_head(); ?>

	<!-- For jQuery support in php -->
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/plugins/jquery-3.3.1/jquery-3.3.1.min.js'></script>

	<script type="text/javascript">
		var ADMIN_URL = "<?php echo admin_url('admin-ajax.php'); ?>";
		var SITE_URL = "<?php echo get_site_url(); ?>";
	</script>

</head>

<body>

	<?php if (g_cua('administrator', 'contributor')) { ?>
		<nav class="navbar navbar-expand-sm navbar-dark bg-dark" style='z-index: 3'>
			<a class="navbar-brand" href="#">Админ-панель</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ml-auto">
					<?php
					$url = home_url($wp->request);
					$href = home_url();
					?>
					<li class="nav-item <?php echo ($url === $href) ? 'active' : '' ?>">
						<a class="nav-link" href=".">Главная</a>
					</li>
					<li class="nav-item <?php echo ($url === $href . '/stats') ? 'active' : '' ?>">
						<a class="nav-link" href="stats">Статистика</a>
					</li>
					<li class="nav-item <?php echo ($url === $href . '/helper') ? 'active' : '' ?>">
						<a class="nav-link" href="helper">Справки</a>
					</li>
					<li class="nav-item <?php echo ($url === $href . '/mails') ? 'active' : '' ?>">
						<a class="nav-link" href="mails">Почтовик</a>
					</li>
					<?php if (g_cua('administrator')) { ?>
						<li class="nav-item <?php echo ($url === $href . '/letters') ? 'active' : '' ?>">
							<a class="nav-link" href="letters">Шаблоны</a>
						</li>
						<li class="nav-item <?php echo ($url === $href . '/service') ? 'active' : '' ?>">
							<a class="nav-link" href="service">Сервис</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</nav>
		<div id='page-status' class='status-container px-2 pt-2'></div>
		<div id='page-status-fixed' class='fixed-top'></div>

	<?php } ?>