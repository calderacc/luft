<?php declare(strict_types=1);

namespace App\Air\AirQuality\LevelColorCollection;

use App\Air\AirQuality\LevelColors\LevelColorsInterface;
use App\Air\AirQuality\LevelColors\StandardLevelColors;
use App\Util\ClassUtil;

class LevelColorCollection implements LevelColorCollectionInterface
{
    /** @var array $levelColorsList */
    protected $levelColorsList = [];

    public function addLevelColors(LevelColorsInterface $levelColors): LevelColorCollectionInterface
    {
        $lowercaseClassName = ClassUtil::getLowercaseShortname($levelColors);
        $identifier = str_replace('levelcolors', '', $lowercaseClassName);

        $this->levelColorsList[$identifier] = $levelColors;

        return $this;
    }

    public function getLevelColorsForMeasurement(string $measurementIdentifier): LevelColorsInterface
    {
        if (!array_key_exists($measurementIdentifier, $this->levelColorsList)) {
            return new StandardLevelColors();
        }

        return $this->levelColorsList[$measurementIdentifier];
    }
}
