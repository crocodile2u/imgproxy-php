<?php

declare(strict_types=1);

namespace Imgproxy\ProcessingOption;

use Imgproxy\ProcessingOption;

abstract class Measure extends ProcessingOption
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int|float
     */
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->format($this->name, $this->value);
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return float|int
     */
    public function value() {
        return $this->value;
    }
}