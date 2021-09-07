<?php

declare(strict_types=1);

namespace Imgproxy\ProcessingOption;

use Imgproxy\ProcessingOption;

class OptionSet
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     */
    public function __construct()
    {
        $this->options = [];
    }

    public function set(ProcessingOption $option): self
    {
        $this->options[$option->name()] = $option;
        return $this;
    }

    public function get(string $name): ?ProcessingOption {
        return $this->options[$name] ?? null;
    }

    public function toString(): string
    {
        $fn = function (ProcessingOption $o) {
            return $o->toString();
        };
        return join("/", array_map($fn, $this->options));
    }

    public function width(): ?Width
    {
        return $this->get(ProcessingOption::WIDTH);
    }

    public function height(): ?Height
    {
        return $this->get(ProcessingOption::HEIGHT);
    }
}