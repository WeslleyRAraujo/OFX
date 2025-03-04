<?php

namespace WeslleyRAraujo\OFX\Core;

class OFXException Extends \Exception
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}