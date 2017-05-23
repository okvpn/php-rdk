<?php

namespace Okvpn\R\Types;

class BooleanType extends Type
{
    /**
     * {@inheritdoc}
     */
    public function convertToRValue($value)
    {
        return $value === true ? 'TRUE' : 'FALSE';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value)
    {
        return $value === 'TRUE' ? true : false;
    }
}
