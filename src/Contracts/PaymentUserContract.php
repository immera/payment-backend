<?php

namespace Immera\Payment\Contracts;

interface PaymentUserContract {
    public function getName();
    public function getEmail();
    public function getId();
}
