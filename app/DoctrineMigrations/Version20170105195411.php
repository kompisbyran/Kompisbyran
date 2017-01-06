<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170105195411 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pre_match (id INT AUTO_INCREMENT NOT NULL, municipality_id INT NOT NULL, fluent_speaker_connection_request_id INT DEFAULT NULL, learner_connection_request_id INT NOT NULL, verified TINYINT(1) NOT NULL, INDEX IDX_60F0AAEEAE6F181C (municipality_id), UNIQUE INDEX UNIQ_60F0AAEEAEF6DF6F (fluent_speaker_connection_request_id), UNIQUE INDEX UNIQ_60F0AAEE735650EB (learner_connection_request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pre_match_ignore (id INT AUTO_INCREMENT NOT NULL, pre_match_id INT NOT NULL, fluent_speaker_id INT NOT NULL, learner_id INT NOT NULL, INDEX IDX_6CB179FD2C689E85 (pre_match_id), INDEX IDX_6CB179FDB527E841 (fluent_speaker_id), INDEX IDX_6CB179FD6209CB66 (learner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_municipality (user_id INT NOT NULL, municipality_id INT NOT NULL, INDEX IDX_46391A2AA76ED395 (user_id), INDEX IDX_46391A2AAE6F181C (municipality_id), PRIMARY KEY(user_id, municipality_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pre_match ADD CONSTRAINT FK_60F0AAEEAE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id)');
        $this->addSql('ALTER TABLE pre_match ADD CONSTRAINT FK_60F0AAEEAEF6DF6F FOREIGN KEY (fluent_speaker_connection_request_id) REFERENCES connection_request (id)');
        $this->addSql('ALTER TABLE pre_match ADD CONSTRAINT FK_60F0AAEE735650EB FOREIGN KEY (learner_connection_request_id) REFERENCES connection_request (id)');
        $this->addSql('ALTER TABLE pre_match_ignore ADD CONSTRAINT FK_6CB179FD2C689E85 FOREIGN KEY (pre_match_id) REFERENCES pre_match (id)');
        $this->addSql('ALTER TABLE pre_match_ignore ADD CONSTRAINT FK_6CB179FDB527E841 FOREIGN KEY (fluent_speaker_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE pre_match_ignore ADD CONSTRAINT FK_6CB179FD6209CB66 FOREIGN KEY (learner_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_municipality ADD CONSTRAINT FK_46391A2AA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_municipality ADD CONSTRAINT FK_46391A2AAE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE connection_request ADD municipality_id INT DEFAULT NULL, ADD available_weekday TINYINT(1) NOT NULL, ADD available_weekend TINYINT(1) NOT NULL, ADD available_day TINYINT(1) NOT NULL, ADD available_evening TINYINT(1) NOT NULL, ADD extra_person TINYINT(1) NOT NULL, ADD extra_person_type VARCHAR(255) DEFAULT NULL, ADD matching_profile_request_type VARCHAR(255) DEFAULT NULL, CHANGE city_id city_id INT DEFAULT NULL, CHANGE comment extra_person_gender VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE connection_request ADD CONSTRAINT FK_409D69BCAE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id)');
        $this->addSql('CREATE INDEX IDX_409D69BCAE6F181C ON connection_request (municipality_id)');
        $this->addSql('ALTER TABLE municipality ADD start_municipality TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE fos_user ADD activities LONGTEXT DEFAULT NULL, ADD occupation VARCHAR(255) NOT NULL, ADD occupation_description LONGTEXT DEFAULT NULL, ADD education TINYINT(1) NOT NULL, ADD education_description LONGTEXT DEFAULT NULL, ADD time_in_sweden LONGTEXT DEFAULT NULL, ADD children_age LONGTEXT DEFAULT NULL, ADD about_music LONGTEXT DEFAULT NULL, ADD can_play_instrument TINYINT(1) NOT NULL, ADD can_sing TINYINT(1) NOT NULL, ADD about_instrument LONGTEXT DEFAULT NULL, ADD professional_musician TINYINT(1) NOT NULL, ADD music_genre LONGTEXT DEFAULT NULL, ADD phone_number LONGTEXT DEFAULT NULL, ADD languages LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pre_match_ignore DROP FOREIGN KEY FK_6CB179FD2C689E85');
        $this->addSql('DROP TABLE pre_match');
        $this->addSql('DROP TABLE pre_match_ignore');
        $this->addSql('DROP TABLE user_municipality');
        $this->addSql('ALTER TABLE connection_request DROP FOREIGN KEY FK_409D69BCAE6F181C');
        $this->addSql('DROP INDEX IDX_409D69BCAE6F181C ON connection_request');
        $this->addSql('ALTER TABLE connection_request ADD comment VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP municipality_id, DROP available_weekday, DROP available_weekend, DROP available_day, DROP available_evening, DROP extra_person, DROP extra_person_gender, DROP extra_person_type, DROP matching_profile_request_type, CHANGE city_id city_id INT NOT NULL');
        $this->addSql('ALTER TABLE fos_user DROP activities, DROP occupation, DROP occupation_description, DROP education, DROP education_description, DROP time_in_sweden, DROP children_age, DROP about_music, DROP can_play_instrument, DROP can_sing, DROP about_instrument, DROP professional_musician, DROP music_genre, DROP phone_number, DROP languages');
        $this->addSql('ALTER TABLE municipality DROP start_municipality');
    }
}
