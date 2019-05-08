<?php declare(strict_types=1);

namespace FroshApiLogger\Interfaces;

interface RecursiveRuleEvaluatorInterface extends RuleEvaluatorInterface
{
    public function setRuleEvaluationService(RuleEvaluationServiceInterface $ruleEvaluationService): void;
}
