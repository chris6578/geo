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
 * MultiLineString object
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MultiLineString extends AbstractGeometry
{
    /**
     * @var LineString[] $lineStrings
     */
    protected $lineStrings;

    /**
     * @param array[]  $lineStrings
     * @param int|null $srid
     */
    public function __construct(array $lineStrings = array(), $srid = 0)
    {
        $this->setLineStrings($lineStrings)
            ->setSrid($srid);
    }

    /**
     * @param array[] $lineStrings
     *
     * @return self
     */
    public function setLineStrings(array $lineStrings)
    {
        $this->lineStrings = array();

        foreach ($lineStrings as $lineString) {
            $this->addLineString($lineString);
        }

        return $this;
    }

    /**
     * @param LineString|array[] $lineString
     *
     * @return self
     * @throws \Exception
     */
    public function addLineString($lineString)
    {
        if ( ! ($lineString instanceof LineString)) {
            $lineString = new LineString($lineString, $this->srid);
        }

        if ($this->srid !== $lineString->getSrid()) {
            throw new \Exception(); // TODO
        }

        $this->lineStrings[] = $lineString;

        return $this;
    }

    /**
     * @return LineString[]
     */
    public function getLineStrings()
    {
        return $this->lineStrings;
    }

    /**
     * @param int $index
     *
     * @return LineString
     */
    public function getLineString($index)
    {
        return $this->getObjectsIndex($this->lineStrings, $index);
    }

    /**
     * @return array[]
     */
    public function toArray()
    {
        return $this->objectsToArray($this->lineStrings);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->objectsToString($this->lineStrings, '(%s)');
    }
}
