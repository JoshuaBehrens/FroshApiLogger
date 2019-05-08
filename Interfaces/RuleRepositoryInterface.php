<?php declare(strict_types=1);

namespace FroshApiLogger\Interfaces;

interface RuleRepositoryInterface
{
    /**
     * @return int[]
     */
    public function listIds(): array;

    /**
     * @return int[]
     */
    public function listActiveIds(): array;

    public function read(int $id): RuleInterface;
}
