<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201014192549 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('SELECT 1;');
        $fieldsMap = [
            'metaKeywords' => 'meta_keywords',
            'metaDescription' => 'meta_description',
            'name' => 'name',
        ];

        $result = $this->connection
            ->fetchAll("SELECT field, foreign_key, content FROM ext_translations WHERE locale='en'")
        ;

        $mappedEnResult = [];

        foreach ($result as $row) {
            if (!isset($mappedEnResult[$row['foreign_key']])) {
                $mappedEnResult[$row['foreign_key']] = [];
            }

            $mappedEnResult[$row['foreign_key']][$row['field']] = $row['content'];
        }

        foreach ($mappedEnResult as $id => $row) {
            foreach ($row as $colName => $colValue) {
                $result = $this->connection->fetchAll("SELECT `{$fieldsMap[$colName]}` FROM category WHERE `id`='{$id}'");
                $resultRow = $result[0];
                $this->connection->executeQuery("UPDATE ext_translations SET `content`='{$resultRow[$fieldsMap[$colName]]}', `locale`='ru' WHERE `foreign_key`='{$id}' AND `field`='{$colName}' AND `locale`='en'");

                $this->connection->executeQuery("UPDATE category SET `{$fieldsMap[$colName]}`='{$colValue}' WHERE `id`='{$id}'");
            }
        }
    }

    public function down(Schema $schema): void
    {

    }
}
