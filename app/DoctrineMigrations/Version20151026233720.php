<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20151026233720 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE article_translation (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX article_translation_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_translation ADD object_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE article_translation ADD CONSTRAINT FK_2EEA2F08232D562B FOREIGN KEY (object_id) REFERENCES abstract_post (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2EEA2F08232D562B ON article_translation (object_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE article_translation');
        $this->addSql('ALTER TABLE article_translation DROP FOREIGN KEY FK_2EEA2F08232D562B');
        $this->addSql('DROP INDEX IDX_2EEA2F08232D562B ON article_translation');
        $this->addSql('ALTER TABLE article_translation DROP object_id');
    }
}
