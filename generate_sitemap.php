<?php

// Configuration
$urlsFile = 'sitemap_urls.txt';
$outputFile = 'public/sitemap.xml';
$maxUrls = 50000; // Maximum URLs per sitemap (Google's limit is 50,000)
$trackedUrlsFile = 'sitemap_indexed_urls.txt'; // File to track indexed URLs

// Load existing tracked URLs
$trackedUrls = file_exists($trackedUrlsFile) 
    ? file($trackedUrlsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) 
    : [];

// Read URLs from file
$urls = file($urlsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$urls = array_slice($urls, 0, $maxUrls); // Limit to max URLs

// Create XML
$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

// Add each URL to the sitemap
foreach ($urls as $url) {
    $url = trim($url);
    if (empty($url)) continue;
    
    $urlElement = $xml->addChild('url');
    $urlElement->addChild('loc', htmlspecialchars($url));
    $urlElement->addChild('lastmod', date('Y-m-d'));
    
    // Set different change frequency and priority for homepage vs other pages
    if ($url == 'https://tiktokviewer.com/' || $url == rtrim('https://tiktokviewer.com', '/')) {
        $urlElement->addChild('changefreq', 'daily');
        $urlElement->addChild('priority', '1.0');
    } else if (strpos($url, '/user/') !== false) {
        $urlElement->addChild('changefreq', 'weekly');
        $urlElement->addChild('priority', in_array($url, $trackedUrls) ? '0.6' : '0.8');
    } else {
        $urlElement->addChild('changefreq', 'weekly');
        $urlElement->addChild('priority', in_array($url, $trackedUrls) ? '0.5' : '0.7');
    }
    
    // Add to tracked URLs if not already there
    if (!in_array($url, $trackedUrls)) {
        $trackedUrls[] = $url;
    }
}

// Save the XML sitemap
$dom = new DOMDocument('1.0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($xml->asXML());
file_put_contents($outputFile, $dom->saveXML());

// Update tracked URLs file
file_put_contents($trackedUrlsFile, implode(PHP_EOL, $trackedUrls));

echo "Sitemap generated successfully at {$outputFile} with " . count($urls) . " URLs.\n"; 