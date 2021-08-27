<?php
/*
		Template Name: Main
	*/

if (!g_cua('administrator')) header('Location: wp-login.php');

//Init locale
if ($_GET['lang'] === 'ru') {
	load_textdomain('translate', get_template_directory() . '/languages/ru_RU.mo');
}
?>

<?php include 'page-templates/countries.php'; ?>

<script>
	var containsRusLettersError = "<?php _ge('Please, fill in English') ?>";
	var wrongEmailAddress = "<?php _ge('Invalid e-mail format') ?>";
	var maximumPapers = "<?php _ge('Maximum two papers could be presented by one author') ?>";
	var alreadySelectedPaper = "<?php _ge('The paper has already been selected') ?>";
	var fieldNecessary = "<?php _ge('Field is required') ?>";
	var noPaperFound = "<?php _ge('No papers found') ?>"
	var noServer = "<?php _ge('Registration server is unavailable. Please, try again later...') ?>";
</script>

<?php
$page = 'regform';
$hasReg = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	//Get data by Link		
	if ("" !== get_query_var('plink')) {
		$hasReg = true;
		$link = get_query_var('plink');

		$strict = wp_get_current_user()->user_login !== 'itntadm';
		$reg = form_get_reg_by_link($link, $strict);
		$reg = @get_object_vars($reg);

		//Redirect to 404 page on wrong link
		if (is_null($reg)) g_404();

		$articles = form_get_reg_articles($reg['ID']);
		$events = form_get_reg_events($reg['ID']);
		$workshops = form_get_reg_workshops($reg['ID']);
	}
	//Load restore page
	else if ("" !== get_query_var('restore')) {
		$page = 'restoreform';
	}
}

get_header();
?>

<div class='h-100 w-100 position-fixed backimg'></div>

