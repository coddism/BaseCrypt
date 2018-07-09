<?php

namespace Coddism\BaseCrypt;

class DigitCrypt64 extends BaseCrypt64
{
    static protected $importCodes = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
    ];
    static protected $inBits = 4;
}