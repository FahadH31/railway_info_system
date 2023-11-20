<?php
$xmlFile = "railwayInfo.xml";
$xmlDoc = new DOMDocument();
$xmlDoc->load($xmlFile);

$display = "<h4>Timeline of Notable Events</h4>";

// Display History Events
$historyEvents = $xmlDoc->getElementsByTagName("event");
$display .= "<div class = info-section>";
foreach ($historyEvents as $event) {
    $date = $event->getElementsByTagName("date")[0]->nodeValue;
    $description = $event->getElementsByTagName("description")[0]->nodeValue;
    $display .= "<p><strong>$date</strong>: $description <br></p>";
}
$display .= "</div>";

// Display Interesting Facts
$interestingFacts = $xmlDoc->getElementsByTagName("fact");
$display .= "<br><h4>Fun Facts</h4><div style = 'margin-bottom: 40px;'class = 'info-section light-yellow'><ol>";
foreach ($interestingFacts as $fact) {
    $title = $fact->getElementsByTagName("title")[0]->nodeValue;
    $description = $fact->getElementsByTagName("description")[0]->nodeValue;
    $display .= "<strong><li>$title</strong>: $description</li><br>";
}
$display .= "</ol></div>";

// Output the generated content
echo $display;
?>
