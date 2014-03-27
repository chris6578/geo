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
 * Point object
 *
 * http://stackoverflow.com/questions/7309121/preferred-order-of-writing-latitude-longitude-tuples
 * http://docs.geotools.org/latest/userguide/library/referencing/order.html
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class Point extends AbstractGeometry
{
    const ORDER_LON_FIRST = 0;
    const ORDER_LAT_FIRST = 1;

    /**
     * @var int
     */
    private static $order = self::ORDER_LON_FIRST;

    /**
     * @var array;
     */
    private $coords;

    /**
     * @param array|string|null $value
     * @param string|int|null   $srid
     */
    public function __construct($value = null, $srid = null)
    {
        $this->coords = array(0, 0);

        if (null != $value) {
            $this->set($value);
        }

        if (null != $srid) {
            $this->setSrid($srid);
        }
    }

    /**
     * @param array|string $value
     *
     * @return self
     * @throws \Exception
     */
    public function set($value)
    {
        if (is_string($value)) {
            $value = $this->parseString($value);
        }

        if ( ! is_array($value)) {
            throw new \Exception(); // TODO
        }

        $this->setX($value)
            ->setY($value);

        return $this;
    }

    /**
     * @param string|int|float|array $value
     *
     * @return self
     */
    public function setX($value)
    {
        if (is_string($value)) {
            $value = $this->parseString($value);
        }

        if (is_array($value)) {
            $value = $value[0];
        }

        $this->coords[0] = $value;

        return $this;
    }

    /**
     * @return int|float
     */
    public function getX()
    {
        return $this->coords[0];
    }

    /**
     * @param string|int|float|array $value
     *
     * @return self
     */
    public function setY($value)
    {
        if (is_string($value)) {
            $value = $this->parseString($value);
        }

        if (is_array($value)) {
            $value = $value[1];
        }

        $this->coords[1] = $value;

        return $this;
    }

    /**
     * @return int|float
     */
    public function getY()
    {
        return $this->coords[1];
    }

    /**
     * @param string|int|float|array $latitude
     *
     * @return self
     */
    public function setLatitude($latitude)
    {
        if (static::$order === self::ORDER_LAT_FIRST) {
            return $this->setX($latitude);
        }

        return $this->setY($latitude);
    }

    /**
     * @return int|float
     */
    public function getLatitude()
    {
        if (static::$order === self::ORDER_LAT_FIRST) {
            return $this->getX();
        }

        return $this->getY();
    }

    /**
     * @param string|int|float|array $longitude
     *
     * @return self
     */
    public function setLongitude($longitude)
    {
        if (static::$order === self::ORDER_LON_FIRST) {
            return $this->setX($longitude);
        }

        return $this->setY($longitude);
    }

    /**
     * @return int|float
     */
    public function getLongitude()
    {
        if (static::$order === self::ORDER_LON_FIRST) {
            return $this->getX();
        }

        return $this->getY();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->coords;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->objectsToString($this->coords, '%s', ' ');
    }

    /**
     * @param int $order
     *
     * @throws \Exception
     */
    public static function setOrder($order)
    {
        if (self::ORDER_LAT_FIRST !== $order && self::ORDER_LON_FIRST !== $order) {
            throw new \Exception(); // TODO
        }

        self::$order = $order;
    }

    /**
     * @return int
     */
    public static function getOrder()
    {
        return self::$order;
    }

    /**
     * @param string $value
     *
     * @return float|int|array
     * @throws \Exception
     */
    private function parseString($value)
    {
        if ( ! is_string($value)) {
            throw new \Exception(); // TODO
        }

        if ( ! is_numeric($value)) {
            $parser = new Parser($value);

            return $parser->parse();
        }

        return $value + 0;
    }






//    public function __construct($x, $y = null, $srid = null)
//    {
//
//        $argv = $this->validateArguments(func_get_args());
//
//        call_user_func_array(array($this, 'construct'), $argv);
//    }
//
//    /**
//     * @return string
//     */
//    public function getType()
//    {
//        return self::POINT;
//    }
//
//    /**
//     * @param array $argv
//     *
//     * @return array
//     * @throws InvalidValueException
//     */
//    protected function validateArguments(array $argv = null)
//    {
//        $argc = count($argv);
//
//        if (1 == $argc && is_array($argv[0])) {
//            return $argv[0];
//        }
//
//        if (2 == $argc) {
//            if (is_array($argv[0]) && (is_numeric($argv[1]) || is_null($argv[1]) || is_string($argv[1]))) {
//                $argv[0][] = $argv[1];
//
//                return $argv[0];
//            }
//
//            if ((is_numeric($argv[0]) || is_string($argv[0])) && (is_numeric($argv[1]) || is_string($argv[1]))) {
//                return $argv;
//            }
//        }
//
//        if (3 == $argc) {
//            if ((is_numeric($argv[0]) || is_string($argv[0])) && (is_numeric($argv[1]) || is_string($argv[1])) && (is_numeric($argv[2]) || is_null($argv[2]) || is_string($argv[2]))) {
//                return $argv;
//            }
//        }
//
//        throw InvalidValueException::invalidParameters(get_class($this), '__construct', $argv);
//    }
//
//    /**
//     * @param int      $x
//     * @param int      $y
//     * @param null|int $srid
//     */
//    protected function construct($x, $y, $srid = null)
//    {
//        $this->setX($x)
//            ->setY($y)
//            ->setSrid($srid);
//    }
}
