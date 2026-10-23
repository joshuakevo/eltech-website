<?php
// Public list of current course prices, used by the Learn AI page
require dirname(__DIR__) . '/lib/bootstrap.php';

header('Cache-Control: public, max-age=300');
json_out(['ok' => true, 'prices' => course_prices()]);
