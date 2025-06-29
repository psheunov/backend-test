<?php

namespace Raketa\BackendTestTask\Infrastructure;

interface ConnectorInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * @throws ConnectorException
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $host
     * @param int $port
     * @return bool
     */
    public function isConnected(string $host, int $port): bool;

    /**
     * @param string $host
     * @param int $port
     * @param string $password
     * @param $database
     * @return void
     */
    public function connect(string $host, int $port, string $password, $database): void;
}