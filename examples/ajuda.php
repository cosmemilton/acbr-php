<?php
declare(strict_types=1);
require dirname(__DIR__) . '/vendor/autoload.php';
$contract = \Acbr\Contract::bundled();
echo json_encode($contract->help($argv[1] ?? 'Det[].Prod.vProd'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), "\n";
