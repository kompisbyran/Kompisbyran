<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180206195709 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE connection ADD fluent_speaker_connection_request_id INT DEFAULT NULL, ADD learner_connection_request_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F77366AEF6DF6F FOREIGN KEY (fluent_speaker_connection_request_id) REFERENCES connection_request (id)');
        $this->addSql('ALTER TABLE connection ADD CONSTRAINT FK_29F77366735650EB FOREIGN KEY (learner_connection_request_id) REFERENCES connection_request (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29F77366AEF6DF6F ON connection (fluent_speaker_connection_request_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_29F77366735650EB ON connection (learner_connection_request_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE connection DROP FOREIGN KEY FK_29F77366AEF6DF6F');
        $this->addSql('ALTER TABLE connection DROP FOREIGN KEY FK_29F77366735650EB');
        $this->addSql('DROP INDEX UNIQ_29F77366AEF6DF6F ON connection');
        $this->addSql('DROP INDEX UNIQ_29F77366735650EB ON connection');
        $this->addSql('ALTER TABLE connection DROP fluent_speaker_connection_request_id, DROP learner_connection_request_id');
    }
}
