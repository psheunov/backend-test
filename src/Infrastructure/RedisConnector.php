<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure;

use http\Exception\RuntimeException;
use Redis;
use RedisException;

readonly class RedisConnector implements ConnectorInterface
{
    const EXPIRE = 86400;

    private Redis $redis;

    public function __construct(private LoggerInterface $logger)
    {
        $this->redis = new Redis();
    }

    public function connect(string $host, int $port, string $password, $database): void
    {
        try {
            if (!$this->redis->connect($host, $port)) {
                throw new RuntimeException('Не удалось подключиться к Redis');
            }

            if ($password !== null) {
                $this->redis->auth($password);
            }

            if ($database !== 0) {
                $this->redis->select($database);
            }
        } catch (RedisException $e) {
            $this->logger->error('Ошибка подключения к Redis', [
                'exception' => $e->getMessage(),
                'host' => $host,
                'port' => $port
            ]);
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function get(string $key)
    {
        try {
            return $this->has($key) ? unserialize($this->redis->get($key)) : null;
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    /**
     * @throws ConnectorException
     */
    public function set(string $key, $value, int $expire = self::EXPIRE): void
    {
        try {
            $this->redis->setex($key, $expire, serialize($value));
        } catch (RedisException $e) {
            throw new ConnectorException('Connector error', $e->getCode(), $e);
        }
    }

    public function has(string $key): bool
    {
        try {
            return (bool)$this->redis->exists($key);
        } catch (RedisException $e) {
            $this->logger->warning('Ошибка проверки существования ключа в Redis', [
                'key' => $key,
                'exception' => $e->getMessage()
            ]);
        }
        return false;
    }

    public function isConnected(string $host, int $port): bool
    {
        try {
            return $this->redis->isConnected() && $this->redis->ping('Pong') && $this->redis->connect($host, $port);
        } catch (RedisException) {
        }
    }
}
