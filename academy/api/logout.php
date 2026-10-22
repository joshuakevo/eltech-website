<?php
require dirname(__DIR__) . '/lib/bootstrap.php';

require_post_with_csrf();

$_SESSION = [];
session_destroy();
redirect('../index.php');
