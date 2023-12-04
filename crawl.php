<?php

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
