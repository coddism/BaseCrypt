<?php
/**
 * Created by PhpStorm.
 * User: isergium
 * Date: 06.07.18
 * Time: 17:06
 */

namespace Coddism\Crypt;

class CharsCrypt64 extends \Coddism\BaseCrypt64
{
    static protected $importCodes = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y', 'Z', ' '
    ];
    static protected $inBits = 5;
}