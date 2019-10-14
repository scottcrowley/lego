<?php

namespace App\Filters;

/**
 * Function to perform a natural sort comparison. Adapted from sourcefrog/natsort/natcompare.js.
 * https://github.com/sourcefrog/natsort/blob/master/natcompare.js
 */
class NaturalSortCompare
{
    protected function isWhitespaceChar($a)
    {
        $charCode = ord(substr($a, 0, 1));

        return ($charCode <= 32) ? true : false;
    }

    protected function isDigitChar($a)
    {
        $charCode = ord(substr($a, 0, 1));

        return ($charCode >= 48 && $charCode <= 57) ? true : false;
    }

    protected function compareRight($a, $b)
    {
        $bias = $ia = $ib = 0;

        $ca = $cb = '';

        // The longest run of digits wins.  That aside, the greatest
        // value wins, but we can't know that it will until we've scanned
        // both numbers to know that they have the same magnitude, so we
        // remember it in BIAS.
        while (true) {
            $ca = substr($a, $ia, 1);
            $cb = substr($b, $ib, 1);
            $dca = $this->isDigitChar($ca);
            $dcb = $this->isDigitChar($cb);

            if (! $dca && ! $dcb) {
                return $bias;
            } elseif (! $dca) {
                return -1;
            } elseif (! $dcb) {
                return 1;
            } elseif ($ca < $cb) {
                if ($bias == 0) {
                    $bias = -1;
                }
            } elseif ($ca > $cb) {
                if ($bias == 0) {
                    $bias = 1;
                }
            } elseif ($ca == 0 && $cb == 0) {
                return $bias;
            }

            $ia++;
            $ib++;
        }
    }

    public static function natcompare($a, $b)
    {
        $ia = $ib = 0;
        $ca = $cb = '';
        $result = '';
        $self = new self;

        while (true) {
            // only count the number of zeroes leading the last number compared
            $nza = $nzb = 0;

            $ca = substr($a, $ia, 1);
            $cb = substr($b, $ib, 1);

            // skip over leading spaces or zeros
            while ($self->isWhitespaceChar($ca) || $ca == '0') {
                if ($ca == '0') {
                    $nza++;
                } else {
                    // only count consecutive zeroes
                    $nza = 0;
                }

                $ca = substr($a, $ia++, 1);
            }

            while ($self->isWhitespaceChar($cb) || $cb == '0') {
                if ($cb == '0') {
                    $nzb++;
                } else {
                    // only count consecutive zeroes
                    $nzb = 0;
                }

                $cb = substr($b, $ib++, 1);
            }

            // process run of digits
            if ($self->isDigitChar($ca) && $self->isDigitChar($cb)) {
                // if (($result = compareRight($a.substring($ia), $b.substring($ib))) != 0) {
                if (($result = $self->compareRight(substr($a, $ia, 1), substr($b, $ib, 1))) != 0) {
                    return $result;
                }
            }

            if ($ca == '0' && $cb == '0') {
                // The strings compare the same.  Perhaps the caller
                // will want to call strcmp to break the tie.
                return $nza - $nzb;
            }

            if ($ca < $cb) {
                return -1;
            } elseif ($ca > $cb) {
                return +1;
            }

            $ia++;
            $ib++;
        }
    }
}
