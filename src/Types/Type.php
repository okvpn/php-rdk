<?php

namespace Okvpn\R\Types;

use Okvpn\R\Exception\InvalidArgumentException;

abstract class Type
{
    const SIMPLE_ARRAY = 'simple_array';
    const MATRIX = 'matrix';
    const BOOLEAN = 'boolean';
    const DATETIME = 'datetime';
    const DATE = 'date';
    const TIME = 'time';
    const INTEGER = 'integer';
    const STRING = 'string';
    const FLOAT = 'float';
    const RAW = 'raw';

    /**
     * @var array
     */
    protected static $typesMap;

    /**
     * @var Type[]
     */
    protected static $typesObject;

    /**
     * Prevents instantiation and forces use of the factory method.
     */
    final protected function __construct()
    {
    }

    /**
     * Converts a value from its PHP representation to its R representation of this type.
     *
     * @param mixed $value The value to convert.
     * @return mixed The R representation of the value.
     */
    public function convertToRValue($value)
    {
        return $value;
    }

    /**
     * Converts a value from its R representation to its PHP representation of this type.
     *
     * @param mixed $value The value to convert.
     * @return mixed The PHP representation of the value.
     */
    public function convertToPHPValue($value)
    {
        return $value;
    }

    /**
     * @param string $name
     * @return $this
     */
    public static function getType($name)
    {
        if (self::$typesMap === null) {
            self::$typesMap = self::getDefaultTypeMap();
        }

        if (!isset(self::$typesObject[$name])) {
            if (!isset(self::$typesMap[$name])) {
                throw new InvalidArgumentException(sprintf('Unknowing R type, "%s"', $name));
            }

            self::$typesObject[$name] = new static::$typesMap[$name]();
        }

        
        return self::$typesObject[$name];
    }

    /**
     * @return string array
     */
    public static function getTypes()
    {
        if (self::$typesMap === null) {
            self::$typesMap = self::getDefaultTypeMap();
        }

        return self::$typesMap;
    }

    /**
     * @param string $name
     * @param string $class
     */
    public static function addType($name, $class)
    {
        if (isset(self::$typesMap[$name])) {
            throw new InvalidArgumentException(
                sprintf('Type "%s" already exists. Use overrideType to override existing type', $name)
            );
        }

        self::$typesMap[$name] = $class;
    }

    /**
     * @param string $name
     * @param string $class
     */
    public static function overrideType($name, $class)
    {
        self::$typesMap[$name] = $class;

        if (isset(self::$typesObject[$name])) {
            unset(self::$typesObject[$name]);
        }
    }

    /**
     * @return array
     */
    protected static function getDefaultTypeMap()
    {
        $typesMap = [
            self::SIMPLE_ARRAY => 'Okvpn\R\Types\SimpleArrayType',
            self::BOOLEAN => 'Okvpn\R\Types\BooleanType',
            self::STRING => 'Okvpn\R\Types\StringType',
            self::FLOAT => 'Okvpn\R\Types\FloatType',
            self::RAW => 'Okvpn\R\Types\RawType',
            self::MATRIX => 'Okvpn\R\Types\MatrixType'
        ];

        return $typesMap;
    }
}
