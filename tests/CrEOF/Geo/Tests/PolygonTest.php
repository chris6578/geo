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
 * Polygon object tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class PolygonTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyPolygon()
    {
        $polygon = new Polygon();

        $this->assertEmpty($polygon->getRings());
    }

    /**
     * @param array $strings
     * @param array $arrays
     *
     * @dataProvider dataSourceGoodArrayArrays
     */
    public function testPolygonFromStringsToArrays(array $strings, array $arrays)
    {
        $polygon = new Polygon($strings);

        $this->assertEquals($arrays, $polygon->toArray());
    }

    /**
     * @param array $strings
     * @param array $arrays
     * @param array $points
     * @param array $rings
     *
     * @dataProvider dataSourceGoodArrayRings
     */
    public function testPolygonFromArraysToRings(array $strings, array $arrays, array $points, array $rings)
    {
        $polygon = new Polygon($arrays);

        $this->assertEquals($rings, $polygon->getRings());
    }

    /**
     * @param array $strings
     * @param array $arrays
     * @param array $points
     * @param array $rings
     *
     * @dataProvider dataSourceGoodArrayRings
     */
    public function testPolygonFromPointsToRings(array $strings, array $arrays, array $points, array $rings)
    {
        $polygon = new Polygon($points);

        $this->assertEquals($rings, $polygon->getRings());
    }

    /**
     * @param array $strings
     * @param array $arrays
     * @param array $points
     * @param array $rings
     *
     * @dataProvider dataSourceGoodArrayRings
     */
    public function testPolygonFromRingsToArrays(array $strings, array $arrays, array $points, array $rings)
    {
        $polygon = new Polygon($rings);

        $this->assertEquals($arrays, $polygon->toArray());
    }

    /**
     * Test Polygon with open ring
     *
     * @expectedException        \CrEOF\Geo\Exception\UnexpectedValueException
     * @expectedExceptionMessage Ring "0 0,10 0,10 10,0 10" in polygon is not closed
     */
    public function testOpenPolygonRing()
    {
        $rings = array(
            new LineString(array(
                new Point(array(0, 0)),
                new Point(array(10, 0)),
                new Point(array(10, 10)),
                new Point(array(0, 10))
            ))
        );

        new Polygon($rings);
    }

    public function testSolidPolygonFromArraysToString()
    {
        $expected = '(0 0,10 0,10 10,0 10,0 0),(0 0,10 0,10 10,0 10,0 0)';
        $rings    = array(
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
        );
        $polygon = new Polygon($rings);
        $result  = (string) $polygon;

        $this->assertEquals($expected, $result);
    }

    /**
     * @param array $strings
     * @param array $arrays
     *
     * @dataProvider dataSourceGoodArrayArrays
     */
    public function testPolygonSrid(array $strings, array $arrays)
    {
        $sridRings = array();

        foreach ($arrays as $ringArray) {
            $sridPoints = array();

            foreach ($ringArray as $arrayPoint) {
                $sridPoints[] = new Point($arrayPoint, 8382);
            }

            $sridRings[] = new LineString($sridPoints, 1234);
        }

        $sridPolygon = new Polygon($sridRings, 4326);

        foreach ($sridPolygon->getRings() as $ring) {
            $this->assertEquals(0, $ring->getSrid());

            foreach ($ring->getPoints() as $point) {
                $this->assertEquals(0, $point->getSrid());
            }
        }
    }

    /**
     * @return array[]
     */
    public function dataSourceGoodArrayRings()
    {
        $parameterSets = array();

        foreach ($this->dataSourceGoodArrayPoints() as $parameters) {
            list($stringSet, $arrayArraysArray, $arrayPointsArray) = $parameters;

            $ringsArray = array();

            foreach ($arrayPointsArray as $pointsArray) {
                $ringsArray[] = new LineString($pointsArray);
            }

            $parameterSets[] = array($stringSet, $arrayArraysArray, $arrayPointsArray, $ringsArray);
        }

        return $parameterSets;
    }

    /**
     * @return array[]
     */
    public function dataSourceGoodArrayPoints()
    {
        $parameterSets = array();

        foreach ($this->dataSourceGoodArrayArrays() as $parameters) {
            list($stringSet, $arrayArraysArray) = $parameters;

            $arrayPointsArray = array();

            foreach ($arrayArraysArray as $arraysArray) {
                $arrayPointsArray[] = $this->arraysToPoints($arraysArray);
            }

            $parameterSets[] = array($stringSet, $arrayArraysArray, $arrayPointsArray);
        }

        return $parameterSets;
    }

    /**
     * @return array[]
     */
    public function dataSourceGoodArrayArrays()
    {
        $parameterSets = array();

        foreach ($this->dataSourceGoodStringArrays() as $arrayStringsArray) {
            $arrayArraysArray = array();

            foreach ($arrayStringsArray as $stringsArray) {
                $arrayArraysArray[] = $this->stringsToArrays($stringsArray);
            }

            $parameterSets[] = array($arrayStringsArray, $arrayArraysArray);
        }

        return $parameterSets;
    }

    /**
     * @return array[]
     */
    public function dataSourceGoodStringArrays()
    {
        return array(
            array(
                array(
                    '0, 0',
                    '10, 0',
                    '10, 10',
                    '0, 10',
                    '0, 0'
                )
            ),
            array(
                array(
                    '5, 5',
                    '7, 5',
                    '7, 7',
                    '5, 7',
                    '5, 5'
                )
            ),
            array(
                array(
                    '-91.4517209530666,33.7154346114233',
                    '-91.4517209668933,33.715434651199',
                    '-91.4516781841348,33.7186571121113',
                    '-91.4560796605743,33.7187422933575',
                    '-91.4570029270665,33.7199217697274',
                    '-91.4635542585698,33.7162746576359',
                    '-91.4574704963375,33.707836112647',
                    '-91.4576913006051,33.7077258412125',
                    '-91.4568890072317,33.7066409208389',
                    '-91.4547490852077,33.7077176833912',
                    '-91.4523706480211,33.7044772688032',
                    '-91.451863031959,33.7047577189045',
                    '-91.4518693067104,33.7042408677742',
                    '-91.4518692932543,33.7042408680098',
                    '-91.451825702316,33.7078319242869',
                    '-91.451811471841,33.7086139582445',
                    '-91.4517209530666,33.7154346114233',
                )
            ),
            array(
                array(
                    '0, 0',
                    '10, 0',
                    '10, 10',
                    '0, 10',
                    '0, 0'
                ),
                array(
                    '5, 5',
                    '7, 5',
                    '7, 7',
                    '5, 7',
                    '5, 5'
                )
            )
        );
    }

    private function arraysToPoints(array $arrays)
    {
        $points = array();

        foreach ($arrays as $array) {
            $points[] = new Point($array);
        }

        return $points;
    }

    private function stringsToArrays(array $strings)
    {
        $arrays = array();

        foreach ($strings as $string) {
            $parser   = new Parser($string);
            $arrays[] = $parser->parse();
        }

        return $arrays;
    }
}
