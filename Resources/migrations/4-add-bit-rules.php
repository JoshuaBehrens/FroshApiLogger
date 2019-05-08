<?php declare(strict_types=1);

namespace FroshApiLogger\Migrations;

use Shopware\Components\Migrations\AbstractPluginMigration;

class Migration4 extends AbstractPluginMigration
{
    public function up($modus): void
    {
        $this->insertTrueRule();
    }

    public function down(bool $keepUserData): void
    {
        if ($keepUserData) {
            return;
        }

        $this->deleteTrueRule();
    }

    protected function insertTrueRule(): void
    {
        $query = $this->getConnection()
            ->prepare('SELECT `id` FROM `frosh_api_logger_rule_types` WHERE `data_type` = ?')
        ;
        $query->execute(['FroshApiLogger\Rules\TrueRule']);
        $ruleId = $query->fetchColumn();

        $this->getConnection()
            ->prepare('INSERT INTO `frosh_api_logger_rules`(`type_id`, `active`, `configuration`) VALUES (?, 1, \'{}\')')
            ->execute([$ruleId])
        ;
    }

    protected function deleteTrueRule()
    {
        $query = $this->getConnection()
            ->prepare('SELECT `id` FROM `frosh_api_logger_rule_types` WHERE `data_type` = ?')
        ;
        $query->execute(['FroshApiLogger\Rules\TrueRule']);
        $ruleId = $query->fetchColumn();
        $quotedRuleId = $this->getConnection()->quote($ruleId);
        $this->addSql(sprintf('DELETE FROM `frosh_api_logger_rules` WHERE type_id = %s AND `active` = 1 AND `configuration` = \'{}\'', $quotedRuleId));
    }
}
