<?php declare(strict_types=1);

namespace App\Pollution\UniqueStrategy;

use App\Entity\Data;
use App\Pollution\Value\Value;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class CacheUniqueStrategy implements UniqueStrategyInterface
{
    const CACHE_KEY = 'import-cache';
    const CACHE_NAMESPACE = 'luft';
    const TTL = 172800;

    /** @var array $existentDataList */
    protected $existentDataList = [];

    /** @var AdapterInterface $cacheAdapter */
    protected $cacheAdapter;

    public function __construct()
    {
        $this->cacheAdapter = new RedisAdapter(
            RedisAdapter::createConnection('redis://localhost'),
            self::CACHE_NAMESPACE,
            self::TTL
        );
    }

    public function init(array $values): UniqueStrategyInterface
    {
        $cacheItem = $this->cacheAdapter->getItem(self::CACHE_KEY);

        if ($cacheItem->isHit()) {
            $this->existentDataList = $cacheItem->get();
        } else {
            $this->existentDataList = [];
        }

        return $this;
    }

    public function isDataDuplicate(Data $data): bool
    {
        $hash = $this->hashData($data);

        return array_key_exists($hash, $this->existentDataList);
    }

    public function addData(Data $data): UniqueStrategyInterface
    {
        $hash = $this->hashData($data);

        $this->existentDataList[$hash] = $data->getDateTime()->format('U');

        return $this;
    }

    public function addDataList(array $dataList): UniqueStrategyInterface
    {
        /** @var Data $data */
        foreach ($dataList as $key => $data) {
            $this->addData($data);
        }

        return $this;
    }

    public function getDataList(): array
    {
        return $this->existentDataList;
    }

    public function save(): UniqueStrategyInterface
    {
        $cacheItem = $this->cacheAdapter->getItem(self::CACHE_KEY);

        if ($cacheItem->isHit()) {
            $existentDataList = $cacheItem->get();
        } else {
            $existentDataList = [];
        }

        $existentDataList = $this->existentDataList + $existentDataList;

        $limitTimestamp = (new \DateTime())->sub(new \DateInterval(sprintf('PT%dS', self::TTL)))->format('U');

        /** @var Data $data */
        foreach ($existentDataList as $key => $timestamp) {
            if ($timestamp < $limitTimestamp) {
                unset($existentDataList[$key]);
            }
        }

        $cacheItem->set($existentDataList);

        $this->cacheAdapter->save($cacheItem);

        return $this;
    }

    public function clear(): CacheUniqueStrategy
    {
        $this->cacheAdapter->deleteItem(self::CACHE_KEY);

        return $this;
    }

    protected function hashData(Data $data): string
    {
        return $data->getStationId() . $data->getDateTime()->format('U') . $data->getPollutant() . $data->getValue();
    }

    protected function hashValue(Value $value): string
    {
        return $value->getStation() . $value->getDateTime()->format('U') . $value->getPollutant() . $value->getValue();
    }
}