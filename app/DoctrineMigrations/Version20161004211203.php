<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161004211203 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $categories = $this->connection->fetchAll('SELECT * FROM category');
        foreach ($categories as $category) {
            if ($translation = $this->translation($category['name'])) {
                $this->addSql('
                    INSERT INTO ext_translations
                    (locale, object_class, field, foreign_key, content)
                    VALUES
                    (?, ?, ?, ?, ?)
                ', [
                    'ar',
                    'AppBundle\Entity\Category',
                    'name',
                    $category['id'],
                    $translation
                ]);
            }
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }

    private function translation($swedish)
    {
        if ($swedish == 'Dans') {
            return'الرقص' ;
        } elseif ($swedish == 'Djur och Natur') {
            return 'الحيوانات و الطبيعة';
        } elseif ($swedish == 'Familj') {
            return 'العائلة';
        } elseif ($swedish == 'Film/TV') {
            return 'الأفلام و مشاهدة التلفاز';
        } elseif ($swedish == 'Litteratur') {
            return 'الأدب و الثقافة';
        } elseif ($swedish == 'Matlagning') {
            return 'تحضير الطعام';
        } elseif ($swedish == 'Musik') {
            return 'الموسيقى';
        } elseif ($swedish == 'Politik') {
            return 'السياسة';
        } elseif ($swedish == 'Promenader') {
            return 'التنزه على الأقدام';
        } elseif ($swedish == 'Resor') {
            return 'الرحلات';
        } elseif ($swedish == 'Sport') {
            return 'الرياضة';
        } elseif ($swedish == 'Träning') {
            return 'التدريب';
        } elseif ($swedish == 'att dansa till musik') {
            return 'الرقص على أنغام الموسيقى';
        } elseif ($swedish == 'att lyssna på musik') {
            return 'الاستماع إل الموسيقى';
        } elseif ($swedish == 'att prata om musik') {
            return 'التحدث عن اللموسيقى';
        } elseif ($swedish == 'att sjunga') {
            return 'الغناء';
        } elseif ($swedish == 'att spela musik') {
            return 'عزف الموسيقى';
        }

        return null;
    }
}
