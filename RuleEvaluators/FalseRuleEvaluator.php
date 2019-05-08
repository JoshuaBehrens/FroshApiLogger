<?php declare(strict_types=1);

namespace FroshApiLogger\RuleEvaluators;

use Enlight_Controller_Request_RequestHttp;
use FroshApiLogger\Interfaces\RuleInterface;
use FroshApiLogger\Interfaces\RuleEvaluatorInterface;
use FroshApiLogger\Rules\FalseRule;

class FalseRuleEvaluator implements RuleEvaluatorInterface
{
    public function canEvaluate(RuleInterface $rule): bool
    {
        return $rule instanceof FalseRule;
    }

    public function evaluate(RuleInterface $rule, Enlight_Controller_Request_RequestHttp $request): bool
    {
        /** @var FalseRule $rule */
        return false;
    }
}
