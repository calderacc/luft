<?php

namespace AppBundle\Twitter\MessageFactory;

abstract class AbstractMessageFactory implements MessageFactoryInterface
{
    /** @var array $boxList */
    protected $boxList = [];

    /** @var string $message */
    protected $message = '';

    public function setBoxList(array $boxList = []): MessageFactoryInterface
    {
        $this->boxList = $boxList;

        return $this;
    }

    abstract public function compose(): MessageFactoryInterface;

    public function getMessage(): string
    {
        return $this->message;
    }
}