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

use CrEOF\Geo\MultiPolygon;
use CrEOF\Geo\Polygon;

/**
 * MultiPolygonTest object tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MultiPolygonTest extends AbstractBaseTest
{
    public function testEmptyMultiPolygon()
    {
        $multiPolygon = new MultiPolygon();

        $this->assertEmpty($multiPolygon->getPolygons());
    }

    /**
     * @param array[] $strings
     * @param array[] $arrays
     *
     * @dataProvider multiPolygonData
     */
    public function testMultiPolygonFromStrings(array $strings, array $arrays)
    {
        $multiPolygon = new MultiPolygon($strings);

        $this->assertEquals($arrays, $multiPolygon->toArray());
    }

    /**
     * @param array[]   $strings
     * @param array[]   $arrays
     * @param array[]   $points
     * @param array[]   $rings
     * @param Polygon[] $polygons
     *
     * @dataProvider multiPolygonData
     */
    public function testMultiPolygonFromArrays($strings, $arrays, $points, $rings, $polygons)
    {
        $multiPolygon = new MultiPolygon($arrays);

        $this->assertEquals($polygons, $multiPolygon->getPolygons());
    }

    /**
     * @param array[]   $strings
     * @param array[]   $arrays
     * @param array[]   $points
     *
     * @dataProvider multiPolygonData
     */
    public function testMultiPolygonFromPoints($strings, $arrays, $points)
    {
        $multiPolygon = new MultiPolygon($points);

        $this->assertEquals($arrays, $multiPolygon->toArray());
    }

    /**
     * @param array[]   $strings
     * @param array[]   $arrays
     * @param array[]   $points
     * @param array[]   $rings
     * @param Polygon[] $polygons
     *
     * @dataProvider multiPolygonData
     */
    public function testMultiPolygonFromRings($strings, $arrays, $points, $rings, $polygons)
    {
        $multiPolygon = new MultiPolygon($rings);

        $this->assertEquals($polygons, $multiPolygon->getPolygons());
    }

    /**
     * @param array[]   $strings
     * @param array[]   $arrays
     * @param array[]   $points
     * @param array[]   $rings
     * @param Polygon[] $polygons
     *
     * @dataProvider multiPolygonData
     */
    public function testMultiPolygonFromPolygons($strings, $arrays, $points, $rings, $polygons)
    {
        $multiPolygon = new MultiPolygon($polygons);

        $this->assertEquals($arrays, $multiPolygon->toArray());
    }

    public function testMultiPolygonFromArraysToString()
    {
        $expected     = '((0 0,10 0,10 10,0 10,0 0),(0 0,10 0,10 10,0 10,0 0)),((0 0,10 0,10 10,0 10,0 0))';
        $polygons     = array(
            array(
                array(
                    array(0, 0),
                    array(10, 0),
                    array(10, 10),
                    array(0, 10),
                    array(0, 0)
                ),
                array(
                    array(0, 0),
                    array(10, 0),
                    array(10, 10),
                    array(0, 10),
                    array(0, 0)
                )
            ),
            array(
                array(
                    array(0, 0),
                    array(10, 0),
                    array(10, 10),
                    array(0, 10),
                    array(0, 0)
                )
            )
        );
        $multiPolygon = new MultiPolygon($polygons);
        $result       = (string) $multiPolygon;

        $this->assertEquals($expected, $result);
    }

    /**
     * @return array[]
     */
    public function multiPolygonData()
    {
        $multiPolygonStrings = array(
            array(
                array(
                    array('0, 0', '10, 0', '10, 10', '0, 10', '0, 0')
                ),
                array(
                    array('5, 5', '7, 5', '7, 7', '5, 7', '5, 5')
                )
            ),
            array(
                array(
                    array('5, 5', '7, 5', '7, 7', '5, 7', '5, 5')
                ),
                array(
                    array('0, 0', '10, 0', '10, 10', '0, 10', '0, 0'),
                    array('5, 5', '7, 5', '7, 7', '5, 7', '5, 5')
                )
            )
        );

        $sets = array();

        foreach ($multiPolygonStrings as $multiPolygon) {
            $arrays   = $this->stringsToArrays($multiPolygon);
            $points   = $this->arraysToPoints($arrays);
            $rings    = $this->pointsToLineStrings($points);
            $polygons = $this->ringsToPolygons($rings);

            $sets[] = array($multiPolygon, $arrays, $points, $rings, $polygons);
        }

        return $sets;
    }
}
