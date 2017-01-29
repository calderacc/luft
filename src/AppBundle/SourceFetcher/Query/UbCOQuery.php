<?php

namespace AppBundle\SourceFetcher\Query;

class UbCOQuery extends AbstractQuery
{
    public function __construct(\DateTime $datetime)
    {
        $this->pollutant = ['CO'];
        $this->scope = ['8SMW'];
        $this->group = ['station'];

        $to = $datetime->format('U');
        $from = $to - 3600;

        $this->range = [$from, $to];
    }
}