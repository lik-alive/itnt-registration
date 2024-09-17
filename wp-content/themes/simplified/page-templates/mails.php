<?php
/*
		Template Name: Mails
	*/
if (!g_cua('administrator', 'contributor')) g_404();

get_header();

?>

<script>
	const SITEURL = "<?php echo WP_SITEURL; ?>";
</script>

<div class='mx-4 my-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Почтовик</div>

		<div class='my-3 mx-2'>
			<table id='datatable' class='table-striped' style='min-width: 280px'>
				<thead>
					<tr>
						<th class='d-none'>ID</th>
						<th width='50%'>Статус</th>
						<th width='50%'>Отправлено</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<?php include get_template_directory() . '/page-templates/tools/confirm-modal.php'; ?>

<script>
	$(document).ready(function() {
		$('body').addClass('bg-white');
	});
</script>

<?php

wp_enqueue_script('page', get_template_directory_uri() . '/js/mails.js', array('datatable', 'general'));

get_footer();
?>