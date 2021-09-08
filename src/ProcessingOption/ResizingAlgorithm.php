<?php

declare(strict_types=1);

namespace Imgproxy\ProcessingOption;

use Imgproxy\ProcessingOption;

class ResizingAlgorithm extends ProcessingOption
{
    const NEAREST = "nearest",
        LINEAR = "linear",
        CUBIC = "cubic",
        LANCZOS2 = "lanczos2",
        LANCZOS3 = "lanczos3";
    protected $name = ProcessingOption::RESIZING_ALGORITHM;
}