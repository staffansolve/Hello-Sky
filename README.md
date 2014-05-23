Hello-Sky
=========
A link to the related blog post will be included!

First, a disclaimer: Since the author is a relative newbie to php, this script is somewhat clumsy, not very elegant and also lacks proper error handling. But it works. Any tips on improvements are welcome.

Method
====
We will first check the weather forecast from YR for a clear sky in the next few days, then check if any of those dates appear in the ISS alert from NASA. If there are one or more matches, compile a report and email it to yourself. YR provides forecast intervals of 6 to 8 hours for each day, but we will only use the date value for this simple test. The ISS alerts from NASA only contain night-time sightings, given your selected place of observation. Both YR and NASA provide data using local time for the selected place, so we don't need to handle any time differences.

Script
====
Part 1. We read the weather forecast from yr.no and save it in plain text format. Replace the location in this example by looking up the URL on yr.no for your favourite spot.

Part 2. Using the explode function, we take away everything before "tabular" in the forecast. We then chop up the rest into single forecasts for each 6 to 8 hours, by the begin tag "time from=". The array $starrySky will save dates when the sky is expected to be clear. We will use $sameDay to make sure we only save a date once, since we do not need the 6 to 8 hour breakdown.

Part 3. Now we look for the YR sign of a clear sky, which is the weather symbol 1. For the length of all forecasts, we check each forecast if symbol number 1 is present. If it looks promising, we save the date - nine characters - to $starrySky, provided $sameDay does not tell us we already saved that date. Of course, even if the weather symbol 1 is present for a date, we cannot be sure that this is valid for the night. So we should really look at the 6 to 8 hour breakdown to make sure the symbol 1 is present during nighttime. But that could be a future improvement of the code.

Part 4. Before we get to the actual matching business, we obviously need the ISS alert from NASA. Read the RSS feed for the location. Parse the text by the tags "title" (the date) and "description", which are the full details of the upcoming ISS sighting. The sightings arrays, $item, are saved in the array $feed. Replace the location in this example by looking up the RSS feed URL on spotthestation.nasa.gov for your favourite spot.

Part 5. Now we go! This is where we check for matches and compile the report. Looping through the array of ISS alerts (10 is sufficient to cover the next few days), we check all saved dates in $clearSky, using an inner loop, for a match with each ISS alert date. If the date strings match, then the ISS sighting description is added to the report, $sightingsReport. Finally, if the report is not empty, we send the report  by email. It looks like this when received as email:

To: myemail@mydomain.com
Spot ISS on a starry night!

Date: Monday May 19, 2014
Time: 3:29 AM
Duration: 1 minute
Maximum Elevation: 27 ° 
Approach: 27 ° above N 
Departure: 16 ° above NNE

Date: Wednesday May 21, 2014
Time: 3:27 AM
Duration: 1 minute
Maximum Elevation: 14 ° 
Approach: 14 ° above NNW 
Departure: 10 ° above N

Notes 
==== 
To test that the script works, I changed the NASA location to a city where ISS passes more frequently.

During development, I did a lot of searching on the Internet and found tips and code to use. I want to thank for example: 
http://bavotasan.com/2010/display-rss-feed-with-php/ http://stackoverflow.com/questions/7638660/get-count-of-substring-occurrence-in-an-array https://gist.github.com/varemenos/2299634 http://www.tequilafish.com/2009/02/10/php-how-to-capture-output-of-echo-into-a-local-variable/

Contact: staffan@solve.se
