<?php

namespace Okvpn\R\Types;

class SimpleArrayType extends Type
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
        $result = [];
        foreach (explode("\n", $value) as $row) {
            // Cut off [?] if needed
            if (strpos($row, ']') !== false) {
                $numbersAsStr = substr($row, strpos($row, ']') + 1);
            } else {
                $numbersAsStr = $row;
            }
            foreach (explode(' ', $numbersAsStr) as $potentialNumber) {
                if (is_numeric($potentialNumber)) {
                    array_push($result, 0 + $potentialNumber);
                } elseif ($potentialNumber == 'NaN') {
                    array_push($result, null);
                }
            }
        }

        return $result;
    }
}
