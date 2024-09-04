<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240902203410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE abstract_post DROP FOREIGN KEY FK_A96E1F8CF675F31B');
        $this->addSql('ALTER TABLE translation DROP FOREIGN KEY FK_B469456F232D562B');
        $this->addSql('DROP TABLE translation');
        $this->addSql('DROP TABLE widget');
        $this->addSql('DROP TABLE admin_user');
        $this->addSql('DROP TABLE migration_versions');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP INDEX IDX_A96E1F8CF675F31B ON abstract_post');
        $this->addSql('ALTER TABLE abstract_post DROP author_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE translation (id INT AUTO_INCREMENT NOT NULL, object_id INT DEFAULT NULL, locale VARCHAR(8) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, field VARCHAR(32) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_B469456F232D562B (object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE widget (id INT AUTO_INCREMENT NOT NULL, name LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, route LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:array)\', position LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, priority INT NOT NULL, back_link LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, show_on_pages VARCHAR(255) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE admin_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, salt VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_AD8A54A9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE migration_versions (version VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, object_class VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, field VARCHAR(32) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, foreign_key VARCHAR(64) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, content LONGTEXT CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), INDEX translations_lookup_idx (locale, object_class, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE translation ADD CONSTRAINT FK_B469456F232D562B FOREIGN KEY (object_id) REFERENCES abstract_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abstract_post ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE abstract_post ADD CONSTRAINT FK_A96E1F8CF675F31B FOREIGN KEY (author_id) REFERENCES admin_user (id)');
        $this->addSql('CREATE INDEX IDX_A96E1F8CF675F31B ON abstract_post (author_id)');
    }
}
