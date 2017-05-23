<?php

namespace Okvpn\R;

class UnixPipes implements PipesInterface
{
    /** @var array */
    public $pipes;

    /** @var bool */
    protected $blocked = true;

    /**
     * {@inheritdoc}
     */
    public function getDescriptors()
    {
        return [
            ['pipe', 'r'],
            ['pipe', 'w'], // stdout
            ['pipe', 'w'], // stderr
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function writeAndRead($input)
    {
        $this->unblock();
        $r = $this->pipes;

        $read = [];
        $this->write($input);
        foreach ($r as $pipe) {
            $read[$type = array_search($pipe, $this->pipes, true)] = '';

            do {
                $data = fread($pipe, self::CHUNK_SIZE);
                $read[$type] .= $data;
            } while ($data && $data != '> ' && substr($data, -3) != "\n> " && substr($data, -3) != "\n+ ");

            if (!isset($read[$type][0])) {
                unset($read[$type]);
            }
        }

        return $read;
    }

    /**
     * {@inheritdoc}
     */
    public function areOpen()
    {
        return (bool) $this->pipes;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        foreach ($this->pipes as $pipe) {
            fclose($pipe);
        }

        $this->pipes = [];
    }

    /**
     * Unblocks streams.
     */
    protected function unblock()
    {
        if ($this->blocked) {
            stream_set_blocking($this->pipes[2], 0);
        }

        $this->blocked = false;
    }

    /**
     * Writes input to stdin.
     *
     * @param string $input
     */
    protected function write($input)
    {
        if (!isset($this->pipes[0])) {
            return;
        }

        $r = $e = [];
        $w = [$this->pipes[0]];

        // let's have a look if something changed in streams
        if (false === $n = @stream_select($r, $w, $e, 0, 0)) {
            return;
        }

        fwrite($this->pipes[0], $input);
    }
}
