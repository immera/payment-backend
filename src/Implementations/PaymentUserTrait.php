<?php
namespace Immera\Payment\Implementations;

trait PaymentUserTrait
{
    public function getName()
    {
        return $this->name;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getId()
    {
        return $this->id;
    }
}

