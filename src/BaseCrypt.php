<?php

namespace Coddism\BaseCrypt;

class BaseCrypt
{
    static protected $importCodes;
    static protected $inBits;

    static protected $exportCodes;
    static protected $outBits;

    static protected $specialCodes = ['#','&','%'];

    static public function encode($string) {
        if (!static::$importCodes) {
            throw new \LogicException('Class must have a $importCodes');
        }
        if (!is_string($string)) {
            throw new \Exception('It is not a string!');
        }
        if (!$string) { return ''; }

        $hStr = '';
        foreach (str_split($string) as $symbol) {
            $index = array_search($symbol, static::$importCodes);
            if ($index === false) {
                throw new \Exception("This character is not supported: $symbol");
            }
            $hStr .= str_pad(decbin($index), static::$inBits, '0', STR_PAD_LEFT);
        }

        $shift = substr_count($hStr, '1');

        if ($shift) {
            $hStr = (substr($hStr, -$shift) . substr($hStr, 0, -$shift));
        }

        $res = '';
        foreach (str_split($hStr, static::$outBits) as $subStr) {
            $subStr = str_pad($subStr, static::$outBits, '0');
            $res .= static::$exportCodes[bindec($subStr)];
        }

        if (strlen($hStr) % static::$outBits) {
            $needSpecialSymbols = ((((strlen($hStr)/static::$outBits|0)+1)*static::$outBits - strlen($hStr)) / static::$inBits)|0;
            if ($needSpecialSymbols >= 1) {
                for ($i = 0; $i < $needSpecialSymbols; $i++) {
                    $pos = rand(0, strlen($res));
                    $res = substr_replace($res, self::$specialCodes[array_rand(self::$specialCodes)], $pos, 0);
                }
            }
        }

        return $res;
    }

    static public function decode($string) {
        if (!static::$importCodes) {
            throw new \LogicException('Class must have a $importCodes');
        }
        if (!is_string($string)) {
            throw new \Exception('It is not a string!');
        }
        if (!$string) { return ''; }

        $hStr = '';
        $skipBlocks = 0;
        foreach (str_split($string) as $symbol) {
            $index = array_search($symbol, static::$exportCodes);
            if (array_search($symbol, static::$specialCodes) !== false) {
                $skipBlocks++;
                continue;
            }
            if ($index === false) {
                throw new \Exception("This character is not supported: $symbol");
            }
            $hStr .= str_pad(decbin($index), static::$outBits, '0', STR_PAD_LEFT);
        }

        if ($zerosCount = (strlen($hStr) % static::$inBits)) {
            $hStr = substr($hStr, 0, -$zerosCount);
        }

        if ($skipBlocks) {
            $hStr = substr($hStr, 0, - $skipBlocks*static::$inBits);
        }

        $shift = substr_count($hStr, '1');
        if ($shift) {
            $hStr = (substr($hStr, $shift) . substr($hStr, 0, $shift));
        }

        $res = '';
        foreach (str_split($hStr, static::$inBits) as $subStr) {
            $res .= static::$importCodes[bindec($subStr)];
        }

        return $res;
    }
}
