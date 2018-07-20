<?php

namespace Coddism\BaseCrypt;

class BaseCrypt64 extends BaseCrypt {
    static protected $exportCodes =
        'abcdefghijklmnopqrstuvwxyz'.
        'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.
        '0123456789!-';
}