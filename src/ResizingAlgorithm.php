<?php

declare(strict_types=1);

namespace Imgproxy;

class ResizingAlgorithm
{
    public const NEAREST = "nearest",
        LINEAR = "linear",
        CUBIC = "cubic",
        LANCZOS2 = "lanczos2",
        LANCZOS3 = "lanczos3";
}