<?php

namespace app\entities;

use Connection;

final class ColorModel
{
    public function __construct(
        public ?Connection $connection
    )
    {
        $this->connection = new Connection();
    }

    public function getColors()
    {
        return $this->connection->query("SELECT * FROM colors");
    }
}