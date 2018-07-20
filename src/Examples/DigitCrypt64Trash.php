<?php

namespace Coddism\BaseCrypt\Examples;

class DigitCrypt64Trash extends DigitCrypt64
{
    static protected $trashCodes = ['"','>','.',','];
}