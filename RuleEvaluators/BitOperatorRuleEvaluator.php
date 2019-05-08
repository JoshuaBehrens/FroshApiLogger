<?php declare(strict_types=1);

namespace FroshApiLogger\RuleEvaluators;

use Enlight_Controller_Request_RequestHttp;
use FroshApiLogger\Interfaces\RecursiveRuleEvaluatorInterface;
use FroshApiLogger\Interfaces\RuleEvaluationServiceInterface;
use FroshApiLogger\Interfaces\RuleInterface;
use FroshApiLogger\Rules\BitOperatorRule;
use FroshApiLogger\Services\RuleEvaluationService;

class BitOperatorRuleEvaluator implements RecursiveRuleEvaluatorInterface
{
    /**
     * @var RuleEvaluationService
     */
    private $ruleEvaluationService;

    public function canEvaluate(RuleInterface $rule): bool
    {
        return $rule instanceof BitOperatorRule
            && in_array($rule->getOperation(), [BitOperatorRule::OP_AND, BitOperatorRule::OP_OR])
        ;
    }

    public function evaluate(RuleInterface $rule, Enlight_Controller_Request_RequestHttp $request): bool
    {
        /** @var BitOperatorRule $rule */
        $lhs = $this->ruleEvaluationService->evaluate($rule->getLeftHandSide(), $request);
        $rhs = $this->ruleEvaluationService->evaluate($rule->getRightHandSide(), $request);

        if ($rule->getOperation() === BitOperatorRule::OP_OR) {
            return boolval($lhs | $rhs);
        }

        if ($rule->getOperation() === BitOperatorRule::OP_AND) {
            return boolval($lhs & $rhs);
        }

        return false;
    }

    public function setRuleEvaluationService(RuleEvaluationServiceInterface $ruleEvaluationService): void
    {
         $this->ruleEvaluationService = $ruleEvaluationService;
    }
}
