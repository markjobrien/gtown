<?php
require_once 'simple_html_dom.php';


function gtown_ajax_hook() {

	$callback = isset($_REQUEST["callback"]) ? $_REQUEST["callback"] : "";

	if( isset($_POST['action']) ) {
		$match = new Match($_POST['form_data'], $_POST['match_id']);

		switch($_POST['action']) {
			case 'addMatch':
				$rtnjsonobj->res = $match->insert_match();
			break;
			case 'editMatch':
				$rtnjsonobj->res = $match->update_match();
			break;
			case 'removeMatch':
				$rtnjsonobj->res = $match->remove_match();
			break;
			case 'scrapeUrl':
				$rtnjsonobj->res = scrape_matches($_POST['form_data']);
			break;
			default:
			return;
		}

		header("content-type: application/json;");
		echo $callback. '('. json_encode($rtnjsonobj) . ')';
		die();
	}
}
add_action('init', 'gtown_ajax_hook');

class Match {
	public $match_data, $match_id = 0, $tbl_name = 'matches';

	function __construct($form_data, $match_id) {
		$this->match_id = $match_id > 0 ? $match_id : 0;

		$form_array = array();
		parse_str($form_data, $form_array);

		$match = new stdClass();
		foreach ($form_array as $key => $value) {
			$match->$key = $value;
		}

		$this->match_data = array(
			'type' => $match->match_type,
			'team' => $match->team_type,
			'home' => $match->home_team,
			'away' => $match->away_team,
			'venue' => $match->venue,
			'comp' => $match->comp,
			'home_score' => $match->home_score,
			'away_score' => $match->away_score,
			'when_match' => get_ISO_date( $match->when, $match->when_time )
		);
	}

	function insert_match() {
		global $wpdb;

		if ( match_exists($this->match_data) > 0 ) {
			$status_code = 0;
		} else {
			$wpdb->insert($this->tbl_name, $this->match_data, array('%s','%s','%s','%s','%s','%s','%s','%s','%s'));
			$status_code = $wpdb->insert_id > 0 ? 1 : 0;
		}

		return $status_code;
	}

	function update_match() {
		global $wpdb;
		$update = $wpdb->update( $this->tbl_name, $this->match_data, array( 'id' => $this->match_id ), array('%s','%s','%s','%s','%s','%s','%s','%s','%s'), array( '%s' ));
		$status_code = $update > -1 ? 1 : 0;
		return $status_code;
	}

	function remove_match() {
		global $wpdb;
		$update = $wpdb->delete( $this->tbl_name, array( 'id' => $this->match_id ) );
		$status_code = $update > -1 ? 1 : 0;
		return $status_code;
	}
}

function match_exists($match_data) {
	global $wpdb;

	$sql = "SELECT * FROM matches WHERE type = '" . $match_data['type'] . "' AND team = '" . $match_data['team']
		. "' AND comp = '" . $match_data['comp'] . "' AND home = '" . $match_data['home']
		.  "' AND away = '" . $match_data['away'] . "' AND when_match = '" . $match_data['when_match'] . "'" ;
	$matches = $wpdb->get_results($sql);
	return count($matches);
}


function retrieve_matches($match_type, $year) {
	global $wpdb;

	$from_now = $match_type == 0 ? " AND when_match >= NOW()" : " AND YEAR(when_match) = " . $year;
	$order = $match_type == 0 ? " ASC" : "DESC";

	$sql = "SELECT * FROM matches WHERE type = " . $match_type . $from_now ." ORDER BY `matches`.`when_match` " . $order;
	$matches = $wpdb->get_results($sql);
	return $matches;
}


function display_matches($is_admin = false, $match_type, $year) {

	$matches = retrieve_matches($match_type, $year);

	if ($match_type) {
		$html = '<h2>Results</h2><table><thead><tr><th>Team</th><th>Home</th><th>Score</th><th>Away</th>
		<th>Score</th><th>Competition</th>
		<th>Venue</th><th>Date</th><th>Time</th></tr>';
	} else {
		$html = '<h2>Fixtures</h2><table><thead><tr><th>Team</th><th>Home</th><th>Away</th><th>Competition</th>
		<th>Venue</th><th>Date</th><th>Time</th></tr>';
	}


	if ($is_admin) {
		$html .= '<th colspan="2"></th>';
	}

	$html .= '</tr></thead><tbody>';

	foreach($matches as $match) {

		$date = DateTime::createFromFormat('Y-m-d H:i:s', $match->when_match);

		$html .= '<tr id="' . $match->id . '"><td class="team_type">' . stripslashes($match->team) . '</td>
			<td class="home_team">' . stripslashes($match->home) . '</td>'.
			($match_type == 1 ? '<td class="home_score">' . $match->home_score . '</td>' : '') .'
			<td class="away_team">' . stripslashes($match->away) . '</td>'.
			($match_type == 1 ? '<td class="away_score">' . $match->away_score . '</td>' : '') .'
			<td class="comp">' . stripslashes($match->comp) . '</td>
			<td class="venue">' . stripslashes($match->venue) . '</td>
			<td class="when" data-when="' . $date->format('Y-m-d') . '">' . $date->format('d/m/Y') . '</td>
			<td class="when_time">' . $date->format('H:i') . '</td>';

		if ($is_admin) {
			$html .= '<td><a class="edit_match" title="' . $match_type . '" href="#">Edit</a></td><td><a class="remove_match" href="#">Remove</a></td></tr>';
		} else {
			$html .= '</tr>';
		}
	}

	return $html . "</tbody></table>";
}

