<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181128134835 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE users_music_categories');
        $this->addSql('ALTER TABLE category DROP discr');
        $this->addSql('ALTER TABLE connection_request DROP available_weekday, DROP available_weekend, DROP available_day, DROP available_evening');
        $this->addSql('ALTER TABLE fos_user DROP district, DROP activities, DROP about_music, DROP can_play_instrument, DROP can_sing, DROP about_instrument, DROP professional_musician, DROP music_genre');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users_music_categories (user_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_8B911025A76ED395 (user_id), INDEX IDX_8B91102512469DE2 (category_id), PRIMARY KEY(user_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_music_categories ADD CONSTRAINT FK_8B91102512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE users_music_categories ADD CONSTRAINT FK_8B911025A76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD discr VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE connection_request ADD available_weekday TINYINT(1) NOT NULL, ADD available_weekend TINYINT(1) NOT NULL, ADD available_day TINYINT(1) NOT NULL, ADD available_evening TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE fos_user ADD district VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD activities LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD about_music LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD can_play_instrument TINYINT(1) NOT NULL, ADD can_sing TINYINT(1) NOT NULL, ADD about_instrument LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, ADD professional_musician TINYINT(1) NOT NULL, ADD music_genre LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
