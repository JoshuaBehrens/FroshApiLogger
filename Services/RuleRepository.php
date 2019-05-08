<?php declare(strict_types=1);

namespace FroshApiLogger\Services;

use Doctrine\DBAL\Connection;
use FroshApiLogger\Interfaces\RuleInterface;
use FroshApiLogger\Interfaces\RuleRepositoryInterface;
use FroshApiLogger\Rules\BitOperatorRule;

class RuleRepository implements RuleRepositoryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function listIds(): array
    {
        $idRows = $this->connection->fetchAll('SELECT `id` FROM `frosh_api_logger_rules`');
        return $this->getIdsFromRows($idRows);
    }

    public function listActiveIds(): array
    {
        $idRows = $this->connection->fetchAll('SELECT `id` FROM `frosh_api_logger_rules` WHERE `active` = 1');
        return $this->getIdsFromRows($idRows);
    }

    public function read(int $id): RuleInterface
    {
        $sql = <<<SQL
SELECT
    `rule`.`id` `ruleId`,
    `rule`.`configuration` `ruleConfiguration`,
    `rule`.`active` `ruleActive`,
    `type`.`id` `typeId`,
    `type`.`data_type` `typeDataType`,
    `type`.`editor_type` `typeEditorType`
FROM
    `frosh_api_logger_rules` `rule`
INNER JOIN
    `frosh_api_logger_rule_types` `type`
ON
    `type`.`id` = `rule`.`type_id`
WHERE
    `rule`.`id` = ?
SQL;
        $data = $this->connection->fetchAssoc($sql, [$id]);
        $data['ruleConfiguration'] = (array) json_decode($data['ruleConfiguration']);

        return $this->createRuleFromData($data);
    }

    protected function createRuleFromData(array $data): RuleInterface
    {
        /** @var RuleInterface $result */
        $result = new $data['typeDataType'];
        $result->setId(intval($data['ruleId']));
        $result->setActive(boolval($data['ruleActive']));
        $result->setTypeId(intval($data['typeId']));
        $result->setDataType(strval($data['typeDataType']));
        $result->setEditorType(strval($data['typeEditorType']));
        $result->setConfiguration($data['ruleConfiguration']);

        if ($result instanceof BitOperatorRule) {
            $result->setOperation(strval(['operation']));
            $result->setLeftHandSide($this->read(intval($result->getConfiguration()['lhsId'])));
            $result->setRightHandSide($this->read(intval($result->getConfiguration()['rhsId'])));
        }

        return $result;
    }

    /**
     * @param array[] $rows
     * @return int[]
     */
    protected function getIdsFromRows(array $rows): array
    {
        $idCol = array_column($rows, 'id');
        return array_map('intval', $idCol);
    }
}
