<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151228141426 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE connection_comment (id INT AUTO_INCREMENT NOT NULL, connection_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, comment LONGTEXT DEFAULT NULL, INDEX IDX_EF7EB44FDD03F01 (connection_id), INDEX IDX_EF7EB44FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE connection_comment ADD CONSTRAINT FK_EF7EB44FDD03F01 FOREIGN KEY (connection_id) REFERENCES `connection` (id)');
        $this->addSql('ALTER TABLE connection_comment ADD CONSTRAINT FK_EF7EB44FA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE connection_comment');
    }
}
