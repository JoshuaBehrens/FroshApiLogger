<?php declare(strict_types=1);

namespace FroshApiLogger\Migrations;

use Shopware\Components\Migrations\AbstractPluginMigration;

class Migration3 extends AbstractPluginMigration
{
    public function up($modus): void
    {
        $this->insertType('FroshApiLogger\Rules\FalseRule', 'FroshApiLogger.Editors.False');
        $this->insertType('FroshApiLogger\Rules\TrueRule', 'FroshApiLogger.Editors.True');
        $this->insertType('FroshApiLogger\Rules\BitOperatorRule', 'FroshApiLogger.Editors.BitOperator');
    }

    public function down(bool $keepUserData): void
    {
        if ($keepUserData) {
            return;
        }

        $this->deleteType('FroshApiLogger\Rules\FalseRule');
        $this->deleteType('FroshApiLogger\Rules\TrueRule');
        $this->deleteType('FroshApiLogger\Rules\BitOperatorRule');
    }

    protected function insertType(string $data, string $editor): void
    {
        $this->getConnection()
            ->prepare('INSERT INTO `frosh_api_logger_rule_types`(`data_type`, `editor_type`) VALUES (?, ?)')
            ->execute([$data, $editor])
        ;
    }

    protected function deleteType(string $data)
    {
        $query = $this->getConnection()
            ->prepare('SELECT `id` FROM `frosh_api_logger_rule_types` WHERE `data_type` = ?')
        ;
        $query->execute([$data]);
        $ruleId = $query->fetchColumn();
        $quotedRuleId = $this->getConnection()->quote($ruleId);
        $this->addSql(sprintf('DELETE FROM `frosh_api_logger_rules` WHERE type_id = %s', $quotedRuleId));
        $this->addSql(sprintf('DELETE FROM `frosh_api_logger_rule_types` WHERE id = %s', $quotedRuleId));
    }
}