function get_ISO_date( $when, $when_time ) {
	date_default_timezone_set('Europe/Dublin');
	$when = strtotime($when . ' ' . $when_time);
	$date = date('Y-m-d H:i:s', $when);
	return $date;
}

function get_upcoming_fixtures($limit) {
	global $wpdb;
	$sql = "SELECT * FROM matches WHERE type = 0 AND when_match >= NOW() ORDER BY `matches`.`when_match` ASC limit " . $limit;
	$matches = $wpdb->get_results($sql);

	$html = '<ul>';
	foreach($matches as $match){

		if ($match->home == 'Garristown') {
			$opponent = $match->away;
		} else {
			$opponent = $match->home;
		}

		$date = DateTime::createFromFormat('Y-m-d H:i:s', $match->when_match);

		$html .= '<li><p>' . $match->comp . ' - ' . $match->team . ' v ' . $opponent . ' in ' .
			$match->venue . ' on ' . $date->format('jS M') . ' at ' . $date->format('H:i') . '</p></li>';
	}
	$html .= '</ul>';

	echo $html;
}

function get_latest_lotto() {
	$args = array(
		'posts_per_page'   => 1,
		'category_name'    => 'lotto',
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'post_type'        => 'post',
		'post_status'      => 'publish'
	);

	$posts = get_posts( $args );

	$post = $posts[0];
	$content = $post->post_content;

	$html = '<div class="latest-lotto sidebar-section">
				<h3>Latest Lotto - ' . get_the_time('F j, Y') . ':</h3>
				<ul>' . extract_lotto_numbers($content) . '</ul>' .
		'<a href=' . get_permalink() . '>' . wp_strip_all_tags($content) . '</a></div>';

	echo $html;
}


function extract_lotto_numbers($txt) {

	$re1='(Lotto)';	# Word 1
	$re2='(:)';	# Any Single Character 1
	$re3='(\\s+)';	# White Space 1
	$re4='(\\d+)';	# Integer Number 1
	$re5='.*?';	# Non-greedy match on filler
	$re6='(\\d+)';	# Integer Number 2
	$re7='.*?';	# Non-greedy match on filler
	$re8='(\\d+)';	# Integer Number 3
	$re9='.*?';	# Non-greedy match on filler
	$re10='(\\d+)';	# Integer Number 4

	if ($c=preg_match_all ("/".$re1.$re2.$re3.$re4.$re5.$re6.$re7.$re8.$re9.$re10."/is", $txt, $matches)) {
		$word1=$matches[1][0];
		$c1=$matches[2][0];
		$ws1=$matches[3][0];
		$int1=$matches[4][0];
		$int2=$matches[5][0];
		$int3=$matches[6][0];
		$int4=$matches[7][0];
	}

	return '<li>' . $int1 . '</li><li>' . $int2 . '</li><li>' . $int3 . '</li><li>' . $int4 . '</li>';
}

function get_latest_uploads() {
	$args = array( 'post_mime_type' => 'application/pdf', 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => 'published' );
	$attachments = get_posts($args);

	$html .= '<div class="latest-uploads sidebar-section"><h3>Latest Uploads:</h3><ul>';
	if ($attachments) {
		foreach ( $attachments as $attachment ) {
			$html .= '<li><a href="' . wp_get_attachment_url($attachment->ID) . '">' . apply_filters( 'the_title' , $attachment->post_title ) . '</a></li>';
		}
	} else {
		$html .= '<p>Sorry, no uploads to display.</p>';
	}

	$html .= '</ul><h3>Important Uploads:</h3><ul>';
	$uploads = get_attachments_by_media_tags('media_tags=important&numberposts=3');
	if ($uploads) {
		foreach ( $uploads as $upload ) {
			$html .= '<li><a href="' . wp_get_attachment_url($upload->ID) . '">' . apply_filters( 'the_title' , $upload->post_title ) . '</a></li>';
		}
	} else {
		$html .= '<p>Sorry, no uploads to display.</p>';
	}

	$html .= '</ul><p><a href="#">See all uploads &#8594; </a></p></div>';

	echo $html;
}

function scrape_matches( $form_data ) {

	$form_array = array();
	parse_str($form_data, $form_array);

	$url = $form_array['url_scrape'];
	$match_type = $form_array['match_type'];
	$team_type = $form_array['team_type'];
	$comp = $form_array['comp'];
	$add_these = $_POST['add_these'];


	// Create DOM from URL or file
	$html = file_get_html($url);

	$matches = array();

	foreach($html->find('a.fixture_row') as $element) {

		if (stripos($element, "Garristown") !== false) {

			$date = $element->find('.date', 0)->innertext;
			$match = array(
				'type' => $match_type,
				'team' => $team_type,
				'home' => $element->find('.home', 0)->innertext,
				'away' => $element->find('.away', 0)->innertext,
				'venue' => $element->find('.venue', 0)->innertext,
				'comp' => $comp,
				'home_score' => trim($element->find('.score span', 0)->innertext),
				'away_score' => trim($element->find('.score_a span', 0)->innertext),
				'when_match' => get_ISO_date( date( 'Y-m-d', strtotime($date) ), $element->find('.time', 0)->innertext )
			);

			$matches[] = $match;
			unset($match);
		}
	}

	if ($add_these == "true") {
		$i = 0;
		foreach($matches as $match_data) {
			$match = new Match();
			$match->match_data = $match_data;
			$added = $match->insert_match();
			$i = $i + $added;
		}
		return $i;
	} else {
		return $matches;
	}
}



?>
