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
        $parsedUrl = parse_url($url);
    
        if (!empty($parsedUrl['host'])) {
            $robotsTxtUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . '/robots.txt';
    
            // Fetch and parse the robots.txt file
            $robotsTxtContent = $this->fetchPageContent($robotsTxtUrl);
    
            if ($robotsTxtContent !== false) {
                try {
                    // Check if the user-agent is allowed to crawl the URL
                    $userAgent = '*'; // Assume the user-agent is '*'
    
                    // can adjust this based on requirements
                    if (preg_match('/User-agent: (.*)/i', $robotsTxtContent, $userAgentMatch)) {
                        $userAgent = trim($userAgentMatch[1]);
                    }
    
                    // Check if the URL is allowed for the specified user-agent
                    if (preg_match('/Disallow: (.*)/i', $robotsTxtContent, $disallowMatch)) {
                        $disallowedPaths = explode("\n", $disallowMatch[1]);
                        foreach ($disallowedPaths as $disallowedPath) {
                            $disallowedPath = trim($disallowedPath);
                            if ($disallowedPath !== '' && strpos($parsedUrl['path'], $disallowedPath) === 0) {
                                return false; // URL is disallowed
                            }
                        }
                    }
    
                    return true; // Default to allowing the URL if no specific rules are found
                } catch (Exception $e) {
                    // Handle any exceptions that might occur during parsing
                    // Log the error or perform additional error handling as needed
                    error_log("Error parsing robots.txt: " . $e->getMessage());
                    return true; // Default to allowing the URL if an error occurs
                }
            } else {
                // Log or handle the case where fetching robots.txt fails
                error_log("Error fetching robots.txt from $robotsTxtUrl");
            }
        }
    
        return true; // Default to allowing the URL if there is no host or if fetching the robots.txt fails
    }
    

    private function fetchPageContent($url) {
        // Initialize cURL session
        $curl = curl_init();
    
        // Set cURL options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    
        // can add more cURL options here, such as setting headers, timeouts, etc.
    
        // Execute cURL session
        $htmlContent = curl_exec($curl);
    
        // Check for cURL errors
        if (curl_errno($curl)) {
            // Handle cURL errors
            // Log the error or perform additional error handling as needed
            error_log("cURL Error: " . curl_error($curl));
    
            // Return false to indicate failure
            return false;
        }
    
        // Close cURL session
        curl_close($curl);
    
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
