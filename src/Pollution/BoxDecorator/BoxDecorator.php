<?php declare(strict_types=1);

namespace App\Pollution\BoxDecorator;

use App\Pollution\Box\Box;

class BoxDecorator extends AbstractBoxDecorator
{
    public function decorate(): BoxDecoratorInterface
    {
        /** @var array $boxArray */
        foreach ($this->pollutantList as $boxList) {
            /** @var Box $box */
            foreach ($boxList as $box) {
                $data = $box->getData();

            $pollutant = $this->getPollutantById($data->getPollutant());

            $box
                ->setStation($data->getStation())
                ->setPollutant($pollutant);
        }

        $this->airQualityCalculator->calculatePollutantList($this->pollutantList);

        return $this;
    }
}
