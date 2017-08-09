<?php

require_once('./vendor/autoload.php');

use Symfony\Component\DomCrawler\Crawler;

error_reporting(E_ALL);
ini_set('display_errors', true);

$content = file_get_contents('./pages/home.html');
$crawler = new Crawler($content);

$ics = $crawler->filter('ol.category-group-container')
	->each(function (Crawler $node) {
		return [
			'group' => trim($node->filter('li.category-group h3')->text()),
			'categories' => $node->filter('ol a.category')->each(function (Crawler $cat) {
				return [
					'slug' => substr($cat->attr('href'), 10),
					'title' => trim($cat->text()),
				];
			}),
		];
	});

echo json_encode([
	'grouped_categories' => $ics
]);

