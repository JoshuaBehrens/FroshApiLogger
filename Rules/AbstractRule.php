<?php declare(strict_types=1);

namespace FroshApiLogger\Rules;

use FroshApiLogger\Interfaces\RuleInterface;

abstract class AbstractRule implements RuleInterface
{
    private $id = 0;

    private $active = false;

    private $configuration = [];

    private $typeId = 0;

    private $dataType = '';

    private $editorType = '';

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): RuleInterface
    {
        $this->id = $id;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): RuleInterface
    {
        $this->active = $active;
        return $this;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): RuleInterface
    {
        $this->configuration = $configuration;
        return $this;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function setTypeId(int $typeId): RuleInterface
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function setDataType(string $dataType): RuleInterface
    {
        $this->dataType = $dataType;
        return $this;
    }

    public function getEditorType(): string
    {
        return $this->editorType;
    }

    public function setEditorType(string $editorType): RuleInterface
    {
        $this->editorType = $editorType;
        return $this;
    }
}
