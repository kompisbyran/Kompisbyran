<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180520192447 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        // this up() migration is auto-generated, please modify it to your needs
		 $categories = [
            'IT/teknik',
            'Fotboll',
        ];
        foreach ($categories as $cat) {
			echo "insert \n";
			 $this->connection->executeQuery(sprintf("INSERT INTO category (name, discr) VALUES ('%s', 'general')", $cat));
        }
		$dbCategories = $this->connection->fetchAll('SELECT * FROM category');
        foreach ($dbCategories as $dbCategory) {
			echo $dbCategory['name']."\n";
            if ($translation = $this->englishTranslation($dbCategory['name'])) {
                $this->addSql('
                    INSERT INTO ext_translations
                    (locale, object_class, field, foreign_key, content)
                    VALUES
                    (?, ?, ?, ?, ?)
                ',  [
                    'en',
                    'AppBundle\Entity\Category',
                    'name',
                    $dbCategory['id'],
                    $translation
                ]); 
				$this->addSql('
                    INSERT INTO ext_translations
                    (locale, object_class, field, foreign_key, content)
                    VALUES
                    (?, ?, ?, ?, ?)
                ', [
                    'en_US',
                    'AppBundle\Entity\Category',
                    'name',
                    $dbCategory['id'],
					$dbCategory['name']
                ]);
				$this->addSql('
                    INSERT INTO ext_translations
                    (locale, object_class, field, foreign_key, content)
                    VALUES
                    (?, ?, ?, ?, ?)
                ', [
                    'ar',
                    'AppBundle\Entity\Category',
                    'name',
                    $dbCategory['id'],
					$this->arabicTranslation($dbCategory['name'])
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
	private function englishTranslation($swedish)
    {
        if ($swedish == 'IT/teknik') {
            return'IT/tech' ;
        } elseif ($swedish == 'Fotboll') {
            return 'Soccer';
        } 
        return null;
    }
	private function arabicTranslation($swedish)
    {
        if ($swedish == 'IT/teknik') {
            return 'تكنولوجيا' ;
        } elseif ($swedish == 'Fotboll') {
            return 'كرة القدم';
        } 
        return null;
    }
}