<div class='mycontainer position-relative'>
	<div class='header'>
		<div class='row align-items-center'>
			<div class='col-6 col-sm-6 pimage'>
				<a href="http://itnt-conf.org/" rel="home">
					<img src="<?php echo get_site_url(); ?>/wp-content/themes/simplified/resources/ITNT-logo_s3.png" width='90px' height='48px' />
				</a>
			</div>
			<div class='col-6 col-sm-6 lang text-right'>
				<a href='?lang=ru' title='Russian'><img src='wp-content/themes/simplified/resources/ru.gif'></img></a>
				<a href='<?php
									if ($page === 'restoreform') echo 'restore';
									else if (isset($link)) echo $link;
									else echo '.';
									?>' title='English'><img src='wp-content/themes/simplified/resources/en.gif'></img></a>
			</div>
		</div>
		<div class='row'>
			<div class='col ptitle text-center'>
				<a href="<?php echo esc_url(get_site_url('/')) . (($_GET['lang'] === 'ru') ? '?lang=ru' : ''); ?>" rel="home"><?php echo _gl('REGISTRATION FORM'); ?></a>
			</div>
		</div>
	</div>

	<div id='status-bar' class="alert alert-danger mt-3 mx-3 text-center d-none"><i class="zmdi zmdi-alert-triangle mr-1"></i><span class='text'></span></div>

	<div class='formcontainer mt-4'>

		<?php if ($page === 'regform') { ?>
			<div id='regok' class='d-none'>
				<div class="alert alert-success text-center">
					<div class='created d-none' style="font-size: medium;"><?php _ge("Thank you for registering to ITNT-2020 Conference!") ?></div>
					<div class='edited d-none' style="font-size: medium;"><?php _ge("Your registration data have been changed.") ?></div>
					<div class='mt-2 font-italic confirmation d-none'><?php _ge("The registration confirmation letter will be sent to the specified E-mail within 24 hours.") ?></div>
				</div>
				<div class='mt-4'>
					<a id='editregbtn' type='button' class='btn btn-primary'><?php _ge("Edit registration") ?></a>
				</div>
			</div>

			<div id='reg'>
				<form id='registrationForm' method='post' class='needs-validation' novalidate>
					<input type='hidden' name='Lang' value='<?php echo $_GET['lang'] === 'ru' ? 'R' : 'E' ?>' />
					<fieldset>
						<legend><?php _ge('Personal Information') ?></legend>
						<div class='form-row'>
							<div class='col-12 col-sm-3 material'>
								<i class="zmdi zmdi-graduation-cap"></i>
								<select class="form-control" name='Honorific' value='<?php echo $reg['Honorific'] ?>' required>
									<?php
									$honorifics = array('' => _gl('Prefix'), 'Prof.' => 'Prof.', 'Dr.' => 'Dr.', 'Mr.' => 'Mr.', 'Mrs.' => 'Mrs.', 'Miss' => 'Miss', 'Ms.' => 'Ms.');
									foreach ($honorifics as $key => $value) {
										echo "<option value='{$key}' " . ($key === $reg['Honorific'] ? 'selected' : '') . ">{$value}</option>";
									} ?>
								</select>
								<div class="invalid-feedback"><?php _ge('Select prefix') ?></div>
							</div>

							<div class='col-6 col-sm-4 material'>
								<i class="zmdi zmdi-account-o"></i>
								<input type='text' class="form-control onlyeng" maxlength='64' name='fname' value='<?php echo $reg['FirstName'] ?>' placeholder='<?php _ge('First Name') ?>' autocomplete='given-name' required />
								<div class="invalid-feedback"><?php _ge('Enter your name') ?></div>
							</div>

							<div class='col-6 col-sm-5 material'>
								<i class="zmdi zmdi-accounts-outline"></i>
								<input type='text' class="form-control onlyeng" maxlength='64' name='lname' value='<?php echo $reg['LastName'] ?>' placeholder='<?php _ge('Family Name') ?>' autocomplete='family-name' required />
								<div class="invalid-feedback"><?php _ge('Enter your family name') ?></div>
							</div>
						</div>


						<div class='material'>
							<i class="zmdi zmdi-email"></i>
							<input type='email' class="form-control" name='email' value='<?php echo $reg['EMail'] ?>' placeholder='<?php _ge('E-mail') ?>' autocomplete='email' required />
							<div class="invalid-feedback"><?php _ge('Enter your e-mail') ?></div>
						</div>
					</fieldset>

					<fieldset>
						<legend><span id='naming' style='font:inherit'></span><?php _ge('Scientific degree') ?></legend>

						<div class='material'>
							<div class='radiogroup form-row'>
								<div class='col-sm-5'>
									<div class='radiobox'>
										<input type='radio' name='SciStatus' id='sciStatusS' value='S' required <?php if ($reg['SciStatus'] === 'S') echo 'checked' ?> />
										<label for='sciStatusS'><?php _ge('Student / PhD Student') ?></label>
									</div>
								</div>

								<div class='col-sm-4'>
									<div class='radiobox'>
										<input type='radio' name='SciStatus' id='sciStatusD' value='D' required <?php if ($reg['SciStatus'] === 'D') echo 'checked' ?> />
										<label for='sciStatusD'><?php _ge('DSc / PhD') ?></label>
									</div>
								</div>

								<div class='col-sm-2'>
									<div class='radiobox'>
										<input type='radio' name='SciStatus' id='sciStatusN' value='N' required <?php if ($reg['SciStatus'] === 'N') echo 'checked' ?> />
										<label for='sciStatusN'><?php _ge('None') ?></label>
									</div>
								</div>
							</div>
							<div class="invalid-feedback"><?php _ge('Select scientific degree') ?></div>
						</div>
					</fieldset>

					<fieldset>
						<legend><?php _ge('Residency') ?></legend>

						<div class='material'>
							<div class='radiogroup form-row'>
								<div class='col-sm-6'>
									<div class='radiobox'>
										<input type='radio' name='Residency' id='residencyR' value='R' <?php if ($reg['Country'] === 'RUS') echo 'checked' ?> required />
										<label for='residencyR'><?php _ge('Russian Federation') ?></label>
									</div>
								</div>

								<div class='col-sm-6'>
									<div class='radiobox'>
										<input type='radio' name='Residency' id='residencyO' value='O' <?php if ($hasReg && $reg['Country'] !== 'RUS') echo 'checked' ?> required />
										<label for='residencyO'><?php _ge('Other Country') ?></label>
									</div>
								</div>
							</div>
							<div class="invalid-feedback"><?php _ge('Select residency') ?></div>
						</div>

						<div id='country' class='form-row collapse <?php if ($hasReg && $reg['Country'] !== 'RUS') echo 'show' ?>'>
							<div class='col material'>
								<i class="zmdi zmdi-home"></i>
								<select class="form-control" name='Country' value='<?php echo $reg['Country'] ?>' required>
									<?php foreach ($countries_all as $key => $value) {
										echo "<option value='{$key}' " . ($key === $reg['Country'] ? 'selected' : '') . ">{$value}</option>";
									} ?>
								</select>
								<div class="invalid-feedback"><?php _ge('Select country') ?></div>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<legend><?php _ge('Main organization') ?></legend>

						<div class='material'>
							<div class='radiogroup form-row'>
								<div class='col-sm-5'>
									<div class='radiobox'>
										<input type='radio' name='OrgSelect' id='orgSelectS' value='S' <?php if ($reg['Organization'] === 'Samara University') echo 'checked' ?> required />
										<label for='orgSelectS'><?php _ge('Samara University') ?></label>
									</div>
								</div>

								<div class='col-sm-4'>
									<div class='radiobox'>
										<input type='radio' name='OrgSelect' id='orgSelectI' value='I' <?php if ($reg['Organization'] === 'Image Processing Systems Institute') echo 'checked' ?> required />
										<label for='orgSelectI'><?php _ge('IPSI RAS') ?></label>
									</div>
								</div>

								<div class='col-sm-2'>
									<div class='radiobox'>
										<input type='radio' name='OrgSelect' id='orgSelectO' value='O' <?php if ($hasReg && $reg['Organization'] !== 'Samara University' && $reg['Organization'] !== 'Image Processing Systems Institute') echo 'checked' ?> required />
										<label for='orgSelectO'><?php _ge('Other_') ?></label>
									</div>
								</div>
							</div>
							<div class="invalid-feedback"><?php _ge('Select organization') ?></div>
						</div>

						<div id='organization' class='collapse <?php if ($hasReg && $reg['Organization'] !== 'Samara University' && $reg['Organization'] !== 'Image Processing Systems Institute') echo 'show' ?>'>
							<div class='form-row'>
								<div class='col material'>
									<i class="zmdi zmdi-home"></i>
									<input type='text' class="form-control onlyeng" name='Organization' maxlength='1024' value='<?php echo $reg['Organization'] ?>' placeholder='<?php _ge('Short name (no abbreviation)') ?>' required />
									<span class='errormsg inline'></span>
									<div class="invalid-feedback"><?php _ge('Enter organization\'s name') ?></div>
								</div>
								<div class='col-sm-4 material'>
									<i class="zmdi zmdi-pin"></i>
									<input type='text' class="form-control onlyeng" name='OrganizationCity' maxlength='128' value='<?php echo $reg['OrganizationCity'] ?>' placeholder='<?php _ge('City') ?>' required />
									<div class="invalid-feedback"><?php _ge('Enter organization\'s city') ?></div>
								</div>
							</div>
						</div>

						<div class='material'>
							<div class='checkbox'>
								<input type='checkbox' name='PaymentFromOrg' id='paymentFromOrg' value='Y' <?php if ($reg['PaymentFromOrg'] === 'Y') echo 'checked' ?> />
								<label for='paymentFromOrg'><?php _ge('Payment from organization') ?></label>
								<span class='checkmark'></span>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<legend><?php _ge('Format of participation') ?></legend>

						<div class='material'>
							<div class='radiogroup form-row'>
								<div class='col'>
									<div class='radiobox'>
										<input type='radio' name='IsPresenter' id='isPresenterN' value='N' <?php if ($hasReg && empty($articles)) echo 'checked' ?> required />
										<label for='isPresenterN'><?php _ge('Listener') ?></label>
									</div>
								</div>

								<div class='col'>
									<div class='radiobox'>
										<input type='radio' name='IsPresenter' id='isPresenterY' value='Y' <?php if (!empty($articles)) echo 'checked' ?> required />
										<label for='isPresenterY'><?php _ge('Presenter') ?></label>
									</div>
								</div>
							</div>
							<div class="invalid-feedback"><?php _ge('Select format of participation') ?></div>
						</div>

						<div id='presentation' class='collapse <?php if ($hasReg && !empty($articles)) echo 'show' ?>'>

							<div class='material pb-0'>
								<i class="zmdi zmdi-search"></i>
								<input id='search' type='text' class="form-control" name='Search' placeholder='<?php _ge('Select 1 or 2 papers (search by Author or Title)...') ?>' />
								<div class="invalid-feedback position-relative"><?php _ge('Select at least one paper to present') ?></div>
							</div>
							<div id='list-container' class='row autocomplete w-100'></div>

							<div id='paperGroup' class='material'>
								<?php
								for ($i = 0; $i < 2; $i++) {
									$article = isset($articles[$i]) ? get_object_vars($articles[$i]) : null;
								?>
									<div class='form-row collapse <?php if ($hasReg && isset($article)) echo 'show' ?>'>
										<div class='col'>
											<div class='paper'>
												<div class='body'>
													<a href="#" onclick='return false;' class="close" title='<?php _ge('Remove paper') ?>'></a>
													<div class="truncate atitle"><?php echo isset($article) ? '[' . $article['ECID'] . '] ' . $article['Title'] : '' ?></div>
													<div class="truncate authors"><?php echo isset($article) ? $article['Authors'] : '' ?></div>
													<div class='decision' style='font-size: 0.9rem'><?php echo isset($article) ? 'Status: ' . $article['Decision'] : '' ?></div>

													<div class='font-weight-bold my-1 text-center'><?php _ge('Format of presentation') ?></div>

													<div class='material pb-0'>
														<div class='radiogroup form-row'>
															<div class='col-12 col-sm-4'>
																<div class='radiobox'>
																	<input type='radio' name='Format<?php echo $i + 1 ?>' id='isPoster<?php echo $i + 1 ?>' value='P' <?php if ($hasReg && $article['Format'] === 'P') echo 'checked' ?> required />
																	<label for='isPoster<?php echo $i + 1 ?>'><?php _ge('Poster') ?><span class='required'>*</span></label>
																</div>
															</div>

															<div class='col-12 col-sm-4'>
																<div class='radiobox'>
																	<input type='radio' name='Format<?php echo $i + 1 ?>' id='isVideo<?php echo $i + 1 ?>' value='V' <?php if ($hasReg && $article['Format'] === 'V') echo 'checked' ?> required />
																	<label for='isVideo<?php echo $i + 1 ?>'><?php _ge('Video') ?><span class='required'>**</span></label>
																</div>
															</div>

															<div class='col-12 col-sm-4'>
																<div class='radiobox'>
																	<input class='oral' type='radio' name='Format<?php echo $i + 1 ?>' id='isLive<?php echo $i + 1 ?>' value='L' <?php if ($hasReg && $article['Format'] === 'L') echo 'checked' ?> <?php if (isset($article) && $article['Decision'] === 'ACCEPT POSTER') echo 'disabled' ?> required />
																	<label for='isLive<?php echo $i + 1 ?>'><?php _ge('Online') ?><span class='required'>***</span></label>
																</div>
															</div>
														</div>
														<div class="invalid-feedback position-relative"><?php _ge('Select format of presentation') ?></div>
													</div>
												</div>
												<input type='hidden' name='ID_Article<?php echo $i + 1 ?>' value='<?php echo $article['ID'] ?>' />
											</div>
										</div>
									</div>
								<?php
								}

								?>

								<div class='info'>
									<div><span class='required d-inline-block text-center mr-1' style='width:20px' class='required'>*</span><?php echo _ge('The participant provides a poster in JPG/JPEG format') ?></div>
									<div><span class='required d-inline-block text-center mr-1' style='width:20px' class='required'>**</span><?php echo _ge('The participant provides a presentation video') ?></div>
									<div><span class='required d-inline-block text-center mr-1' style='width:20px' class='required'>***</span><?php echo _ge('The participant provides a presentation video and joins the broadcast at the scheduled time to answer questions online') ?></div>
								</div>
							</div>

							<div class='material'>
								<div class='checkbox'>
									<input type='checkbox' name='YouthSchool' id='youthSchool' value='Y' <?php if ($reg['YouthSchool'] === 'Y') echo 'checked' ?> />
									<label for='youthSchool'><?php _ge('I\'ll participate in Youth School') ?></label>
									<span class='checkmark'></span>
								</div>
							</div>
						</div>
					</fieldset>

					<?php if (false) { ?>
						<fieldset>
							<legend><?php _ge('Entertainment interests') ?> <a href='http://itnt-conf.org/index.php/glavnaya/social-events-rus' target='_blank' class='details' title='<?php _ge('Show events details') ?>'><i class="zmdi zmdi-info"></i></a></legend>

							<div class='form-row material'>
								<div class='col-sm-6 mb-2'>
									<div class='checkbox event'>
										<input type='checkbox' name='Event2' id='event2' value='Y' <?php if ($events[2] === 'Y') echo 'checked' ?> />
										<label for='event2' style='font-weight:bold; font-size:1.1rem'><?php _ge('Boat trip') ?></label>
										<span class='checkmark'></span>
									</div>
								</div>
								<div class='col-sm-6 mb-2'>
									<div class='checkbox event'>
										<input type='checkbox' name='Event3' id='event3' value='Y' <?php if ($events[3] === 'Y') echo 'checked' ?> />
										<label for='event3' style='font-weight:bold; font-size:1.1rem'><?php _ge('Planetarium') ?></label>
										<span class='checkmark'></span>
									</div>
								</div>
								<div class='col-sm-4 mb-2'>
									<div class='checkbox event'>
										<input type='checkbox' name='Event4' id='event4' value='Y' <?php if ($events[4] === 'Y') echo 'checked' ?> />
										<label for='event4'><?php _ge('Cosmic Samara tour'); ?></label>
										<span class='checkmark'></span>
									</div>
								</div>
								<div class='col-sm-4 mb-2'>
									<div class='checkbox event'>
										<input type='checkbox' name='Event6' id='event6' value='Y' <?php if ($events[5] === 'Y') echo 'checked' ?> />
										<label for='event6'><?php _ge('Samara Sightseeing') ?></label>
										<span class='checkmark'></span>
									</div>
								</div>
								<div class='col-sm-4 mb-2'>
									<div class='checkbox event'>
										<input type='checkbox' name='Event5' id='event5' value='Y' <?php if ($events[6] === 'Y') echo 'checked' ?> />
										<label for='event5'><?php _ge('Stalin\'s Bunker tour') ?></label>
										<span class='checkmark'></span>
									</div>
								</div>
								<div class='col-sm-6 mb-2 mb-sm-0'>
									<div class='checkbox event'>
										<input type='checkbox' name='Event7' id='event7' value='Y' <?php if ($events[7] === 'Y') echo 'checked' ?> />
										<label for='event7'><?php _ge('Samara University Scientific&nbsp;Labs&nbsp;tour') ?></label>
										<span class='checkmark'></span>
									</div>
								</div>
								<div class='col-sm-6'>
									<div class='checkbox event'>
										<input type='checkbox' name='Event8' id='event8' value='Y' <?php if ($events[8] === 'Y') echo 'checked' ?> />
										<label for='event8'><?php _ge("Museum of aviation and&nbsp;cosmonautics") ?></label>
										<span class='checkmark'></span>
									</div>
								</div>
							</div>
						</fieldset>

						<fieldset>
							<legend><?php _ge('Workshop participation') ?> <a href='http://itnt-conf.org/index.php/meropriyatiya/workshops' target='_blank' class='details' title='<?php _ge('Show workshops details') ?>'><i class="zmdi zmdi-info"></i></a></legend>

							<div class='form-row material'>
								<div class='col-sm-12 mb-2'>
									<div class='checkbox event'>
										<input type='checkbox' name='Track1' id='track1' value='Y' <?php if ($workshops[1] === 'Y') echo 'checked' ?> />
										<label for='track1' style='font-weight:bold; font-size:1.2rem'><span style='font-weight:normal; font-size:1rem'><?php _ge('Track #') ?>1</span><br />Samara University</label>
										<span class='checkmark'></span>
									</div>
								</div>
								<div class='col-sm-6 mb-2 mb-sm-0'>
									<div class='checkbox event'>
										<input type='checkbox' name='Track2' id='track2' value='Y' <?php if ($workshops[2] === 'Y') echo 'checked' ?> />
										<label for='track2' style='font-weight:bold; font-size:1.2rem'><span style='font-weight:normal; font-size:1rem'><?php _ge('Track #') ?>2</span><br />Nvidia</label>
										<span class='checkmark'></span>
									</div>
								</div>
								<div class='col-sm-6'>
									<div class='checkbox event'>
										<input type='checkbox' name='Track3' id='track3' value='Y' <?php if ($workshops[3] === 'Y') echo 'checked' ?> />
										<label for='track3' style='font-weight:bold; font-size:1.2rem'><span style='font-weight:normal; font-size:1rem'><?php _ge('Track #') ?>3</span><br />Intel</label>
										<span class='checkmark'></span>
									</div>
								</div>
							</div>
						</fieldset>

					<?php } ?>

					<fieldset>
						<div class='material'>
							<div class='checkbox'>
								<input type='checkbox' name='PersonalAgreement' id='personalAgreement' value='Y' checked required />
								<label for='personalAgreement'><?php _ge('I agree to the processing of my personal data') ?></label>
								<span class='checkmark'></span>
							</div>
							<div class="invalid-feedback"><?php _ge('Sorry, we cannot process your data') ?></div>
						</div>
					</fieldset>

					<input type='hidden' name='Link' value='<?php echo $reg['Link'] ?>' />
					<button id='submitButton' type='submit' class='btn btn-primary mt-2'>
						<div class="d-none mr-2 spinner-border" style="height: 18px;width: 18px;"></div><?php _ge('Submit') ?>
					</button>
					<div class='restorehelp'>
						<?php _ge('Forgot your registration edit link?') ?> <a href='restore<?php echo ($_GET['lang'] === 'ru') ? '?lang=ru' : '';  ?>' id='restoreLink'><?php _ge('Restore') ?>...</a>
					</div>
				</form>
			</div>

		<?php } else { ?>

			<div id='restok' class='d-none'>
				<div class="alert alert-success text-center" style="font-size: medium;">
					<?php _ge('Registration edit link has been sent to the specified E-mail.'); ?>
				</div>
			</div>

			<div id='rest'>
				<form id='restoreForm' method='post' class='needs-validation' novalidate>
					<input type='hidden' name='Lang' value='<?php echo $_GET['lang'] === 'ru' ? 'R' : 'E' ?>' />
					<fieldset>
						<legend><?php _ge('Information for recovery') ?></legend>

						<div class='material'>
							<i class="zmdi zmdi-email"></i>
							<input type='email' class="form-control" name='email' value='<?php echo $reg['EMail'] ?>' placeholder='<?php _ge('E-mail') ?>' autocomplete='email' required />
							<div class="invalid-feedback"><?php _ge('Enter your e-mail address') ?></div>
						</div>
					</fieldset>
					<button id='restoreButton' type="submit" class="btn btn-primary mt-2">
						<div class="d-none mr-2 spinner-border" style="height: 18px;width: 18px;"></div><?php _ge('Restore') ?>
					</button>
				</form>
			</div>
		<?php } ?>
	</div>

	<div class="text-center footer" style='background: url("wp-content/themes/simplified/resources/dark-honeycomb.png")'>
		<a href="http://itnt-conf.org/" rel="home"><?php _ge('ITNT-2020 Conference Website') ?></a>
	</div>
</div>

<?php
wp_enqueue_script('page', get_template_directory_uri() . '/js/form.js', array('general'));

get_footer();
?>