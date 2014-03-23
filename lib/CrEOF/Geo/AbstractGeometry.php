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
 * Abstract geometry object
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
abstract class AbstractGeometry implements GeometryInterface
{
    /**
     * @var int
     */
    protected $srid;

    /**
     * @return string
     */
    abstract public function __toString();

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * @return null|int
     */
    public function getSrid()
    {
        return $this->srid;
    }

    /**
     * @param mixed $srid
     *
     * @return self
     */
    public function setSrid($srid)
    {
        if ($srid !== null) {
            $this->srid = (int) $srid;
        }

        return $this;
    }

    /**
     * @param array $array
     * @param int   $index
     *
     * @return mixed
     */
    protected function getIndex(array $array, $index)
    {
        switch ($index) {
            case -1:
                return $array[count($array) - 1];
            default:
                return $array[$index];
        }
    }

    /**
     * @param AbstractGeometry[] $objects
     *
     * @return array
     */
    protected function objectsToArray(array $objects)
    {
        $array = array();

        foreach ($objects as $object) {
            $array[] = $object->toArray();
        }

        return $array;
    }

    /**
     * @param array  $objects
     * @param string $format
     *
     * @return string
     */
    protected function objectsToString(array $objects, $format = '%s')
    {
        $strings = array();

        foreach ($objects as $object) {
            $strings[] = sprintf($format, $object);
        }

        return implode(',', $strings);
    }
}
