<?php
/*
		Template Name: Helper
	*/
if (!g_cua('administrator', 'contributor')) {
	header("Location: wp-login.php?redirect_to=" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
	exit();
}

get_header();

?>

<script>
	$(document).ready(function() {
		$('body').addClass('bg-white');
	});
</script>

<div class='mx-4 my-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Справка публикации в Scopus</div>

		<div class='my-3 mx-2 mb-2'>
			<button type='button' class='btn btn-block btn-info collapser collapsed' data-toggle='collapse' data-target='#collapsemail'>
				Шаблон справки
				<i class="fas fa-chevron-down"></i>
				<i class="fas fa-chevron-up"></i>
			</button>
			<div id='collapsemail' class='collapse'>
				<?php
				$urltempl = get_site_url(null, "wp-content/themes/simplified/emails/confirmations/pubconfirm2_pdf.html");
				echo "<iframe class='w-100 mt-2' style='height: 80vh' src='$urltempl'></iframe>";
				?>
			</div>

			<br />

			<form id='scopus' method='post'>
				<div class='font-weight-bold ml-1'>EasyChair ID</div>
				<div class='form-group row'>
					<div class='col-12 col-sm-9'>
						<input id='ecid' type='text' class='w-100 form-control' name='ECID' placeholder='Введите Easy Chair ID для поиска по базе статей' required></input>
					</div>
					<div class='col-12 col-sm-3'>
						<button id='ecidsearch' type='button' class='btn btn-block btn-warning'>
							<i class='fas fa-search'> </i>
							Найти
						</button>
					</div>
				</div>

				<div class='font-weight-bold ml-1'>Авторы статьи</div>
				<div class='form-group row'>
					<div class='col-12'>
						<textarea id='authors' rows='2' class='w-100 form-control' name='Authors' required></textarea>
					</div>
				</div>

				<div class='font-weight-bold ml-1'>Название статьи</div>
				<div class='form-group row'>
					<div class='col-12'>
						<textarea id='title' rows='3' class='w-100 form-control' name='Title' required></textarea>
					</div>
				</div>

				<div class='font-weight-bold ml-1'>E-Mail получателя справки</div>
				<small id='recipient' class='form-text text-muted ml-1'></small>
				<div class='form-group row'>
					<div class='col-12'>
						<input id='email' type='email' class='w-100 form-control' name='EMail' placeholder='Введите E-Mail адрес' required></input>
					</div>
				</div>


				<button type="submit" class="btn btn-primary mb-0 mt-2"><i class='fas fa-envelope'></i> Отправить</button>
			</form>
			<script>
				$('#ecid').on('keydown', function(e) {
					if (e.keyCode === 13) {
						e.preventDefault();
						$('#ecidsearch').click();
					}
				});

				$('#ecidsearch').click(() => {
					if (!$('#ecid').val().length) {
						AddStatusFixedMsg([2, 'Пожалуйста, введите Easy Chair ID']);
						return;
					}

					$('#scopus textarea').val('');
					$('#email').val('');
					$('#recipient').html('');

					$.get(ADMIN_URL, {
						action: 'ecid_search_json',
						'ECID': $('#ecid').val()
					}, function(response) {
						let data = JSON.parse(response);
						if (data === null) AddStatusFixedMsg([2, 'Информация о статье не найдена. Если вы уверены, что ECID верен - введите данные вручную']);
						else {
							$('#authors').val(data.Authors);
							$('#title').val(data.Title);

							if (data.EMail === null) {
								AddStatusFixedMsg([2, 'На данную статью никто не регистрировался. Кому отправим?']);
							} else {
								$('#recipient').html('На данную статью регистрировалась: ' + data.Name);
								$('#email').val(data.EMail);
							}
						}
					});
				});

				$('#scopus').submit(function(e) {
					e.preventDefault();

					let fd = new FormData(this);
					fd.append('Type', 'Scopus');
					fd.append('action', 'letters_send_json');

					$.ajax({
						type: 'POST',
						url: ADMIN_URL,
						contentType: false,
						processData: false,
						data: fd,
						success: function(response) {
							let data = JSON.parse(response);

							AddStatusFixedMsg(data);
						}
					});
				});
			</script>
		</div>
	</div>
</div>

<?php
get_footer();
?>