<?php

namespace app\Interfaces;

interface RequestHandlerInterface
{
    public function handle(DBConnectionInterface $connection): void;
}
