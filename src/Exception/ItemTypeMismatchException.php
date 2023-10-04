<?php

declare(strict_types=1);

namespace Alan6k8\SortedLinkedList\Exception;

use Exception;
use Throwable;

class ItemTypeMismatchException extends Exception
{
    private const DEFAULT_MESSAGE = 'Mixing item types is not allowed';

    public function __construct(
        string $message = self::DEFAULT_MESSAGE,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
