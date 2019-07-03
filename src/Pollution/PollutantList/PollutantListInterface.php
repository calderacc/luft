<?php declare(strict_types=1);

namespace App\Pollution\PollutantList;

use App\Pollution\Pollutant\PollutantInterface;

/**
 * @deprecated
 */
interface PollutantListInterface
{
    public function addPollutant(PollutantInterface $pollutant): PollutantListInterface;
    public function getPollutants(): array;
    public function getPollutantsWithIds(): array;
}
