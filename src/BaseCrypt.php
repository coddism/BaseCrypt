<?php

namespace Coddism\BaseCrypt;

class BaseCrypt
{
    static protected $importCodes;
    static protected $exportCodes;
    static protected $specialCodes = '#&%';
    static protected $trashCodes = '';

    static private final function checkErrors() {
        if (!static::$importCodes) {
            throw new \LogicException('Class must have a $importCodes');
        }
        if (!static::$exportCodes) {
            throw new \LogicException('Class must have a $exportCodes');
        }
    }
    static private final function randomCharFromString($string) {
        return $string[rand(0, strlen($string)-1)];
    }

    static private function inBits() {
        self::checkErrors();
        return intval(ceil(log(strlen(static::$importCodes), 2)));
    }
    static private function outBits() {
        self::checkErrors();
        return intval(ceil(log(strlen(static::$exportCodes), 2)));
    }

    static public function encode($string) {
        self::checkErrors();
        if (!is_string($string)) {
            throw new \Exception('It is not a string!');
        }
        if (!$string) { return ''; }

        $hStr = '';
        foreach (str_split($string) as $symbol) {
            $index = strpos(static::$importCodes, $symbol);
            if ($index === false) {
                throw new \Exception("This character is not supported: $symbol");
            }
            $hStr .= str_pad(decbin($index), static::inBits(), '0', STR_PAD_LEFT);
        }

        $shift = substr_count($hStr, '1');
        if ($shift) {
            $hStr = (substr($hStr, -$shift) . substr($hStr, 0, -$shift));
        }

        $res = '';
        foreach (str_split($hStr, static::outBits()) as $subStr) {
            $subStr = str_pad($subStr, static::outBits(), '0');
            $res .= static::$exportCodes[bindec($subStr)];
        }

        if (strlen($hStr) % static::outBits()) {
            $needSpecialSymbols = ((((strlen($hStr)/static::outBits()|0)+1)*static::outBits() - strlen($hStr)) / static::inBits())|0;
            if ($needSpecialSymbols >= 1) {
                for ($i = 0; $i < $needSpecialSymbols; $i++) {
                    $pos = rand(0, strlen($res));
                    $res = substr_replace($res, self::randomCharFromString(static::$specialCodes), $pos, 0);
                }
            }
        }

        if (static::$trashCodes) {
            $trashCount = round( (strlen($hStr) - $shift) / static::outBits() );
            if ($trashCount >= 1) {
                for ($i = 0; $i < $trashCount; $i++) {
                    $pos = rand(0, strlen($res));
                    $res = substr_replace($res, self::randomCharFromString(static::$trashCodes), $pos, 0);
                }
            }
        }

        return $res;
    }

    static public function decode($string) {
        self::checkErrors();
        if (!is_string($string)) {
            throw new \Exception('It is not a string!');
        }
        if (!$string) { return ''; }

        $hStr = '';
        $skipBlocks = 0;
        foreach (str_split($string) as $symbol) {
            if (strpos(static::$specialCodes, $symbol) !== false) {
                $skipBlocks++;
                continue;
            }
            if (strpos(static::$trashCodes, $symbol) !== false) {
                continue;
            }
            $index = strpos(static::$exportCodes, $symbol);
            if ($index === false) {
                throw new \Exception("This character is not supported: $symbol");
            }
            $hStr .= str_pad(decbin($index), static::outBits(), '0', STR_PAD_LEFT);
        }

        if ($zerosCount = (strlen($hStr) % static::inBits())) {
            $hStr = substr($hStr, 0, -$zerosCount);
        }

        if ($skipBlocks) {
            $hStr = substr($hStr, 0, - $skipBlocks*static::inBits());
        }

        $shift = substr_count($hStr, '1');
        if ($shift) {
            $hStr = substr($hStr, $shift) . substr($hStr, 0, $shift);
        }

        $res = '';
        foreach (str_split($hStr, static::inBits()) as $subStr) {
            $res .= static::$importCodes[bindec($subStr)];
        }

        return $res;
    }
}
