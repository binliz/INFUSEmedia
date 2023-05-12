<?php

namespace app;

use app\Interfaces\DBConnectionInterface;
use app\Interfaces\RequestHandlerInterface;

class App
{
    private RequestHandlerInterface $handler;
    private DBConnectionInterface $connection;

    /**
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $this->connection = ConnectionFactory::getDatabase($config['db']);
    }

    public function setHandler(RequestHandlerInterface $param): static
    {
        $this->handler = $param;

        return $this;
    }

    public function handle(): void
    {
        $this->getHandler()->handle($this->connection);
    }

    private function getHandler(): RequestHandlerInterface
    {
        return $this->handler;
    }
}
