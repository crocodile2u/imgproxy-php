<?php

declare(strict_types=1);

namespace Imgproxy;

class WatermarkPosition
{
    public const NORTH = Gravity::NORTH,
        SOUTH = Gravity::SOUTH,
        EAST = Gravity::EAST,
        WEST = Gravity::WEST,
        NORTH_EAST = Gravity::NORTH_EAST,
        NORTH_WEST = Gravity::NORTH_WEST,
        SOUTH_EAST = Gravity::SOUTH_EAST,
        SOUTH_WEST = Gravity::SOUTH_WEST,
        CENTER = Gravity::CENTER,
        REPLICATE = "re";
}