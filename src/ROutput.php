<?php

namespace Okvpn\R;

use Okvpn\R\Types\Type;

class ROutput implements ROutputInterface
{
    /** @var array */
    protected $output;

    /** @var array */
    protected $errors;

    /**
     * @param array $output
     * @param array $errors
     */
    public function __construct(array $output, array $errors)
    {
        $this->output = $output;
        $this->errors = $errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllOutput()
    {
        return $this->output;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllError()
    {
        return $this->errors;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastOutput($type = null)
    {
        $value = end($this->output);
        if ($type !== null) {
            $value = Type::getType($type)->convertToPHPValue($value);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastError()
    {
        return end($this->errors);
    }
}