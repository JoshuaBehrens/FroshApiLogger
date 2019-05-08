<?php declare(strict_types=1);

namespace FroshApiLogger\Interfaces;

interface RuleInterface
{
    public function getId(): int;

    public function setId(int $id): self;

    public function isActive(): bool;

    public function setActive(bool $active): self;

    public function getConfiguration(): array;

    public function setConfiguration(array $configuration): self;

    public function getTypeId(): int;

    public function setTypeId(int $typeId): self;

    public function getDataType(): string;

    public function setDataType(string $dataType): self;

    public function getEditorType(): string;

    public function setEditorType(string $editorType): self;
}
