<?php

class WebSpider {
    private $urlQueue = [];
    private $visitedUrls = [];
    private $depthLimit = 3;
    private $extractedData = [];

    public function __construct($seedUrl) {
        $this->urlQueue[] = $seedUrl;
    }

    public function crawl() {
        while (!empty($this->urlQueue)) {
            $currentUrl = array_shift($this->urlQueue);

            if (!$this->canVisitUrl($currentUrl)) {
                continue;
            }

            $htmlContent = $this->fetchPageContent($currentUrl);
            $data = $this->processPageContent($currentUrl, $htmlContent);

            $this->visitedUrls[] = $currentUrl;

            if ($this->depthLimit > 0) {
                $this->enqueueLinks($htmlContent);
            }

            $this->depthLimit--;

            // TODO: implement concurrency here for improved performance
            // use multi-curl or asynchronous requests
            sleep(1); // Respectful crawling delay

            // Store the extracted data
            $this->extractedData[] = $data;
        }

        // Return the extracted data
        return $this->extractedData;
    }

    private function canVisitUrl($url) {
        // TODO: Implement robots.txt compliance check here
        // Return false if the URL is not allowed to be crawled
        return true;
    }

    private function fetchPageContent($url) {
        // TODO: Implement HTTP request to fetch the HTML content
        // use cURL or other HTTP libraries for this
        $htmlContent = file_get_contents($url);
        return $htmlContent;
    }

    private function processPageContent($url, $htmlContent) {
        // TODO: Implement HTML parsing using DOMDocument to extract relevant information
        // Extract and display/log title, meta description, etc.
        $dom = new DOMDocument;
        @$dom->loadHTML($htmlContent);

        $title = $dom->getElementsByTagName('title')->item(0)->nodeValue;
        $metaDescription = ''; // Extract meta description here

        return [
            'url' => $url,
            'title' => $title,
            'metaDescription' => $metaDescription,
        ];
    }

    private function enqueueLinks($htmlContent) {
        // TODO: Implement URL extraction and add to the URL queue
        // use regular expressions or a dedicated HTML parser
        $pattern = '/<a\s[^>]*?href=(["\'])(.*?)\1/';
        preg_match_all($pattern, $htmlContent, $matches);

        foreach ($matches[2] as $link) {
            $absoluteUrl = $this->makeAbsoluteUrl($link);
            if ($absoluteUrl && !in_array($absoluteUrl, $this->visitedUrls) && !in_array($absoluteUrl, $this->urlQueue)) {
                $this->urlQueue[] = $absoluteUrl;
            }
        }
    }

    private function makeAbsoluteUrl($url) {
        // Convert relative URLs to absolute URLs
        // TODO: need to handle base URLs and other cases
        // TODO: This is a simple example, enhance this logic
        $parsedUrl = parse_url($url);
        if (empty($parsedUrl['scheme']) || empty($parsedUrl['host'])) {
            return null;
        }
        return $url;
    }
}
