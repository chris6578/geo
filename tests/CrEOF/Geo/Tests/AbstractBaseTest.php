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

namespace CrEOF\Geo\Tests;

use CrEOF\Geo\LineString;
use CrEOF\Geo\Parser;
use CrEOF\Geo\Point;
use CrEOF\Geo\Polygon;

/**
 * Abstract Geo Test with Data Sources
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
abstract class AbstractBaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array[] $rings
     *
     * @return Polygon[]
     */
    protected function ringsToPolygons(array $rings)
    {
        foreach ($rings as &$array) {
            $array = new Polygon($array);
        }

        return $rings;
    }

    /**
     * @param array[] $points
     *
     * @return LineString[]
     */
    protected function pointsToLineStrings(array $points)
    {
        foreach ($points as &$array) {
            if (is_array($array[0])) {
                $array = $this->pointsToLineStrings($array);
            } else {
                $array = new LineString($array);
            }
        }

        return $points;
    }

    /**
     * @param array[] $arrays
     *
     * @return Point[]
     */
    protected function arraysToPoints(array $arrays)
    {
        foreach ($arrays as &$array) {
            if (is_array($array[0])) {
                $array = $this->arraysToPoints($array);
            } else {
                $array = new Point($array);
            }
        }

        return $arrays;
    }

    /**
     * @param string[] $strings
     *
     * @return array[]
     */
    protected function stringsToArrays(array $strings)
    {
        foreach ($strings as &$string) {
            if (is_array($string)) {
                $string = $this->stringsToArrays($string);
            } else {
                $parser   = new Parser($string);
                $string = $parser->parse();
            }
        }

        return $strings;
    }
}
