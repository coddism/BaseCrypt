<?php
/**
 * Created by PhpStorm.
 * User: isergium
 * Date: 06.07.18
 * Time: 17:06
 */

namespace Coddism\Crypt;

class DigitCrypt64 extends \Coddism\BaseCrypt64
{
    static protected $importCodes = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
    ];
    static protected $inBits = 4;
}