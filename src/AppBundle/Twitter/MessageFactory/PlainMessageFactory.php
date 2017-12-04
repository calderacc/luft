<?php

namespace AppBundle\Twitter\MessageFactory;

use AppBundle\Pollution\Box\Box;

class PlainMessageFactory extends AbstractMessageFactory
{
    public function compose(): MessageFactoryInterface
    {
        /** @var Box $box */
        foreach ($this->boxList as $box) {
            $this->message .= sprintf("%s: %.0f %s \n", $box->getPollutant()->getName(), $box->getData()->getValue(), $box->getPollutant()->getUnitPlain());
        }

        return $this;
    }
}