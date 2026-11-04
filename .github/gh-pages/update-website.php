<?php

declare(strict_types=1);

namespace Patchwork\GhPages;

/**
 * @internal
 */
new class 
{
    public const string PATH = __DIR__ . '/../../docs/index.html';
    public const string SEARCH = '/<p>.*?<\/p>/';
    public const string REPLACE = '<p>%s</p>';

    public readonly string $tag;

    public function __construct()
    {
        $this->tag = getenv('TAG') |> self::prevent(false);
    }

    public function writeUpdates(): void
    {
        $oldContents = file_get_contents(self::PATH) |> self::prevent(false);
        $replacement = sprintf(self::REPLACE, $this->tag);
        $newContents = preg_replace(self::SEARCH, $replacement, $oldContents) |> self::prevent(null);
        file_put_contents(self::PATH, $newContents) |> self::prevent(false);
    }

    private static function prevent(mixed $unwantedValue): callable
    {
        return
        /**
         * @template T of mixed
         * @param T $actualValue
         * @return T
         */
        fn(mixed $actualValue): mixed => match ($actualValue) {
            $unwantedValue => throw new \InvalidArgumentException('Unwanted value received: ' . var_export($unwantedValue, true)),
            default => $actualValue,
        };
    }

}->writeUpdates();