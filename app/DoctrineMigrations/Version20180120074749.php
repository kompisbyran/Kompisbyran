<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180120074749 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE connection ADD learner_meeting_status_email_sent_at_dates LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD fluent_speaker_meeting_status_email_sent_at_dates LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD learner_follow_up_email2sent_at_dates LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD fluent_speaker_follow_up_email2sent_at_dates LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql(
            'UPDATE connection SET learner_meeting_status_email_sent_at_dates = ?, fluent_speaker_meeting_status_email_sent_at_dates = ?, learner_follow_up_email2sent_at_dates = ? , fluent_speaker_follow_up_email2sent_at_dates = ?',
            ['a:0:{}', 'a:0:{}', 'a:0:{}', 'a:0:{}']
        );
        $this->addSql('ALTER TABLE connection ADD learner_follow_up_email2count INT NOT NULL, ADD fluent_speaker_follow_up_email2count INT NOT NULL');
        $this->addSql('ALTER TABLE connection ADD learner_marked_as_met_created_at DATETIME DEFAULT NULL, ADD fluent_speaker_marked_as_met_created_at DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE connection DROP learner_meeting_status_email_sent_at_dates, DROP fluent_speaker_meeting_status_email_sent_at_dates, DROP learner_follow_up_email2sent_at_dates, DROP fluent_speaker_follow_up_email2sent_at_dates');
        $this->addSql('ALTER TABLE connection DROP learner_follow_up_email2count, DROP fluent_speaker_follow_up_email2count');
        $this->addSql('ALTER TABLE connection DROP learner_marked_as_met_created_at, DROP fluent_speaker_marked_as_met_created_at');
    }
}
