<?php

namespace AppBundle\Pollution\PollutionLevel;

use AppBundle\Entity\Data;

class PollutionLevel
{
    const LEVEL_ACCEPTABLE = 1;
    const LEVEL_WARNING = 2;
    const LEVEL_DANGER = 3;
    const LEVEL_DEATH = 4;

    protected $levels = [];

    public function __construct(int $acceptable, int $warning, int $danger, int $death)
    {
        $this->levels = [
            self::LEVEL_ACCEPTABLE => $acceptable,
            self::LEVEL_WARNING => $warning,
            self::LEVEL_DANGER => $danger,
            self::LEVEL_DEATH => $death,
        ];
    }

    public function getLevel(Data $data): int
    {
        $current = null;

        foreach ($this->levels as $level => $value) {
            if (!$current || $value < $data->getValue()) {
                $current = $level;
            }
        }

        return $current;
    }
}
