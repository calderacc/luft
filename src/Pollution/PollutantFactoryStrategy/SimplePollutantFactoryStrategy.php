<?php declare(strict_types=1);

namespace App\Pollution\PollutantFactoryStrategy;

use App\Entity\Data;
use App\Pollution\DataList\DataListInterface;

class SimplePollutantFactoryStrategy implements PollutantFactoryStrategyInterface
{
    public function getMissingPollutants(DataListInterface $dataList): array
    {
        $list = $dataList->getList();
        $missingList = [];

        array_walk($list, function(array $list, int $key) use (&$missingList) {
            if (null === $list || 0 === count($list)) { // || 1 >= count($data)) {
                array_push($missingList, $key);
            }
        });

        return $missingList;
    }

    public function accepts(DataListInterface $dataList, Data $data = null): bool
    {
        if (!$data) {
            return false;
        }

        return $data && $dataList->countPollutant($data->getPollutant()) <= 1;
    }

    public function addDataToList(DataListInterface $dataList, Data $data = null): bool
    {
        if (!$data) {
            return false;
        }

        $dataList->addData($data);

        return true;
    }
}
