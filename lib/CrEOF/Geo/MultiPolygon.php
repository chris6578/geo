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
 * MultiPolygon object
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class MultiPolygon extends AbstractGeometry
{
    /**
     * @var Polygon[] $polygons
     */
    protected $polygons;

    /**
     * @param array[]  $polygons
     * @param int|null $srid
     */
    public function __construct(array $polygons = array(), $srid = null)
    {
        $this->setPolygons($polygons);

        if (null !== $srid) {
            $this->setSrid($srid);
        }
    }

    /**
     * @param array[] $polygons
     *
     * @return self
     */
    public function setPolygons(array $polygons)
    {
        $this->polygons = array();

        foreach ($polygons as $polygon) {
            $this->addPolygon($polygon);
        }

        return $this;
    }

    /**
     * @param Polygon|array[] $polygon
     *
     * @return self
     * @throws \Exception
     */
    public function addPolygon($polygon)
    {
        if ( ! ($polygon instanceof Polygon)) {
            $polygon = new Polygon($polygon, $this->srid);
        }

        if ($this->srid !== $polygon->getSrid()) {
            throw new \Exception(); // TODO
        }

        $this->polygons[] = $polygon;

        return $this;
    }

    /**
     * @return Polygon[]
     */
    public function getPolygons()
    {
        return $this->polygons;
    }

    /**
     * @param int $index
     *
     * @return Polygon
     */
    public function getPolygon($index)
    {
        return $this->getIndex($this->polygons, $index);
    }

    /**
     * @return array[]
     */
    public function toArray()
    {
        return $this->objectsToArray($this->polygons);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->objectsToString($this->polygons, '(%s)');
    }
}
