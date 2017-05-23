<?php

namespace Okvpn\R\Types;

class MatrixType extends SimpleArrayType
{
    /**
     * {@inheritdoc}
     */
    public function convertToRValue($value)
    {
        return sprintf('c(%s)', implode(',', $value));
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
