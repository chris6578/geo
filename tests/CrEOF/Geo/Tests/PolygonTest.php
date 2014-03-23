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

    public function testSolidPolygonFromObjectsToArray()
    {
        $expected = array(
            array(
                array(0, 0),
                array(10, 0),
                array(10, 10),
                array(0, 10),
                array(0, 0)
            )
        );
        $rings    = array(
            new LineString(
                array(
                    new Point(array(0, 0)),
                    new Point(array(10, 0)),
                    new Point(array(10, 10)),
                    new Point(array(0, 10)),
                    new Point(array(0, 0))
                )
            )
        );

        $polygon = new Polygon($rings);

        $this->assertEquals($expected, $polygon->toArray());
    }

    public function testSolidPolygonFromArraysGetRings()
    {
        $expected = array(
            new LineString(
                array(
                    new Point(array(0, 0)),
                    new Point(array(10, 0)),
                    new Point(array(10, 10)),
                    new Point(array(0, 10)),
                    new Point(array(0, 0))
                )
            )
        );
        $rings    = array(
            array(
                array(0, 0),
                array(10, 0),
                array(10, 10),
                array(0, 10),
                array(0, 0)
            )
        );

        $polygon = new Polygon($rings);

        $this->assertEquals($expected, $polygon->getRings());
    }

    public function testRingPolygonFromObjectsGetSingleRing()
    {
        $ring1   = new LineString(
            array(
                new Point(array(0, 0)),
                new Point(array(10, 0)),
                new Point(array(10, 10)),
                new Point(array(0, 10)),
                new Point(array(0, 0))
            )
        );
        $ring2   = new LineString(
            array(
                new Point(array(5, 5)),
                new Point(array(7, 5)),
                new Point(array(7, 7)),
                new Point(array(5, 7)),
                new Point(array(5, 5))
            )
        );
        $polygon = new Polygon(array($ring1, $ring2));

        $this->assertEquals($ring1, $polygon->getRing(0));
    }

    public function testRingPolygonFromObjectsGetLastRing()
    {
        $ring1   = new LineString(
            array(
                new Point(array(0, 0)),
                new Point(array(10, 0)),
                new Point(array(10, 10)),
                new Point(array(0, 10)),
                new Point(array(0, 0))
            )
        );
        $ring2   = new LineString(
            array(
                new Point(array(5, 5)),
                new Point(array(7, 5)),
                new Point(array(7, 7)),
                new Point(array(5, 7)),
                new Point(array(5, 5))
            )
        );
        $polygon = new Polygon(array($ring1, $ring2));

        $this->assertEquals($ring2, $polygon->getRing(-1));
    }

    /**
     * Test Polygon with open ring
     *
     * @expectedException        \Exception
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
        $polygon  = new Polygon($rings);
        $result   = (string)$polygon;

        $this->assertEquals($expected, $result);
    }
}
