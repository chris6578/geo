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

/**
 * MultiPoint object
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MultiPoint extends AbstractGeometry
{
    /**
     * @param Point[]|array[] $points
     * @param int             $srid
     */
    public function __construct(array $points = array(), $srid = 0)
    {
        parent::__construct($srid);

        $this->setPoints($points);
    }

    /**
     * @param Point[]|array[] $points
     *
     * @return self
     */
    public function setPoints(array $points)
    {
        return $this->setObjects($points);
    }

    /**
     * @param Point|string|string[]|int[]|float[] $point
     *
     * @return self
     */
    public function addPoint($point)
    {
        return $this->addObject($point);
    }

    /**
     * @return Point[]
     */
    public function getPoints()
    {
        return $this->getObjects();
    }

    /**
     * @param int $index
     *
     * @return Point
     */
    public function getPoint($index)
    {
        return $this->getObjectsIndex($index);
    }

    /**
     * @return array[]
     */
    public function toArray()
    {
        return $this->objectsToArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->objectsToString();
    }

    /**
     * @param Point|string|string[]|int[]|float[] $value
     *
     * @return Point
     */
    protected function getValidObject($value)
    {
        if ( ! ($value instanceof Point)) {
            $value = new Point($value);
        }

        return $value;
    }
}
