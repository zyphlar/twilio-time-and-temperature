<?php

require('time-temp.inc.php');

$cache_path = "/tmp/timetemp-".md5($url.$wunderkey.$feature.$path).".tmp";
$from_cache = 0;

// by default do voice
$voice = true;
$sms = false;

if (array_key_exists('type', $_GET)) { 
    if ($_GET['type'] == "voice") { $voice = true; $sms = false; }
    if ($_GET['type'] == "sms") { $voice = false; $sms = true; }
}

// update weather every half hour
if (file_exists($cache_path) && (time() - filemtime($cache_path) < 1800) ) {
    $response = file_get_contents($cache_path);
    $from_cache = 1;
} else {
    $response = file_get_contents($url.$wunderkey.$feature.$path);
    file_put_contents($cache_path, $response);
}

$jso = json_decode($response);
$obs = $jso->current_observation;

$tz = new DateTimeZone($obs->local_tz_long);

$obs_date = new DateTime($obs->local_time_rfc822);

$date = new DateTime('now', $tz);
$dst = $date->format('I');
if ($dst === "1") {
   $dst_text = "is";
} else {
   $dst_text = "is not";
}

if ($obs->pressure_trend == "+") {
    $pres_text = "and rising";
} elseif ($obs->pressure_trend == "-") {
    $pres_text = "and falling";
} else {
    $pres_text = "and steady";
}

header('Content-Type: application/xml');

$out = '<?xml version="1.0" encoding="UTF-8"?>
<!-- from_cache: '.$from_cache.' filemtime: '.filemtime($cache_path).' -->
<Response>';

if ($voice) {
    $out .= '<Say voice="man">Hello. '.$date->format('g:i:s A').', '.$date->format('F j, Y').', was the current time when this call began. It is week number '.((int)$date->format('W')).' and day number '.((int)$date->format('z')+1).' of the year. It '.$dst_text.' currently Daylight Saving Time in '.$obs->display_location->city.'.</Say>
    <Say voice="woman">Thank you for calling '.$obs->display_location->city.' Time and Temperature: a free hobby service, courtesy of '.$courtesy_of_phonetic.', and Weather Underground dot com.</Say>
    <Say voice="man">The temperature in '.$obs->observation_location->city.' was '.$obs->temp_f.' degrees Fahrenheit today at '.$obs_date->format('g:i A').'. The weather was '.$obs->weather.' with winds '.$obs->wind_string.'. The dew point was '.$obs->dewpoint_f.' degrees. The pressure was '.$obs->pressure_in.' inches of mercury '.$pres_text.'. The visibility was '.$obs->visibility_mi.' miles. The temperature felt like '.$obs->feelslike_f.' degrees.</Say>
    <Gather input="dtmf" timeout="5" numDigits="1">
        <Say voice="woman">Please press any key to hear this message again.</Say>
    </Gather>
    <Say voice="woman">Thanks again for calling. '.$motds[0].' Goodbye for now.</Say>';
}

if ($sms) {
    $out .= '<Message>The current time in '.$obs->display_location->city.' is '.$date->format('g:i:s A').', on '.$date->format('F j, Y').'. It is week number '.((int)$date->format('W')).' and day number '.((int)$date->format('z')+1).' of the year. It '.$dst_text.' currently Daylight Saving Time. The temperature in '.$obs->observation_location->city.' was '.$obs->temp_f.'F today at '.$obs_date->format('g:i A').' ('.$courtesy_of.' via wunderground.com)</Message>';
}

$out .= '</Response>';

echo $out;
