<?php

namespace App\Support;

/**
 * Contenedor mínimo de éxito/error (opcional) para acciones; sin reemplazar excepciones de dominio.
 */
final class Result
{
    public function __construct(
        public bool $ok,
        public mixed $value = null,
        public ?string $message = null,
    ) {
    }

    public static function success(mixed $value = null): self
    {
        return new self(true, $value, null);
    }

    public static function fail(string $message, mixed $value = null): self
    {
        return new self(false, $value, $message);
    }
}
