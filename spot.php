# Part 1

<?php
$yrWeather = new DOMDocument();
$yrWeather->load('http://www.yr.no/place/Sweden/Scania/Lund/forecast.xml');

header("Content-Type:text/plain");
ob_start();
echo $yrWeather->saveXML();
$yrWeatherText = ob_get_contents();
ob_end_clean();

# Part 2

$starrySky = array();
$sameDay = '';
$start = '<tabular>';

$allForecasts = explode($start,$yrWeatherText);

$forecastStart = '<time from=';
$forecastsLength = substr_count($allForecasts[1] , $forecastStart) - 1;
$restOfForecasts = $allForecasts[1];
$forecast = explode($forecastStart,$restOfForecasts);

# Part 3

for ($i = 0; $i <= $forecastsLength; $i++) {
    if (strpos ( $forecast[$i], 'symbol number="3"') !== false){
    	if (substr($forecast[$i],1,10) !== $sameDay){
			array_push($starrySky,substr($forecast[$i],1,10));
		}
		$sameDay = substr($forecast[$i],1,10);
	}
}

# Part 4

$nasaStationRss = new DOMDocument();
$nasaStationRss->load('http://spotthestation.nasa.gov/sightings/indexrss.cfm?country=Sweden&region=None&city=Lund');
$feed = array();
foreach ($nasaStationRss->getElementsByTagName('item') as $node) {
	$item = array ( 
		'date' => $node->getElementsByTagName('title')->item(0)->nodeValue,
		'description' => $node->getElementsByTagName('description')->item(0)->nodeValue
		);
	array_push($feed, $item);
}

# Part 5

$sightingsReport = '';
for($x=0;$x<10;$x++) {
	$date = $feed[$x]['date'];
	$description = $feed[$x]['description'];
	for ($i = 0; $i <= count($starrySky); $i++){
		if (strpos ( $date, $starrySky[$i]) !== false){
			$sightingsReport = $sightingsReport.$description;
		}
	}
}
if ($sightingsReport !== ''){
	mail('myemail@mydomain.com', 'Spot ISS on a starry night!', strip_tags($sightingsReport));
	echo 'Sightings report sent!';
}else{
	echo 'No sightings ahead';
}
?>