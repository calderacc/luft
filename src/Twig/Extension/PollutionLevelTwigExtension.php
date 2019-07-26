<?php declare(strict_types=1);

namespace App\Twig\Extension;

use App\Air\AirQuality\LevelColorHandler\LevelColorHandlerInterface;
use App\Air\AirQuality\PollutionLevel\PollutionLevelInterface;
use App\Air\ViewModel\MeasurementViewModel;

class PollutionLevelTwigExtension extends \Twig_Extension
{
    /** @var LevelColorHandlerInterface $levelColorHandler */
    protected $levelColorHandler;

    public function __construct(LevelColorHandlerInterface $levelColorHandler)
    {
        $this->levelColorHandler = $levelColorHandler;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('max_pollution_level', [$this, 'maxPollutionLevel'], ['is_safe' => ['raw']]),
            new \Twig_SimpleFunction('max_pollution_color_name', [$this, 'maxPollutionColorName'], ['is_safe' => ['raw']]),
            new \Twig_SimpleFunction('pollution_color', [$this, 'pollutionColor'], ['is_safe' => ['raw']]),
            new \Twig_SimpleFunction('pollution_color_name', [$this, 'pollutionColorName'], ['is_safe' => ['raw']]),
            new \Twig_SimpleFunction('pollution_levels', [$this, 'getLevelsForMeasurement'], ['is_safe' => ['raw']]),
        ];
    }

    public function maxPollutionLevel(array $pollutionList): int
    {
        return $this->levelColorHandler->maxPollutionLevel($pollutionList);
    }

    public function maxPollutionColorName(array $pollutionList): string
    {
        return $this->levelColorHandler->maxPollutionColorName($pollutionList);
    }
    public function pollutionColor(MeasurementViewModel $measurementViewModel): string
    {
        return $this->levelColorHandler->pollutionColor($measurementViewModel);
    }

    public function pollutionColorName(MeasurementViewModel $measurementViewModel): string
    {
        return $this->levelColorHandler->pollutionColorName($measurementViewModel);
    }

    public function getLevelsForMeasurement(string $pollutantIdentifier): PollutionLevelInterface
    {
        return $this->levelColorHandler->getLevelsForMeasurement($pollutantIdentifier);
    }

    public function getName(): string
    {
        return 'pollution_level_extension';
    }
}
