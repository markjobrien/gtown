<?php
require_once 'simple_html_dom.php';

// Create DOM from URL or file
$html = file_get_html('http://www.dublingaa.ie/competitions/results/92856');
$comp = 'AFL5';
$team_type = 'Men\'s Senior';

$fixtures = array();

foreach($html->find('a.fixture_row') as $element) {

	echo $element;


	if (stripos($element, "Garristown") !== false) {
		$fixture->team = $team_type;
		$fixture->comp = $comp;
		$fixture->home = $element->find('.home', 0)->innertext;
		$fixture->away = $element->find('.away', 0)->innertext;
		$fixture->venue = $element->find('.venue', 0)->innertext;
		$date = $element->find('.date', 0)->innertext;
		$fixture->when = date( 'd/m/Y', strtotime($date) );
		$fixture->when_time = $element->find('.time', 0)->innertext;
		$fixtures[] = $fixture;
		unset($fixture);
	}

}

echo '<pre>';
print_r($fixtures);
echo '</pre>';


?>
