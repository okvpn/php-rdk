<?php

namespace Okvpn\R\Builder;

use Okvpn\R\RProcessInterface;
use Okvpn\R\Types\Type;

class ExpressionBuilder
{
    /** @var array */
    protected $select  = [];

    /** @var array */
    protected $set = [];

    /** @var array */
    protected $parameters = [];

    /**
     * @var RProcessInterface
     */
    protected $rProcess;

    /**
     * @param RProcessInterface $rProcess
     */
    public function __construct(RProcessInterface $rProcess)
    {
        $this->rProcess = $rProcess;
    }

    /**
     * @param mixed $expr
     * @param string $type
     * @return $this
     */
    public function select($expr, $type = Type::RAW)
    {
        $this->select[] = [
            'expr' => $expr,
            'type' => $type
        ];

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return $this
     */
    public function set($key, $value, $type = Type::RAW)
    {
        $name = uniqid($key, true);
        $this->set[] = sprintf('%s = :%s', $key, $name);
        $this->setParameter($name, $value, $type);

        return $this;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $payload = implode(PHP_EOL, $this->set);
        if (!$payload) {
            return $this;
        }

        foreach ($this->parameters as $name => $parameter) {
            $type = Type::getType($parameter['type']);
            $value = $type->convertToRValue($parameter['value']);
            $payload = preg_replace(sprintf('/\:%s/', $name), $value, $payload);
        }

        $this->rProcess->write($payload);
        $this->set = [];

        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param string $type
     * @return $this
     */
    public function setParameter($name, $value, $type = Type::RAW)
    {
        $this->parameters[$name] = [
            'value' => $value,
            'type' => $type
        ];

        return $this;
    }

    public function getResult()
    {
        $output = [];
        foreach ($this->select as $item) {
            $payload = $item['expr'];
            foreach ($this->parameters as $name => $parameter) {
                $type = Type::getType($parameter['type']);
                $value = $type->convertToRValue($parameter['value']);
                $payload = preg_replace(sprintf('/\:%s/', $name), $value, $payload);
            }

            $rOutput = $this->rProcess->write($payload);
            $output[] = $rOutput->getLastOutput($item['type']);
        }

        $this->parameters = [];
        $this->set = [];
        $this->select = [];

        return $output;
    }

    /**
     * @return mixed
     */
    public function getSingleResult()
    {
        $result = $this->getResult();
        return reset($result);
    }
}
