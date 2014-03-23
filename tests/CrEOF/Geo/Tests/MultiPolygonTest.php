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
use CrEOF\Geo\Point;
use CrEOF\Geo\Polygon;
use CrEOF\Geo\LineString;

/**
 * MultiPolygonTest object tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MultiPolygonTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyMultiPolygon()
    {
        $multiPolygon = new MultiPolygon();

        $this->assertEmpty($multiPolygon->getPolygons());
    }

    public function testMultiPolygonFromObjectsToArray()
    {
        $expected = array(
            array(
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
        $polygons = array(
            new Polygon(
                array(
                    new LineString(
                        array(
                            new Point(array(0, 0)),
                            new Point(array(10, 0)),
                            new Point(array(10, 10)),
                            new Point(array(0, 10)),
                            new Point(array(0, 0))
                        )
                    )
                )
            ),
            new Polygon(
                array(
                    new LineString(
                        array(
                            new Point(array(0, 0)),
                            new Point(array(10, 0)),
                            new Point(array(10, 10)),
                            new Point(array(0, 10)),
                            new Point(array(0, 0))
                        )
                    )
                )
            )
        );

        $multiPolygon = new MultiPolygon($polygons);

        $this->assertEquals($expected, $multiPolygon->toArray());
    }

    public function testMultiPolygonFromArraysGetObjects()
    {
        $expected = array(
            new Polygon(
                array(
                    new LineString(
                        array(
                            new Point(array(0, 0)),
                            new Point(array(10, 0)),
                            new Point(array(10, 10)),
                            new Point(array(0, 10)),
                            new Point(array(0, 0))
                        )
                    )
                )
            ),
            new Polygon(
                array(
                    new LineString(
                        array(
                            new Point(array(0, 0)),
                            new Point(array(10, 0)),
                            new Point(array(10, 10)),
                            new Point(array(0, 10)),
                            new Point(array(0, 0))
                        )
                    )
                )
            )
        );
        $polygons = array(
            array(
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

        $this->assertEquals($expected, $multiPolygon->getPolygons());
    }

    public function testMultiPolygonFromObjectsGetSinglePolygon()
    {
        $polygon1     = new Polygon(
            array(
                new LineString(
                    array(
                        new Point(array(0, 0)),
                        new Point(array(10, 0)),
                        new Point(array(10, 10)),
                        new Point(array(0, 10)),
                        new Point(array(0, 0))
                    )
                )
            )
        );
        $polygon2     = new Polygon(
            array(
                new LineString(
                    array(
                        new Point(array(5, 5)),
                        new Point(array(7, 5)),
                        new Point(array(7, 7)),
                        new Point(array(5, 7)),
                        new Point(array(5, 5))
                    )
                )
            )
        );
        $multiPolygon = new MultiPolygon(array($polygon1, $polygon2));

        $this->assertEquals($polygon1, $multiPolygon->getPolygon(0));
    }

    public function testMultiPolygonFromObjectsGetLastPolygon()
    {
        $polygon1     = new Polygon(
            array(
                new LineString(
                    array(
                        new Point(array(0, 0)),
                        new Point(array(10, 0)),
                        new Point(array(10, 10)),
                        new Point(array(0, 10)),
                        new Point(array(0, 0))
                    )
                )
            )
        );
        $polygon2     = new Polygon(
            array(
                new LineString(
                    array(
                        new Point(array(5, 5)),
                        new Point(array(7, 5)),
                        new Point(array(7, 7)),
                        new Point(array(5, 7)),
                        new Point(array(5, 5))
                    )
                )
            )
        );
        $multiPolygon = new MultiPolygon(array($polygon1, $polygon2));

        $this->assertEquals($polygon2, $multiPolygon->getPolygon(-1));
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
        $result       = (string)$multiPolygon;

        $this->assertEquals($expected, $result);
    }
}
