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

use CrEOF\Geo\MultiPoint;
use CrEOF\Geo\Point;

/**
 * MultiPoint object tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MultiPointTest extends AbstractBaseTest
{
    public function testEmptyMultiPoint()
    {
        $multiPoint = new MultiPoint();

        $this->assertEmpty($multiPoint->getPoints());
    }

    /**
     * @param string[] $strings
     * @param array[]  $arrays
     * @param Point[]  $points
     *
     * @dataProvider multiPointData
     */
    public function testMultiPointFromObjectsToArray(array $strings, array $arrays, array $points)
    {
        $multiPoint = new MultiPoint($points);

        $this->assertCount(count($points), $multiPoint->getPoints());
        $this->assertEquals($arrays, $multiPoint->toArray());
    }

    /**
     * @param string[] $strings
     * @param array[]  $arrays
     * @param Point[]  $points
     *
     * @dataProvider multiPointData
     */
    public function testMultiPointFromArraysGetPoints(array $strings, array $arrays, array $points)
    {
        $multiPoint = new MultiPoint($arrays);
        $actual     = $multiPoint->getPoints();

        $this->assertCount(count($arrays), $actual);
        $this->assertEquals($points, $actual);
    }

    /**
     * @param string[] $strings
     * @param array[]  $arrays
     * @param Point[]  $points
     *
     * @dataProvider multiPointData
     */
    public function testMultiPointFromStringsGetPoints(array $strings, array $arrays, array $points)
    {
        $multiPoint = new MultiPoint($strings);
        $actual     = $multiPoint->getPoints();

        $this->assertCount(count($strings), $actual);
        $this->assertEquals($points, $actual);
    }

    /**
     * @param string[] $strings
     * @param array[]  $arrays
     * @param Point[]  $points
     *
     * @dataProvider multiPointData
     */
    public function testMultiPointGetSinglePoint(array $strings, array $arrays, array $points)
    {
        $multiPoint = new MultiPoint($points);

        for ($i = 0; $i < count($points); $i++) {
            $this->assertEquals($points[$i], $multiPoint->getPoint($i));
        }
    }

    /**
     * @param string[] $strings
     * @param array[]  $arrays
     * @param Point[]  $points
     *
     * @dataProvider multiPointData
     */
    public function testMultiPointGetLastPoint(array $strings, array $arrays, array $points)
    {
        $multiPoint = new MultiPoint($points);

        $this->assertEquals($points[count($points) - 1], $multiPoint->getPoint(-1));
    }

    /**
     * @param string[] $strings
     * @param array[]  $arrays
     * @param Point[]  $points
     *
     * @dataProvider multiPointData
     */
    public function testMultiPointAddPoint(array $strings, array $arrays, array $points)
    {
        $multiPoint = new MultiPoint();

        foreach ($points as $point) {
            $multiPoint->addPoint($point);
        }

        $actual = $multiPoint->getPoints();

        $this->assertCount(count($points), $actual);
        $this->assertEquals($points, $actual);
    }

    /**
     * @param string[] $strings
     * @param array[]  $arrays
     * @param Point[]  $points
     *
     * @dataProvider multiPointData
     */
    public function testMultiPointSetPoints(array $strings, array $arrays, array $points)
    {
        $junkPoints = array(
            array(100, 100),
            array(102, 102)
        );

        $multiPoint = new MultiPoint($junkPoints);

        $multiPoint->setPoints($points);

        $actual = $multiPoint->getPoints();

        $this->assertCount(count($points), $actual);
        $this->assertEquals($points, $actual);
    }

    /**
     * @param string[] $strings
     * @param array[]  $arrays
     * @param Point[]  $points
     *
     * @dataProvider multiPointData
     */
    public function testMultiPointSrid(array $strings, array $arrays, array $points)
    {
        $sridPoints = array();

        foreach ($arrays as $array) {
            $sridPoints[] = new Point($array, 1234);
        }

        $multiPoint = new MultiPoint($sridPoints, 4326);

        foreach ($multiPoint->getPoints() as $point) {
            $this->assertEquals(0, $point->getSrid());
        }
    }

    /**
     * Test MultiPoint bad parameter
     *
     * @expectedException        \CrEOF\Geo\Exception\UnexpectedValueException
     * @expectedExceptionMessage Unexpected value of type "integer"
     */
    public function testBadLineString()
    {
        new MultiPoint(array(1, 2, 3 ,4));
    }

    public function testMultiPointFromArraysToString()
    {
        $expected   = '0 0,0 5,5 0,0 0';
        $multiPoint = new MultiPoint(
            array(
                array(0, 0),
                array(0, 5),
                array(5, 0),
                array(0, 0)
            )
        );

        $this->assertEquals($expected, (string) $multiPoint);
    }

    /**
     * @return array[]
     */
    public function multiPointData()
    {
        $multiPointStrings = array(
            array('0, 0', '1, 1', '2, 2', '3, 3'),
            array('0 0', '0 5', '5 0', '0 0'),
            array('44°58\'53.9"N 93°19\'25.8"W', '41°49\'30.4"N 87°40\'49.6"W', '35°29\'20.7"N 97°33\'38.0"W')
        );

        $sets = array();

        foreach ($multiPointStrings as $multiPoint) {
            $arrays = $this->stringsToArrays($multiPoint);
            $points = $this->arraysToPoints($arrays);
            $sets[] = array($multiPoint, $arrays, $points);
        }

        return $sets;
    }
}
