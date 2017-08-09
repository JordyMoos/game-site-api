<?php

require_once('./vendor/autoload.php');

use Symfony\Component\DomCrawler\Crawler;

error_reporting(E_ALL);
ini_set('display_errors', true);

$content = file_get_contents('./pages/home.html');
$crawler = new Crawler($content);


$ics = $crawler->filter('div.thumb-container')
	->each(function (Crawler $node, $i) {
		return [
			'slug' => substr($node->filter('a.item-link-main')->first()->attr('href'), 10),
			'title' => trim($node->filter('a.item-link-main')->first()->attr('title')),
                        'src' => $node->filter('span.thumb-image img')->first()->attr('src'),
		];
	});

echo json_encode([
	'categories' => $ics
]);

