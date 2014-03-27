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
     * @var AbstractGeometry[]|int[]|float[]
     */
    protected $values;

    /**
     * @return string
     */
    abstract public function __toString();

    /**
     * @return array
     */
    abstract public function toArray();

    /**
     * @param int|null $srid
     */
    public function __construct($srid = null)
    {
        if (null === $srid) {
            $srid = Configuration::getDefaultSrid();
        }

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

        if ($srid < 0) {
            $srid = 0;
        }

        $this->srid = $srid;

        return $this;
    }

    /**
     * @param array $values
     *
     * @return self
     */
    protected function setValues(array $values)
    {
        $this->values = array();

        foreach ($values as $object) {
            $this->addValue($object);
        }

        return $this;
    }

    /**
     * @param int $index
     *
     * @return AbstractGeometry
     */
    protected function getValuesIndex($index)
    {
        switch ($index) {
            case -1:
                return $this->values[count($this->values) - 1];
            default:
                return $this->values[$index];
        }
    }

    /**
     * @return array
     */
    protected function getValues()
    {
        $objects = array();

        foreach ($this->values as $value) {
            $objects[] = $value->setSrid(0);
        }

        return $objects;
    }

    /**
     * @param mixed $values
     *
     * @return self
     */
    protected function addValue($values)
    {
        $values = $this->getValidObject($values);

        $this->values[] = $values->setSrid(0);

        return $this;
    }

    /**
     * @return array
     */
    protected function valuesToArray()
    {
        $array = array();

        foreach ($this->values as $value) {
            $array[] = $value->toArray();
        }

        return $array;
    }

    /**
     * valuesToString
     *
     * @param string $format
     * @param string $separator
     *
     * @return string
     */
    protected function valuesToString($format = '%s', $separator = ',')
    {
        $strings = array();

        foreach ($this->values as $value) {
            $strings[] = sprintf($format, $value);
        }

        return implode($separator, $strings);
    }

    /**
     * getValidObject
     *
     * Create object from value if necessary and perform validation before returning an AbstractGeometry object
     *
     * @param mixed $value
     *
     * @return AbstractGeometry
     */
    protected function getValidObject($value)
    {
        return $value;
    }
}
