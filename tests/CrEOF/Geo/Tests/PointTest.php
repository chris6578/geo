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

use CrEOF\Geo\Configuration;
use CrEOF\Geo\Point;

/**
 * Point tests
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 *
 * @backupStaticAttributes enabled
 */
class PointTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testEmptyPoint
     *
     * Test point constructor called with no arguments
     */
    public function testEmptyPoint()
    {
        $point = new Point();

        $this->assertEquals(array(0, 0), $point->toArray());
        $this->assertEquals('0 0', (string) $point);
    }

    /**
     * testPointSet
     *
     * Test constructor/set with supported valid point data
     *
     * @param string $value
     * @param array  $expected
     *
     * @dataProvider pointDataSource
     */
    public function testPointSet($value, array $expected)
    {
        $point = new Point($value);

        $this->assertEquals($expected, $point->toArray());
        $this->assertEquals($expected[0], $point->getX());
        $this->assertEquals($expected[0], $point->getLongitude());
        $this->assertEquals($expected[1], $point->getY());
        $this->assertEquals($expected[1], $point->getLatitude());
    }

    /**
     * testPointXY
     *
     * Test setX/Y with supported valid point data
     *
     * @param string $value
     * @param array  $expected
     *
     * @dataProvider pointDataSource
     */
    public function testPointXY($value, array $expected)
    {
        $point = new Point();

        $point->setX($value)
            ->setY($value);

        $this->assertEquals($expected, $point->toArray());
        $this->assertEquals($expected[0], $point->getX());
        $this->assertEquals($expected[0], $point->getLongitude());
        $this->assertEquals($expected[1], $point->getY());
        $this->assertEquals($expected[1], $point->getLatitude());
    }

    /**
     * testPointLonLat
     *
     * Test setLongitude/Latitude with supported valid point data
     *
     * @param string $value
     * @param array  $expected
     *
     * @dataProvider pointDataSource
     */
    public function testPointLonLat($value, array $expected)
    {
        $point = new Point();

        $point->setLongitude($value)
            ->setLatitude($value);

        $this->assertEquals($expected, $point->toArray());
        $this->assertEquals($expected[0], $point->getX());
        $this->assertEquals($expected[0], $point->getLongitude());
        $this->assertEquals($expected[1], $point->getY());
        $this->assertEquals($expected[1], $point->getLatitude());
    }

    /**
     * testToString
     *
     * Test __toString magic method
     */
    public function testToString()
    {
        $point = new Point('40° 26\' 46" N 79° 58\' 56" W');

        $this->assertEquals('40.446111111111 -79.982222222222', (string) $point);
    }

    /**
     * testLatitudeFirstGet
     *
     * Test order when set via constructor/setX/Y
     *
     * @param string $value
     * @param array  $expected
     *
     * @dataProvider pointDataSource
     */
    public function testLatitudeFirstGet($value, array $expected)
    {
        Configuration::setOrder(Configuration::ORDER_LAT_FIRST);

        $point = new Point($value);

        $this->assertEquals($expected, $point->toArray());
        $this->assertEquals($expected[0], $point->getX());
        $this->assertEquals($expected[1], $point->getLongitude());
        $this->assertEquals($expected[1], $point->getY());
        $this->assertEquals($expected[0], $point->getLatitude());
    }

    /**
     * testLatitudeFirstSet
     *
     * Test order when set via setLongitude/Latitude
     *
     * @param string $value
     * @param array  $expected
     *
     * @dataProvider pointDataSource
     */
    public function testLatitudeFirstSet($value, array $expected)
    {
        Configuration::setOrder(Configuration::ORDER_LAT_FIRST);

        $point = new Point();

        $point->setLongitude($value)
            ->setLatitude($value);

        $this->assertEquals($expected, $point->toArray());
        $this->assertEquals($expected[0], $point->getX());
        $this->assertEquals($expected[1], $point->getLongitude());
        $this->assertEquals($expected[1], $point->getY());
        $this->assertEquals($expected[0], $point->getLatitude());
    }

    /**
     * pointDataSource
     *
     * Data source providing valid point data
     *
     * @return array[]
     */
    public function pointDataSource()
    {
        return array(
            array('5,6', array(5, 6)),
            array(array(5, 6), array(5, 6)),
            array(array('5', '6'), array(5, 6)),
            array(array('5.456', '6'), array(5.456, 6)),
            array(array('40° 26\' 46" N', '79° 58\' 56" W'), array(40.446111111111, -79.982222222222)),
            array('40° 26\' 46" N 79° 58\' 56" W', array(40.446111111111, -79.982222222222)),
            array('40.4738° N, 79.553° W', array(40.4738, -79.553)),
            array('40.4738° S, 79.553° W', array(-40.4738, -79.553)),
            array('40° 26.222\' N 79° 58.52\' E', array(40.437033333333, 79.975333333333)),
            array('40°26.222\'N 79°58.52\'E', array(40.437033333333, 79.975333333333)),
            array('40°26.222\' 79°58.52\'', array(40.437033333333, 79.975333333333)),
            array('40.222° -79.5852°', array(40.222, -79.5852)),
            array('40.222°, -79.5852°', array(40.222, -79.5852)),
        );
    }
}
