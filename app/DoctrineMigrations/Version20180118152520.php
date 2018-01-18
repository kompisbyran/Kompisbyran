<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180118152520 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $connections = $this->connection->fetchAll('SELECT c.id FROM connection c INNER JOIN fos_user u ON u.id = c.learner_id WHERE u.newly_arrived = 1');
        foreach ($connections as $connection) {
            $this->addSql('UPDATE connection SET newly_arrived = 1 WHERE id = ?', [$connection['id']]);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
