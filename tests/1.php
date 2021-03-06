<?php

include "../src/BaseCrypt.php";
include "../src/BaseCrypt64.php";
include "../src/Examples/DigitCrypt64.php";
include "../src/Examples/DigitCrypt64Trash.php";
include "../src/Examples/CharsCrypt64.php";

/*echo ($decoded = \Coddism\BaseCrypt\Examples\DigitCrypt64::decode("n4%msiG")) . PHP_EOL;
die();*/

//echo ($in = "51375344").PHP_EOL;
echo ($in = rand(0, 10000000).'').PHP_EOL;
echo ($encoded = \Coddism\BaseCrypt\Examples\DigitCrypt64::encode($in)) . PHP_EOL;
echo ($decoded = \Coddism\BaseCrypt\Examples\DigitCrypt64::decode($encoded)) . PHP_EOL;
echo ($in == $decoded ? '+' : '!!!') . PHP_EOL;

echo ($in = rand(0, 100).'').PHP_EOL;
echo ($encoded = \Coddism\BaseCrypt\Examples\DigitCrypt64Trash::encode($in)) . PHP_EOL;
echo ($decoded = \Coddism\BaseCrypt\Examples\DigitCrypt64Trash::decode($encoded)) . PHP_EOL;
echo ($in == $decoded ? '+' : '!!!') . PHP_EOL;

echo ($encoded = \Coddism\BaseCrypt\Examples\CharsCrypt64::encode("DETHKLOK")) . PHP_EOL;
echo \Coddism\BaseCrypt\Examples\CharsCrypt64::decode($encoded) . PHP_EOL;
