<?php
$xmlFile = "../data/educational-info.xml";
$xmlDoc = new DOMDocument();
$xmlDoc->load($xmlFile);

$display = "<h4>Timeline of Notable Events</h4>";

// Display History Events
$historyEvents = $xmlDoc->getElementsByTagName("event");
$display .= "<div class='info-section'>";
foreach ($historyEvents as $event) {
    $date = $event->getElementsByTagName("date")->item(0);
    $description = $event->getElementsByTagName("description")->item(0);
    $image = $event->getElementsByTagName("image")->item(0);

    // Check if the elements exist before accessing their nodeValue
    if ($date && $description) {
        $dateValue = $date->nodeValue;
        $descriptionValue = $description->nodeValue;

        $display .= "<p><strong>$dateValue</strong>: $descriptionValue <br></p>";

        // Check if the image element exists before accessing its nodeValue
        if ($image) {
            $imageValue = $image->nodeValue;
            $display .= "<img style='width: 20vw; height: auto; border: 3px solid black; margin: 10px; margin-top: 0px;' src='$imageValue'>";
        }
    }
}
$display .= "</div>";

// Display Interesting Facts
$interestingFacts = $xmlDoc->getElementsByTagName("fact");
$display .= "<br><h4>Fun Facts</h4><div class='info-section'><p>";
foreach ($interestingFacts as $fact) {
    $title = $fact->getElementsByTagName("title")->item(0);
    $description = $fact->getElementsByTagName("description")->item(0);
    $image = $fact->getElementsByTagName("image")->item(0);

    if ($title && $description) {
        $titleValue = $title->nodeValue;
        $descriptionValue = $description->nodeValue;
        $display .= "<p><strong>$titleValue</strong>: $descriptionValue<br></p>";
        // Check if the image element exists before accessing its nodeValue
        if ($image) {
            $imageValue = $image->nodeValue;
            $display .= "<img style='width: 20vw; height: auto; border: 3px solid black; margin: 10px; margin-top: 0px;' src='$imageValue'>";
        }
    }
    
}
$display .= "</p></div><br>";

// Output the generated content
echo $display;

?>
