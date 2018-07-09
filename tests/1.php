<?php

include "../src/BaseCrypt.php";
include "../src/BaseCrypt64.php";
include "../src/Examples/DigitCrypt64.php";
include "../src/Examples/CharsCrypt64.php";


echo ($decoded = \Coddism\Crypt\DigitCrypt64::encode("2138545")) . PHP_EOL;
echo \Coddism\Crypt\DigitCrypt64::decode($decoded) . PHP_EOL;

echo ($decoded = \Coddism\Crypt\CharsCrypt64::encode("DETHKLOK")) . PHP_EOL;
echo \Coddism\Crypt\CharsCrypt64::decode($decoded) . PHP_EOL;
