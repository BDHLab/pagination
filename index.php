<?php

use BDHLab\Pagination\Pagination;

$test = new Pagination(array(
	'url' => 'http://www.google.com/',
	'total_rows' => 100,
	'max_items' => 10,
	'max_pages' => 5,
	'current_page' => 1
));
print_r($test->toArray());