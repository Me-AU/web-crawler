# Web Spider

## Overview
This PHP-based web spider is designed to systematically crawl websites, extract information, and perform content searches. The project includes a simple user interface for entering seed URLs and adheres to ethical crawling guidelines. Optional features such as depth control, concurrency, and advanced search capabilities are provided for enhanced functionality.

## Features
- **URL Queue:** Maintain a queue of URLs to be crawled, starting with a seed URL.
- **Crawling:** Send HTTP requests to URLs, retrieve HTML content, and save it.
- **HTML Parsing:** Extract relevant information from crawled pages using PHP's DOMDocument.
- **URL Extraction:** Extract hyperlinks from crawled HTML and add them to the URL queue.
- **Depth Limit:** Set a depth limit to control spider's crawling depth.
- **Output:** Display/log extracted information like title, meta description, etc.
- **Content Search Module:** Search for a specified string within crawled content.
- **Robots.txt Compliance:** Respect rules specified in the robots.txt file of the website.
- **Error Handling:** Implement error handling for fetching, parsing, and other issues.
- **Bonus (Attempted):**
  - **Concurrency:** Explore and implement concurrency for improved performance.
  - **Filtering:** Exclude certain URLs from crawling based on specific criteria.
  - **Persistent Storage:** Store crawled data persistently, e.g., in a local database.
  - **Advanced Search Features:** Extend search module with advanced features.

## Usage
1. Clone the repository: `git clone https://github.com/Me-AU/web-crawler.git`
2. Navigate to the project directory: `cd web-crawler`
3. Open `index.html` in a web browser and enter the seed URL.
4. Click "Start Crawling" to initiate the spider.

## Technologies Used
- PHP
- HTML
- JavaScript (AJAX)
- DOMDocument
- cURL (intended for advanced HTTP requests)

## Notes
- Ensure responsible crawling: respect website rules and policies, avoid overloading servers.
- Test thoroughly before deploying on any website.
- Feel free to customize and extend the code based on your requirements.

## Contributors
- [Ahsan Ullah](https://github.com/Me-AU)

## License
This project is licensed under the [MIT License](LICENSE).
