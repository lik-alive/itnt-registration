<?php
/*
		Template Name: Stats
	*/
if (!g_cua('administrator', 'contributor')) {
	header("Location: wp-login.php?redirect_to=" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
	exit();
}

get_header();

function calcStats($growth = false)
{
	$cond = '1';
	if ($growth) $cond = 'DateTime > DATE_SUB(CURDATE(), INTERVAL 1 DAY)';

	$registrations = db_list_cond('zi_ab_participants', "ID_Conf=2 AND $cond");
	global $wpdb;
	$reports = $wpdb->get_results("
		SELECT *
		FROM zi_ab_participants p 
			LEFT JOIN zi_ab_arlinks al ON al.ID_Participant=p.ID
		WHERE p.ID_Conf=2 AND $cond
	");

	$registrationsCount = sizeof($registrations);
	$sciDoctorsCount = 0;
	$sciStudentsCount = 0;
	$sciOthersCount = 0;
	$paymentFromOrgCount = 0;
	$paymentFromOtherCount = 0;
	$youthSchoolCount = 0;

	foreach ($registrations as $row) {
		if ($row->SciStatus === 'D') $sciDoctorsCount++;
		else if ($row->SciStatus === 'S') $sciStudentsCount++;
		else $sciOthersCount++;

		if ($row->PaymentFromOrg === 'Y') $paymentFromOrgCount++;
		else $paymentFromOtherCount++;

		if ($row->YouthSchool === 'Y') $youthSchoolCount++;
	}

	$reportsCount = 0;
	$listenersCount = 0;
	$presentersCount = 0;
	$formatLiveCount = 0;
	$formatVideoCount = 0;
	$formatPosterCount = 0;

	foreach ($reports as $row) {
		if (isset($row->ID_Article)) {
			$reportsCount++;
			if ($row->Format === 'L') $formatLiveCount++;
			else if ($row->Format === 'V') $formatVideoCount++;
			else $formatPosterCount++;
		} else {
			$listenersCount++;
		}
	}

	$presentersCount = $registrationsCount - $listenersCount;

	return (object) array(
		'Registrations' => $registrationsCount,
		'SciDoctors' => $sciDoctorsCount,
		'SciStudents' => $sciStudentsCount,
		'SciOthers' => $sciOthersCount,
		'PaymentOrg' => $paymentFromOrgCount,
		'PaymentOther' => $paymentFromOtherCount,
		'YouthSchool' => $youthSchoolCount,
		'Reports' => $reportsCount,
		'Listeners' => $listenersCount,
		'Presenters' => $presentersCount,
		'FormatLive' => $formatLiveCount,
		'FormatVideo' => $formatVideoCount,
		'FormatPoster' => $formatPosterCount
	);
}

$cur = calcStats();
$old = calcStats(true);

?>

<style>
	.stats {
		width: max-content;
		margin-left: auto;
		margin-right: auto;
	}

	.growth {
		color: green;
	}

	.label {
		width: 200px;
		display: inline-block;
	}

	.title {
		font-weight: bold;
		font-size: 1.2rem;
		margin: 0 0 5px 0;
		text-align: center;
	}

	.title:not(:first-child) {
		margin-top: 20px;
	}
</style>

<div class='mx-4 mt-3'>
	<div class='border border-secondary'>
		<div class='text-center bg-secondary py-2 px-3 text-white' style='font-size:  1.4rem'>Статистика</div>

		<div class='stats my-3'>
			<div class='title'>Number of registrations</div>
			<div>
				<span class='label'>Total: </span>
				<span><?php echo $cur->Registrations ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->Registrations;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>

			<div class='title'>Scientific status</div>
			<div>
				<span class='label'>Doctor / PhD: </span>
				<span><?php echo $cur->SciDoctors ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->SciDoctors;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>

			<div>
				<span class='label'>Student / Postgrad: </span>
				<span><?php echo $cur->SciStudents ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->SciStudents;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>

			<div>
				<span class='label'>Other: </span>
				<span><?php echo $cur->SciOthers ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->SciOthers;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>


			<div class='title'>Youth School</div>
			<div>
				<span class='label'>Total: </span>
				<span><?php echo $cur->YouthSchool ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->YouthSchool;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>


			<div class='title'>Format of payment</div>
			<div>
				<span class='label'>From Organization: </span>
				<span><?php echo $cur->PaymentOrg ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->PaymentOrg;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>

			<div>
				<span class='label'>Other: </span>
				<span><?php echo $cur->PaymentOther ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->PaymentOther;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>


			<div class='title'>Format of participation</div>
			<div>
				<span class='label'>Listener: </span>
				<span><?php echo $cur->Listeners ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->Listeners;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>

			<div>
				<span class='label'>Presenter: </span>
				<span><?php echo $cur->Presenters ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->Presenters;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>


			<div class='title'>Number of reports</div>
			<div>
				<span class='label'>Total: </span>
				<span><?php echo $cur->Reports ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->Reports;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>


			<div class='title'>Format of presentation</div>
			<div>
				<span class='label'>Live: </span>
				<span><?php echo $cur->FormatLive ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->FormatLive;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>

			<div>
				<span class='label'>Video: </span>
				<span><?php echo $cur->FormatVideo ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->FormatVideo;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>

			<div>
				<span class='label'>Poster: </span>
				<span><?php echo $cur->FormatPoster ?></span>
				<span class='growth' title='Over the last 24 hours'>
					<?php $val = $old->FormatPoster;
					if ($val) echo "(+$val)"
					?>
				</span>
			</div>

		</div>
	</div>
</div>


<script>
	$(document).ready(function() {
		$('body').addClass('bg-white');
	});
</script>

<?php

get_footer();
?>