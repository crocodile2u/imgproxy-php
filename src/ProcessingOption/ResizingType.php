<?php

declare(strict_types=1);

namespace Imgproxy\ProcessingOption;

use Imgproxy\ProcessingOption;

class ResizingType extends ProcessingOption
{
    const FIT = "fit",
        FILL = "fill",
        AUTO = "auto";
    protected $name = ProcessingOption::RESIZING_TYPE;
}