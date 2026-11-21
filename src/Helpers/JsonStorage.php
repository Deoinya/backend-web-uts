<?php

namespace App\Helpers;

class JsonStorage
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->ensureFileExists();
    }

    private function ensureFileExists(): void
    {
        $directory = dirname($this->filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    public function readAll(): array
    {
        $content = file_get_contents($this->filePath);
        if ($content === false || trim($content) === '') {
            return [];
        }

        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function writeAll(array $data): bool
    {
        $json = json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return file_put_contents($this->filePath, $json, LOCK_EX) !== false;
    }

    public function append(array $item): bool
    {
        $data = $this->readAll();
        $data[] = $item;
        return $this->writeAll($data);
    }
}