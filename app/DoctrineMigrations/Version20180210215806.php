<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20180210215806 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE abstract_post CHANGE title title VARCHAR(1000) DEFAULT NULL, CHANGE slug slug VARCHAR(1000) DEFAULT NULL, CHANGE text text LONGTEXT DEFAULT NULL, CHANGE published published TINYINT(1) DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE abstract_post CHANGE title title VARCHAR(1000) NOT NULL COLLATE utf8_unicode_ci, CHANGE slug slug VARCHAR(1000) NOT NULL COLLATE utf8_unicode_ci, CHANGE text text LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE published published TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
    }
}
