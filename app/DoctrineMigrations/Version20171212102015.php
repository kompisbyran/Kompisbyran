<?php

namespace Application\Migrations;

use AppBundle\Enum\MeetingTypes;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171212102015 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `connection` ADD learner_meeting_status VARCHAR(255) NOT NULL, ADD fluent_speaker_meeting_status VARCHAR(255) NOT NULL, ADD learner_meeting_status_emails_count INT NOT NULL, ADD fluent_speaker_meeting_status_emails_count INT NOT NULL');
        $this->addSql('ALTER TABLE fos_user ADD uuid VARCHAR(255) NOT NULL');

        $this->addSql(
            'UPDATE `connection` SET learner_meeting_status = ?, fluent_speaker_meeting_status = ?',
            [
                MeetingTypes::UNKNOWN,
                MeetingTypes::UNKNOWN,
            ]
        );

        $users = $this->connection->fetchAll('SELECT * FROM fos_user');
        foreach ($users as $user) {
            $this->addSql(
                'UPDATE fos_user SET uuid = ? WHERE id = ?',
                [
                    Uuid::uuid4(),
                    $user['id']
                ]
            );
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `connection` DROP learner_meeting_status, DROP fluent_speaker_meeting_status, DROP learner_meeting_status_emails_count, DROP fluent_speaker_meeting_status_emails_count');
        $this->addSql('ALTER TABLE fos_user DROP uuid');
    }
}
