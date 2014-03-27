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
     * @var AbstractGeometry[]
     */
    protected $objects;

    /**
     * @return string
     */
    abstract public function __toString();

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * @param int $srid
     */
    public function __construct($srid = 0)
    {
        $this->setSrid($srid);
    }

    /**
     * @return int
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
        $srid = (int) $srid;

        if (0 > $srid) {
            $srid = 0;
        }

        $this->srid = $srid;

        return $this;
    }

    /**
     * @param int $index
     *
     * @return mixed
     */
    protected function getObjectsIndex($index)
    {
        switch ($index) {
            case -1:
                return $this->objects[count($this->objects) - 1];
            default:
                return $this->objects[$index];
        }
    }

    /**
     * @return array
     */
    protected function getObjects()
    {
        $objects = array();

        foreach ($this->objects as $object) {
            $objects[] = $object->setSrid(0);
        }

        return $objects;
    }

    /**
     * @param AbstractGeometry $object
     *
     * @return self
     */
    protected function addObject(AbstractGeometry $object)
    {
        $this->objects[] = $object->setSrid(0);

        return $this;
    }

    /**
     * @return array
     */
    protected function objectsToArray()
    {
        $array = array();

        foreach ($this->objects as $object) {
            $array[] = $object->toArray();
        }

        return $array;
    }

    /**
     * @param string $format
     * @param string $separator
     * @param array  $objects
     *
     * @return string
     */
    protected function objectsToString($format = '%s', $separator = ',', array $objects = null)
    {
        if (null === $objects) {
            $objects = $this->objects;
        }

        $strings = array();

        foreach ($objects as $object) {
            $strings[] = sprintf($format, $object);
        }

        return implode($separator, $strings);
    }
}
