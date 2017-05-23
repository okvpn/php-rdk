<?php

namespace Okvpn\R\Types;

class FloatType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function convertToRValue($value)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        return (float) $value;
    }
}
