<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170109191102 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `connection` ADD municipality_id INT DEFAULT NULL, CHANGE city_id city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `connection` ADD CONSTRAINT FK_29F77366AE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id)');
        $this->addSql('CREATE INDEX IDX_29F77366AE6F181C ON `connection` (municipality_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `connection` DROP FOREIGN KEY FK_29F77366AE6F181C');
        $this->addSql('DROP INDEX IDX_29F77366AE6F181C ON `connection`');
        $this->addSql('ALTER TABLE `connection` DROP municipality_id, CHANGE city_id city_id INT NOT NULL');
    }
}
