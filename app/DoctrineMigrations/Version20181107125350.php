<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181107125350 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A64798BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_957A64798BAC62AF ON fos_user (city_id)');

        $users = $this->connection->fetchAll('SELECT * FROM fos_user');
        foreach ($users as $user) {
            $connectionRequests = $this->connection->fetchAll('SELECT * FROM connection_request WHERE user_id = ? AND city_id IS NOT NULL ORDER BY created_at DESC LIMIT 1', [$user['id']]);
            if ($connectionRequests) {
                $connectionRequest = current($connectionRequests);
                $this->addSql('UPDATE fos_user SET city_id = ? WHERE id = ?', [$connectionRequest['city_id'], $user['id']]);
            } else {
                $connection = $this->connection->fetchAll('SELECT * FROM connection WHERE (fluent_speaker_id = ? OR learner_id = ?) AND city_id IS NOT NULL ORDER BY created_at DESC LIMIT 1', [$user['id'], $user['id']]);
                if ($connection) {
                    $connection = current($connection);
                    $this->addSql('UPDATE fos_user SET city_id = ? WHERE id = ?', [$connection['city_id'], $user['id']]);
                }
            }
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A64798BAC62AF');
        $this->addSql('DROP INDEX IDX_957A64798BAC62AF ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP city_id');
    }
}
