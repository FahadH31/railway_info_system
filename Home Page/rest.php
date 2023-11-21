<?php

$conn = mysqli_connect('localhost:3306', 'root', 'fahad1306', 'RailwaySystemWebsite');

// Check connection
if (!$conn) {
  echo 'Connection error: ' . mysqli_connect_error();
}
// Endpoint to fetch educational information
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch educational information from the database
    $eduInfoQuery = 'SELECT * FROM educationalinfotrain';
    $eduInfoResult = mysqli_query($conn, $eduInfoQuery);

    $educationalInfo = array();

    if ($eduInfoResult) {
        while ($row = mysqli_fetch_assoc($eduInfoResult)) {
            $educationalInfo[] = $row;
        }
    }

    // Set response headers to JSON
    header('Content-Type: application/json');

    // Return educational information as JSON response
    echo json_encode($educationalInfo);
} else {
    // If an unsupported request method is used
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('message' => 'Method Not Allowed'));
}
?>
