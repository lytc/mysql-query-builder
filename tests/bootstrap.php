<?php
require_once __DIR__ . '/../library/autoload.php';


\Qb\Pdo::setDsn('mysql:dbname=test-query-builder;host=127.0.0.1');
\Qb\Pdo::setUsername('root');
\Qb\Pdo::getInstance()->setLog(__DIR__ . '/query.log');