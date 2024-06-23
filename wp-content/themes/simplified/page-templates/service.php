<?php
/*
		Template Name: Service
	*/
if (!g_cua('administrator')) g_404();

get_header();
?>

<script>
	$(document).ready(function() {
		$('body').addClass('bg-white');
	});
</script>

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


<?php
get_footer();
?>