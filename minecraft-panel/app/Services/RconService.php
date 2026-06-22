<?php

namespace App\Services;

class RconService
{
    private string $host;
    private int $port;
    private string $password;
    private $socket = null;

    public function __construct()
    {
        $this->host     = config('minecraft.rcon_host', '172.17.0.1');
        $this->port     = config('minecraft.rcon_port', 25575);
        $this->password = config('minecraft.rcon_password', '');
    }

    public function send(string $command): string
    {
        try {
            $this->connect();
            $this->authenticate();
            $response = $this->sendPacket(2, $command);
            $this->disconnect();

            return $response ?: 'Command sent (no response).';
        } catch (\Exception $e) {
            return 'RCON Error: ' . $e->getMessage();
        }
    }

    private function connect(): void
    {
        $this->socket = fsockopen(
            $this->host,
            $this->port,
            $errno,
            $errstr,
            5
        );

        if (!$this->socket) {
            throw new \Exception("Cannot connect to RCON: {$errstr} ({$errno})");
        }
    }

    private function authenticate(): void
    {
        $response = $this->sendPacket(3, $this->password);

        if ($response === null) {
            throw new \Exception('RCON authentication failed.');
        }
    }

    private function sendPacket(int $type, string $payload): ?string
    {
        $id      = rand(1, 999);
        $data    = pack('VV', $id, $type) . $payload . "\x00\x00";
        $packet  = pack('V', strlen($data)) . $data;

        fwrite($this->socket, $packet);

        $lenData = fread($this->socket, 4);

        if (strlen($lenData) < 4) {
            return null;
        }

        $len      = unpack('V', $lenData)[1];
        $response = fread($this->socket, $len);

        return substr($response, 8, -2);
    }

    private function disconnect(): void
    {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
        }
    }
}
