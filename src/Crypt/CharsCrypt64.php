<?php

namespace Coddism\BaseCrypt;

class CharsCrypt64 extends BaseCrypt64
{
    static protected $importCodes = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y', 'Z', ' '
    ];
    static protected $inBits = 5;
}