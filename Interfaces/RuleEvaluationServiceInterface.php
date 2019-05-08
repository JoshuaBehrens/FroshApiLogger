<?php declare(strict_types=1);

namespace FroshApiLogger\Interfaces;

use Enlight_Controller_Request_RequestHttp;

interface RuleEvaluationServiceInterface
{
    public function evaluate(RuleInterface $rule, Enlight_Controller_Request_RequestHttp $request): ?bool;
}
