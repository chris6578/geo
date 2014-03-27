<?php
/**
 * Copyright (C) 2014 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\Geo;

use CrEOF\Geo\Exception\RangeException;

/**
 * Configuration object
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
final class Configuration
{
    const ORDER_LON_FIRST = 0;
    const ORDER_LAT_FIRST = 1;

    /**
     * @var int
     */
    private static $order;

    /**
     * @var int
     */
    private static $defaultSrid;

    /**
     * @param int $order
     *
     * @throws RangeException
     */
    public static function setOrder($order)
    {
        if (self::ORDER_LAT_FIRST !== $order && self::ORDER_LON_FIRST !== $order) {
            throw new RangeException(sprintf('"%s" is not a supported order', $order));
        }

        self::$order = $order;
    }

    /**
     * @return int
     */
    public static function getOrder()
    {
        if (null === self::$order) {
            return self::ORDER_LON_FIRST;
        }

        return self::$order;
    }

    /**
     * @param mixed $srid
     */
    public static function setDefaultSrid($srid)
    {
        $srid = (int) $srid;

        if ($srid < 0) {
            $srid = 0;
        }

        self::$defaultSrid = $srid;
    }

    /**
     * @return int
     */
    public static function getDefaultSrid()
    {
        if (null === self::$defaultSrid) {
            return 0;
        }

        return self::$defaultSrid;
    }
}
