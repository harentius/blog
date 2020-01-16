<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200105204523 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('UPDATE translation SET `object_id`=`foreign_key`');
        $this->addSql('DROP INDEX article_translation_idx ON translation');
        $this->addSql('ALTER TABLE translation DROP foreign_key');
        $this->addSql('ALTER TABLE translation DROP object_class');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE translation ADD foreign_key VARCHAR(64) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('CREATE INDEX article_translation_idx ON translation (locale, field, foreign_key)');
        $this->addSql('ALTER TABLE translation ADD object_class VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
