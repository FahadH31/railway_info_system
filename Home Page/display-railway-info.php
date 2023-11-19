<?php
$xmlFile = "railwayInfo.xml";
$xmlDoc = new DOMDocument();
$xmlDoc->load($xmlFile);

$display = "";

// Display History Events
$historyEvents = $xmlDoc->getElementsByTagName("event");
$display .= "<h4>History of Railways</h4><ul>";
foreach ($historyEvents as $event) {
    $date = $event->getElementsByTagName("date")[0]->nodeValue;
    $description = $event->getElementsByTagName("description")[0]->nodeValue;
    $display .= "<li><strong>$date</strong>: $description</li>";
}
$display .= "</ul>";

// Display Interesting Facts
$interestingFacts = $xmlDoc->getElementsByTagName("fact");
$display .= "<h2>Interesting Facts about Railways</h2><ul>";
foreach ($interestingFacts as $fact) {
    $title = $fact->getElementsByTagName("title")[0]->nodeValue;
    $description = $fact->getElementsByTagName("description")[0]->nodeValue;
    $display .= "<li><strong>$title</strong>: $description</li>";
}
$display .= "</ul>";

// Display Train Models
$trainModels = $xmlDoc->getElementsByTagName("model");
$display .= "<h2>Popular Train Models</h2><ul>";
foreach ($trainModels as $model) {
    $name = $model->getElementsByTagName("name")[0]->nodeValue;
    $description = $model->getElementsByTagName("description")[0]->nodeValue;
    $display .= "<li><strong>$name</strong>: $description</li>";
}

// Output the generated content
echo $display;
?>
