<!-- SCRIPTS -->

<?php 

wp_enqueue_script('jquery-ui', get_template_directory_uri() . '/assets/plugins/jquery-ui-1.12.1/jquery-ui.min.js');

wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/plugins/bootstrap-4.3.1-dist/js/bootstrap.bundle.min.js');

wp_enqueue_script('datatable', get_template_directory_uri() . '/assets/plugins/datatables/datatables.min.js');

wp_enqueue_script('fontawesome', get_template_directory_uri() . '/assets/plugins/fontawesome-free-5.10.2-web/js/all.min.js');

wp_enqueue_script('general', get_template_directory_uri() . '/js/general.js', array('jquery-ui', 'bootstrap'));

wp_footer(); ?>

</body>

</html>