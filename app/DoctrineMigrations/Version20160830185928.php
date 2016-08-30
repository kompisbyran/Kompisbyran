<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160830185928 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `connection` ADD learner_connection_request_created_at DATETIME NOT NULL, ADD fluent_speaker_connection_request_created_at DATETIME NOT NULL');
        $this->addSql('UPDATE connection SET learner_connection_request_created_at=SYSDATE(), fluent_speaker_connection_request_created_at=SYSDATE()');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `connection` DROP learner_connection_request_created_at, DROP fluent_speaker_connection_request_created_at');
    }
}
