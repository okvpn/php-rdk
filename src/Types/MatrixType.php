<?php

namespace Okvpn\R\Types;

use Okvpn\R\Exception\InvalidValueException;

class MatrixType extends SimpleArrayType
{
    /**
     * {@inheritdoc}
     */
    public function convertToRValue($value)
    {
        if (!isset($value[0]) || !is_array($value[0])) {
            throw new InvalidValueException('Matrix type support only array type.');
        }

        $payload = array_map(function ($item) {return implode(",", $item);}, $value);

        return sprintf('matrix(c(%s), %s, %s)', implode(",", $payload), count($value[0]), count($value));
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        $elements = explode("\n", $value);
        array_shift($elements);

        $result = [];
        foreach ($elements as $element) {
            $result[] = parent::convertToPHPValue($element);
        }

        return $result;
    }
}
