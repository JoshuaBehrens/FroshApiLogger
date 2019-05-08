<?php declare(strict_types=1);

namespace FroshApiLogger\Rules;

use FroshApiLogger\Interfaces\RuleInterface;

class BitOperatorRule extends AbstractRule
{
    const OP_OR = 'or';

    const OP_AND = 'and';

    /**
     * @var RuleInterface
     */
    private $leftHandSide;

    /**
     * @var RuleInterface
     */
    private $rightHandSide;

    private $operation = '';

    public function getLeftHandSide(): RuleInterface
    {
        return $this->leftHandSide;
    }

    public function setLeftHandSide(RuleInterface $leftHandSide): self
    {
        $this->leftHandSide = $leftHandSide;
        return $this;
    }

    public function getRightHandSide(): RuleInterface
    {
        return $this->rightHandSide;
    }

    public function setRightHandSide(RuleInterface $rightHandSide): self
    {
        $this->rightHandSide = $rightHandSide;
        return $this;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function setOperation(string $operation): self
    {
        $this->operation = $operation;
        return $this;
    }
}
