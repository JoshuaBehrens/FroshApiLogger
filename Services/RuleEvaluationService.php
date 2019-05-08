<?php declare(strict_types=1);

namespace FroshApiLogger\Services;

use Enlight_Controller_Request_RequestHttp;
use FroshApiLogger\Interfaces\RecursiveRuleEvaluatorInterface;
use FroshApiLogger\Interfaces\RuleInterface;
use FroshApiLogger\Interfaces\RuleEvaluationServiceInterface;
use FroshApiLogger\Interfaces\RuleEvaluatorInterface;
use Traversable;

class RuleEvaluationService implements RuleEvaluationServiceInterface
{
    /**
     * @var RuleEvaluatorInterface[]
     */
    private $ruleEvaluators;

    public function __construct(Traversable $ruleEvaluators)
    {
        $this->ruleEvaluators = iterator_to_array($ruleEvaluators);

        foreach ($this->ruleEvaluators as $ruleEvaluator) {
            if ($ruleEvaluator instanceof RecursiveRuleEvaluatorInterface) {
                $ruleEvaluator->setRuleEvaluationService($this);
            }
        }
    }

    public function evaluate(RuleInterface $rule, Enlight_Controller_Request_RequestHttp $request): ?bool
    {
        foreach ($this->ruleEvaluators as $ruleEvaluator) {
            if ($ruleEvaluator->canEvaluate($rule)) {
                return $ruleEvaluator->evaluate($rule, $request);
            }
        }

        return null;
    }
}
