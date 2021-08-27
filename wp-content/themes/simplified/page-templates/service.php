<?php
/*
		Template Name: Service
	*/
if (!g_cua('administrator')) g_404();

get_header();


// $results = db_list_cond('zi_ab_participants', "ID_Conf=2");
// $data = '';
// foreach ($results as $row) {
// 	if (!empty($data)) $data .= "\n";
// 	$data .= $row->Link;
// }
//var_dump($data);

// $content = file_get_contents(get_template_directory() . '/assets/s4.csv');
// $lines = explode("\n", $content);
// for ($i = 1; $i < sizeof($lines); $i++) {
// 	$line = $lines[$i];
// 	if (empty($line)) continue;

// 	$pars = explode(';', $line);
// 	db_add_TH('zi_ab_rooms', array('Link' => $pars[0], 'SectionNo' => 4));
// }

//letters_broadcast_TH('Rooms', null);
// updateECAuthors();

?>

<script>
	$(document).ready(function() {
		$('body').addClass('bg-white');
	});
</script>

<!-- <div class='mx-4 mt-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-3 px-3 text-white' style='font-size:  1.4rem'>Поздравляю с Днём Рождения!</div>

		<div class='mt-3 mx-2 mb-2'>
			<form  method='post'>
				<div class='text-center mb-2'>
					<img src='<?php echo get_template_directory_uri() ?>/resources/num2.1.jpg' style='width: 100%'></img>
				</div>
				<button type="button" class="btn btn-heat btn-block mb-0 font-weight-bold">Ура! Ура! Ураааа!!!</button>
			</form>
		</div>
	</div>
</div> -->

<div class='mx-4 mt-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Поиск регистрации</div>

		<div class='mt-3 mx-2 mb-2'>
			<form id='search' method='post'>
				<input type='text' class='w-100 form-control' rows='1' name='Data' placeholder='Вставьте данные'></input>
				<button type="submit" class="btn btn-primary mb-0 mt-2">Найти</button>
				<label class='mt-2'>Результат:</label>
				<div class='results'></div>
			</form>
			<script>
				$('#search').submit(function(e) {
					e.preventDefault();
					let data = $(this).find('[name=Data]').val();

					$.get(ADMIN_URL, {
						action: 'search_json',
						'Data': data
					}, function(response) {
						let data = JSON.parse(response);
						console.log(data);
						if (data.length) {
							$('#search .results').empty();
							data.forEach(el => {
								$('#search .results').append(`<div><a href='${el.Link}' target='_blank'>${el.Link}</a></div>`);
							});
						}

					});
				});
			</script>
		</div>
	</div>
</div>

<div class='mx-4 mt-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Массовая рассылка (Upload - список links)</div>

		<div class='mt-3 mx-2 mb-2'>
			<form id='broadcast' method='post'>
				<textarea class='w-100 allowtab' rows='10' name='Data' placeholder='Вставьте данные'></textarea>
				<button type="submit" class="btn btn-primary mb-0">Запустить</button>
			</form>
			<script>
				$('#broadcast').submit(function(e) {
					e.preventDefault();
					let data = $(this).find('[name=Data]').val();

					$.post(ADMIN_URL, {
						action: 'letters_broadcast_json',
						'Type': 'Upload',
						'Data': data
					}, function(response) {
						let data = JSON.parse(response);
						AddStatusMsg(data);
					});
				});
			</script>
		</div>
	</div>
</div>

<div class='mx-4 mt-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Массовая рассылка (Registration - список links)</div>

		<div class='mt-3 mx-2 mb-2'>
			<form id='broadcast2' method='post'>
				<textarea class='w-100 allowtab' rows='10' name='Data' placeholder='Вставьте данные'></textarea>
				<button type="submit" class="btn btn-primary mb-0">Запустить</button>
			</form>
			<script>
				$('#broadcast2').submit(function(e) {
					e.preventDefault();
					let data = $(this).find('[name=Data]').val();

					$.post(ADMIN_URL, {
						action: 'letters_broadcast_json',
						'Type': 'Registration',
						'Data': data
					}, function(response) {
						let data = JSON.parse(response);
						AddStatusMsg(data);
					});
				});
			</script>
		</div>
	</div>
</div>

<div class='mx-4 mt-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Массовая рассылка (ExtPubConf)</div>

		<div class='mt-3 mx-2 mb-2'>
			<form id='broadcast3' method='post'>
				<button type="submit" class="btn btn-primary mb-0">Запустить</button>
			</form>
			<script>
				$('#broadcast3').submit(function(e) {
					e.preventDefault();

					$.post(ADMIN_URL, {
						action: 'letters_broadcast_json',
						'Type': 'ExtPubConf'
					}, function(response) {
						let data = JSON.parse(response);
						AddStatusMsg(data);
					});
				});
			</script>
		</div>
	</div>
</div>
<br />
<br />
<br />
<br />
<!-- <div class='mx-4 mt-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Обновление списка статей</div>

		<div class='mt-3 mx-2 mb-2'>
			<form id='updateArticles' method='post'>
				<button type="submit" class="btn btn-primary mb-0">Обновить</button>
			</form>
			<script>
				$('#updateArticles').submit(function(e) {
					e.preventDefault();

					$.post(ADMIN_URL, {
						action: 'ec_update_papers_json'
					}, function(response) {
						let data = JSON.parse(response);
						AddStatusMsg(data);
					});
				});
			</script>
		</div>
	</div>
</div> -->



<div class='mx-4 mt-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Инфо сервера</div>

		<div class='mt-3 mx-2 mb-2'>
			<button type='button' class='btn btn-block btn-info collapser collapsed' data-toggle='collapse' data-target='#collapsephp'>
				PHP Info
			</button>
			<div id='collapsephp' class='collapse overflow-auto show'>
				<?php
				function embedded_phpinfo()
				{
					ob_start();
					phpinfo();
					$phpinfo = ob_get_contents();
					ob_end_clean();
					$phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
					echo "
				<style type='text/css'>
				#phpinfo {}
				#phpinfo pre {margin: 0; font-family: monospace;}
				#phpinfo a:link {color: #009; text-decoration: none; background-color: #fff;}
				#phpinfo a:hover {text-decoration: underline;}
				#phpinfo table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
				#phpinfo .center {text-align: center;}
				#phpinfo .center table {margin: 1em auto; text-align: left;}
				#phpinfo .center th {text-align: center !important;}
				#phpinfo td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
				#phpinfo h1 {font-size: 150%;}
				#phpinfo h2 {font-size: 125%;}
				#phpinfo .p {text-align: left;}
				#phpinfo .e {background-color: #ccf; width: 300px; font-weight: bold;}
				#phpinfo .h {background-color: #99c; font-weight: bold;}
				#phpinfo .v {background-color: #ddd; max-width: 300px; overflow-x: auto; word-wrap: break-word;}
				#phpinfo .v i {color: #999;}
				#phpinfo img {float: right; border: 0;}
				#phpinfo hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
				</style>
				<div id='phpinfo'>
				$phpinfo
				</div>
				";
				}
				embedded_phpinfo();
				?>
			</div>
		</div>
	</div>
</div>




<?php
get_footer();
?>