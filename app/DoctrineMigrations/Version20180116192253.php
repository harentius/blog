<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20180116192253 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE article_redaction_tag DROP FOREIGN KEY FK_DF02DFFFE25CD47E');
        $this->addSql('DROP TABLE abstract_post_redaction');
        $this->addSql('DROP TABLE article_redaction_tag');
        $this->addSql('ALTER TABLE abstract_post CHANGE is_published published TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('CREATE TABLE abstract_post_redaction (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, article_id INT DEFAULT NULL, page_id INT DEFAULT NULL, author_id INT DEFAULT NULL, type INT NOT NULL, title VARCHAR(1000) DEFAULT NULL COLLATE utf8_unicode_ci, slug VARCHAR(1000) DEFAULT NULL COLLATE utf8_unicode_ci, text LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, is_published TINYINT(1) DEFAULT NULL, published_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, meta_keywords VARCHAR(1000) DEFAULT NULL COLLATE utf8_unicode_ci, show_in_main_menu TINYINT(1) DEFAULT NULL, _order INT DEFAULT NULL, INDEX IDX_FC2EF5E37294869C (article_id), INDEX IDX_FC2EF5E312469DE2 (category_id), INDEX IDX_FC2EF5E3F675F31B (author_id), INDEX IDX_FC2EF5E3C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_redaction_tag (article_redaction_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_DF02DFFFE25CD47E (article_redaction_id), INDEX IDX_DF02DFFFBAD26311 (tag_id), PRIMARY KEY(article_redaction_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abstract_post_redaction ADD CONSTRAINT FK_FC2EF5E312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE abstract_post_redaction ADD CONSTRAINT FK_FC2EF5E37294869C FOREIGN KEY (article_id) REFERENCES abstract_post (id)');
        $this->addSql('ALTER TABLE abstract_post_redaction ADD CONSTRAINT FK_FC2EF5E3C4663E4 FOREIGN KEY (page_id) REFERENCES abstract_post (id)');
        $this->addSql('ALTER TABLE abstract_post_redaction ADD CONSTRAINT FK_FC2EF5E3F675F31B FOREIGN KEY (author_id) REFERENCES admin_user (id)');
        $this->addSql('ALTER TABLE article_redaction_tag ADD CONSTRAINT FK_DF02DFFFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_redaction_tag ADD CONSTRAINT FK_DF02DFFFE25CD47E FOREIGN KEY (article_redaction_id) REFERENCES abstract_post_redaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE abstract_post CHANGE published is_published TINYINT(1) DEFAULT NULL');
    }
}
