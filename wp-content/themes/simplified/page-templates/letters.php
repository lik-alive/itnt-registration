<?php
/*
		Template Name: Letters
	*/
if (!current_user_can('administrator')) g_404();

get_header();

if (isset($_GET['templ'])) {
	$templ = stripslashes($_GET['templ']);
}
?>

<div class='mx-4 mt-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Шаблоны писем</div>

		<div class='mt-3 mx-2 mb-2'>
			<select id='templateSelect' class='w-100'>
				<?php
				$root = ABSPATH . 'wp-content/themes/simplified/emails';
				$dirs = glob($root . '/*', GLOB_ONLYDIR);

				echo "<option value=''>-Выберите шаблон-</option>";
				foreach ($dirs as $dir) {
					$files = glob($dir . '/*.html');
					foreach ($files as $file) {
						$relpath = mb_substr($file, mb_strlen(ABSPATH));
						$text = file_get_contents($file);
						$selected = $relpath === $templ ? 'selected' : '';
						preg_match('/<!--Name: (.*)-->/', $text, $matches);
						if (empty($matches)) {
							echo "<option value='$relpath' $selected>Нет названия</option>";
						} else {
							$name = htmlspecialchars($matches[1]);
							echo "<option value='$relpath' $selected>$name</option>";
						}
					}
				}

				?>
			</select>

			<?php
			if (!empty($templ)) {
				$res = files_fill_pdf_TH(ABSPATH . $templ, [], "sample");

				echo "<a class='btn btn-primary' href='" . $res['url'] . "' target='_blank'>Sample</a>";

				$urltempl = get_site_url(null, $templ);
				echo "<iframe class='w-100 vh-100 mt-2' src='$urltempl'></iframe>";
			}
			?>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('body').addClass('bg-white');

		$('#templateSelect').change(function() {
			location.href = '?templ=' + $(this).val();
		});
	});
</script>

<?php

wp_enqueue_script('page', get_template_directory_uri() . '/js/mails.js', array('datatable', 'general'));

get_footer();
?>