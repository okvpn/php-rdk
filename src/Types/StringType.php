<?php

namespace Okvpn\R\Types;

class StringType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function convertToRValue($value)
    {
        return sprintf('"%s"', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        return $value;
    }
}
