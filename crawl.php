<?php

// Allow CORS (Cross-Origin Resource Sharing)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle pre-flight CORS requests (OPTIONS method)
    http_response_code(200);
    exit();
}

if (isset($_POST['seedUrl'])) {
    $seedUrl = $_POST['seedUrl'];

    // Include the WebSpider class and initiate crawling
    require_once('WebSpider.php'); // Adjust the path based on your project structure

    $webSpider = new WebSpider($seedUrl);
    $webSpider->crawl();
} else {
    // Handle the case when the seed URL is not provided
    echo "Seed URL is missing.";
}
