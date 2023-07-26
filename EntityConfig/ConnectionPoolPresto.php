<?php
namespace App\Http\Bundle\Presto\EntityConfig;

/**
 * ConnectionPool
 *
 * @package App\Http\Bundle\Presto\EntityConfig
 */
final class ConnectionPoolPresto
{
    /**
     * @return string
     */
    public static function setPrestoAddress(): string
    {
        return env('PRESTO_HOST').':'.env('PRESTO_PORT');
    }
}

