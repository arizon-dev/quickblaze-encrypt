<?php
// require("./vendor/autoload.php");
require './vendor/delfimov/translate/src/Translate.php';
require './vendor/delfimov/translate/src/Loader/LoaderInterface.php';
require './vendor/delfimov/translate/src/Loader/PhpFilesLoader.php';

use DElfimov\Translate\Translate;
use DElfimov\Translate\Loader\PhpFilesLoader;
use Monolog\Logger; // PSR-3 logger, not required  
use Monolog\Handler\StreamHandler;

$log = new Logger('Translate');
$log->pushHandler(new StreamHandler('./Modules/composerlogs/latest.log', Logger::WARNING));

$t = new Translate(
    new PhpFilesLoader(__DIR__ . "/messages"),
    [
        "default" => "en",
        "available" => ["en", "ru"],
    ],
    $log // optional
);

$num = rand(0, 100);

$t->setLanguage("en"); // this is not required, language will be auto detected with Accept-Language HTTP header
echo $t->t('some string') . "\n\n"; // or $t('some string');
echo $t->plural('%d liters', $num) . "\n\n";
echo $t->plural("The %s contains %d monkeys", $num, ['tree', $num]) . "\n\n";

$num = rand(0, 100);

$t->setLanguage("ru");
echo $t->t('some string')."\n\n"; // or $t('some string');
echo $t->plural('%d liters', $num) . "\n\n";
echo $t->plural("The %s contains %d monkeys", $num, ['tree', $num]) . "\n\n";
