<?php
//cURL autologin to EasyChair and retrieve HTML code of all papers page
function getECPapersHTML_TH()
{
	$curl = getInside();

	//We are inside!

	curl_setopt($curl, CURLOPT_URL, 'https://easychair.org/conferences/submissions?a=23343612');

	//Execute the GET request and print out the result.
	$result =  curl_exec($curl);
	curl_close($curl);

	return $result;
}

//Parse array of papers [id, title]
function parseECPapers_TH()
{
	$html = getECPapersHTML_TH();

	$dom = new DOMDocument;
	@$dom->loadHTML($html);

	//Get base table
	$table = $dom->getElementById('ec:table1');
	if (!isset($table)) return null;

	//Get table body
	$tableBody = $table->childNodes->item(1);
	if (!isset($tableBody)) return null;

	//Retrieve paper data
	$papers = array();
	foreach ($tableBody->getElementsByTagName('tr') as $tr) {
		$tds = $tr->getElementsByTagName('td');

		//Skip headers
		if ($tds->length < 2) continue;

		//Skip rejected papers
		if ($tds->item(15)->nodeValue === 'REJECT') continue;

		$ecid = (int)$tds->item(0)->nodeValue;
		$authors = $tds->item(1)->nodeValue;
		$title = $tds->item(2)->nodeValue;
		$link = $tds->item(3)->getElementsByTagName('a')->item(0)->getAttribute('href');
		$decision = $tds->item(15)->nodeValue;

		$papers[$ecid] = array('Authors' => $authors, 'Title' => $title, 'Link' => $link, 'Decision' => $decision);
	}

	return $papers;
}

// Update papers table using current data from EC
add_action('wp_ajax_ec_update_papers_json', 'ec_update_papers_json');
function ec_update_papers_json()
{
	try {
		global $CONFID;
		$newpapers = parseECPapers_TH();

		foreach ($newpapers as $ecid => $value) {
			$row = db_list_cond('zi_ab_articles', "ECID=$ecid AND ID_Conf=$CONFID")[0];

			$newpaper = array(
				'ID_Conf' => $CONFID,
				'ECID' => $ecid,
				'Authors' => $value['Authors'],
				'Title' => $value['Title'],
				'Decision' => $value['Decision']
			);

			// Create new
			if (is_null($row)) {
				db_add_TH('zi_ab_articles', $newpaper);
			}
			// Update 
			else {
				$newpaper['ID'] = $row->ID;
				db_set_TH('zi_ab_articles', $newpaper);
			}
		}
		echo json_encode(array(1, 'Таблица статей обновлена'));
	} catch (DataException $e) {
		g_ldx($e->getMessage(), $e->getData());
		echo json_encode(array(2, 'Ошибка обновления статей'));
	}
	exit();
}


//Update authors table using current data from EC
function updateECAuthors()
{
	try {
		global $wpdb, $CONFID;
		$wpdb->query("
			DELETE FROM zi_ab_authors 
			WHERE ID_Article IN (
				SELECT ID
				FROM zi_ab_articles
				WHERE ID_Conf=$CONFID
			)");

		$papers = parseECPapers_TH();

		$curl = getInside();

		foreach ($papers as $ecid => $value) {
			$link = $value['Link'];

			curl_setopt($curl, CURLOPT_URL, 'https://easychair.org' . $link);

			//Execute the GET request and print out the result.
			$html = curl_exec($curl);

			$dom = new DOMDocument;
			@$dom->loadHTML($html);

			//Get base table
			$table = $dom->getElementById('ec:table2');
			if (!isset($table)) throw new DataException('Table not found');

			//Get table body
			$tableBody = $table->childNodes->item(0);
			if (!isset($tableBody)) throw new DataException('Table body not found');

			$article = db_list_cond('zi_ab_articles', "ID_Conf=$CONFID AND ECID=$ecid")[0];

			//Retrieve paper data
			foreach ($tableBody->getElementsByTagName('tr') as $tr) {
				$tds = $tr->getElementsByTagName('td');

				//Skip headers
				if ($tds->length < 2) continue;
				if ($tds->item(2)->nodeValue === 'email') continue;

				$firstname = $tds->item(0)->nodeValue;
				$lastname = $tds->item(1)->nodeValue;
				$email = $tds->item(2)->nodeValue;
				$iscorr = empty($tds->item(6)->nodeValue) ? 'N' : 'Y';

				$author = array(
					'ID_Article' => $article->ID,
					'FirstName' => $firstname,
					'LastName' => $lastname,
					'EMail' => $email,
					'IsCorr' => $iscorr
				);

				db_add_TH('zi_ab_authors', $author);
			}
		}
		g_lev('Таблица авторов из EasyChair обновлена', __FUNCTION__);
	} catch (DataException $e) {
		g_ldx($e->getMessage(), $e->getData());
	}
	curl_close($curl);
	return 1;
}

function getInside()
{
	$USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
	$COOKIE_FILE = get_temp_dir() . 'cookie.txt';
	$LOGIN_FORM_URL = 'https://easychair.org/account/signin';
	$LOGIN_ACTION_URL = 'https://easychair.org/account/verify';

	$postValues = array(
		'name' => EC_LOGIN,
		'password' => EC_PASSWORD
	);

	//Initiate cURL.
	$curl = curl_init();

	//The action URL of the login form.
	curl_setopt($curl, CURLOPT_URL, $LOGIN_ACTION_URL);

	//Tell cURL that we want to carry out a POST request.
	curl_setopt($curl, CURLOPT_POST, true);

	//Set our post fields / date (from the array above).
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));

	//We don't want any HTTPS errors.
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	//Where our cookie details are saved.
	curl_setopt($curl, CURLOPT_COOKIEJAR, $COOKIE_FILE);

	//Sets the user agent.
	curl_setopt($curl, CURLOPT_USERAGENT, $USER_AGENT);

	//Tells cURL to return the output once the request has been executed.
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	//Allows us to set the referer header.
	curl_setopt($curl, CURLOPT_REFERER, $LOGIN_FORM_URL);

	//Do we want to follow any redirects?
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

	//Execute the login request.
	curl_exec($curl);

	//Check for errors!
	if (curl_errno($curl)) {
		throw new Exception(curl_error($curl));
	}

	return $curl;
}
