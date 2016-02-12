<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160212195143 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE municipality (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_user ADD municipality_id INT NOT NULL');

        $array = [];
        $array[] = 'Ale kommun';
        $array[] = 'Alingsås kommun';
        $array[] = 'Alvesta kommun';
        $array[] = 'Aneby kommun';
        $array[] = 'Arboga kommun';
        $array[] = 'Arjeplogs kommun';
        $array[] = 'Arvidsjaurs kommun';
        $array[] = 'Arvika kommun';
        $array[] = 'Askersunds kommun';
        $array[] = 'Avesta kommun';
        $array[] = 'Bengtsfors kommun';
        $array[] = 'Bergs kommun';
        $array[] = 'Bjurholms kommun';
        $array[] = 'Bjuvs kommun';
        $array[] = 'Bodens kommun';
        $array[] = 'Bollebygds kommun';
        $array[] = 'Bollnäs kommun';
        $array[] = 'Borgholms kommun';
        $array[] = 'Borlänge kommun';
        $array[] = 'Borås kommun';
        $array[] = 'Botkyrka kommun';
        $array[] = 'Boxholms kommun';
        $array[] = 'Bromölla kommun';
        $array[] = 'Bräcke kommun';
        $array[] = 'Burlövs kommun';
        $array[] = 'Båstads kommun';
        $array[] = 'Dals-Ed kommun';
        $array[] = 'Danderyds kommun';
        $array[] = 'Degerfors kommun';
        $array[] = 'Dorotea kommun';
        $array[] = 'Eda kommun';
        $array[] = 'Ekerö Kommun';
        $array[] = 'Eksjö Kommun';
        $array[] = 'Emmaboda kommun';
        $array[] = 'Enköpings kommun';
        $array[] = 'Eskilstuna kommun';
        $array[] = 'Eslövs kommun';
        $array[] = 'Essunga kommun';
        $array[] = 'Fagersta kommun';
        $array[] = 'Falkenbergs kommun';
        $array[] = 'Falköpings kommun';
        $array[] = 'Faluns kommun';
        $array[] = 'Filipstads kommun';
        $array[] = 'Finspångs kommun';
        $array[] = 'Flens kommun';
        $array[] = 'Forshaga kommun';
        $array[] = 'Färgelanda kommun';
        $array[] = 'Gagnefs kommun';
        $array[] = 'Gislaveds kommun';
        $array[] = 'Gnesta kommun';
        $array[] = 'Gnosjö Kommun';
        $array[] = 'Gotlands kommun';
        $array[] = 'Grums kommun';
        $array[] = 'Grästorps kommun';
        $array[] = 'Gullspångs kommun';
        $array[] = 'Gällivare kommun';
        $array[] = 'Gävle kommun';
        $array[] = 'Göteborgs kommun';
        $array[] = 'Götene kommun';
        $array[] = 'Habo kommun';
        $array[] = 'Hagfors kommun';
        $array[] = 'Hallsbergs kommun';
        $array[] = 'Hallstahammars kommun';
        $array[] = 'Halmstads kommun';
        $array[] = 'Hammarö Kommun';
        $array[] = 'Haninge kommun';
        $array[] = 'Haparanda kommun';
        $array[] = 'Heby kommun';
        $array[] = 'Hedemora kommun';
        $array[] = 'Helsingborgs kommun';
        $array[] = 'Herrljunga kommun';
        $array[] = 'Hjo kommun';
        $array[] = 'Hofors kommun';
        $array[] = 'Huddinge kommun';
        $array[] = 'Hudiksvalls kommun';
        $array[] = 'Hultsfreds kommun';
        $array[] = 'Hylte kommun';
        $array[] = 'Håbo kommun';
        $array[] = 'Hällefors kommun';
        $array[] = 'Härjedalens kommun';
        $array[] = 'Härnösands kommun';
        $array[] = 'Härryda kommun';
        $array[] = 'Hässleholms kommun';
        $array[] = 'Höganäs kommun';
        $array[] = 'Högsby kommun';
        $array[] = 'Hörby kommun';
        $array[] = 'Höörs kommun';
        $array[] = 'Jokkmokks kommun';
        $array[] = 'Järfälla kommun';
        $array[] = 'Jönköpings kommun';
        $array[] = 'Kalix kommun';
        $array[] = 'Kalmar kommun';
        $array[] = 'Karlsborgs kommun';
        $array[] = 'Karlshamns kommun';
        $array[] = 'Karlskoga kommun';
        $array[] = 'Karlskrona kommun';
        $array[] = 'Karlstads kommun';
        $array[] = 'Katrineholms kommun';
        $array[] = 'Kils kommun';
        $array[] = 'Kinda kommun';
        $array[] = 'Kiruna kommun';
        $array[] = 'Klippans kommun';
        $array[] = 'Knivsta kommun';
        $array[] = 'Kramfors kommun';
        $array[] = 'Kristianstads kommun';
        $array[] = 'Kristinehamns kommun';
        $array[] = 'Krokoms kommun';
        $array[] = 'Kumla kommun';
        $array[] = 'Kungsbacka kommun';
        $array[] = 'Kungsörs kommun';
        $array[] = 'Kungälvs kommun';
        $array[] = 'Kävlinge kommun';
        $array[] = 'Köpings kommun';
        $array[] = 'Laholms kommun';
        $array[] = 'Landskrona kommun';
        $array[] = 'Laxå Kommun';
        $array[] = 'Lekebergs kommun';
        $array[] = 'Leksand Municipality';
        $array[] = 'Lerums kommun';
        $array[] = 'Lessebo kommun';
        $array[] = 'Lidingö kommun';
        $array[] = 'Lidköpings kommun';
        $array[] = 'Lilla Edets kommun';
        $array[] = 'Lindesbergs kommun';
        $array[] = 'Linköpings kommun';
        $array[] = 'Ljungby kommun';
        $array[] = 'Ljusdals kommun';
        $array[] = 'Ljusnarsbergs kommun';
        $array[] = 'Lomma kommun';
        $array[] = 'Ludvika kommun';
        $array[] = 'Luleå Kommun';
        $array[] = 'Lunds kommun';
        $array[] = 'Lycksele kommun';
        $array[] = 'Lysekils kommun';
        $array[] = 'Malmö kommun';
        $array[] = 'Malung-Sälens kommun';
        $array[] = 'Malå Kommun';
        $array[] = 'Mariestads kommun';
        $array[] = 'Markaryds kommun';
        $array[] = 'Marks kommun';
        $array[] = 'Melleruds kommun';
        $array[] = 'Mjölby kommun';
        $array[] = 'Mora kommun';
        $array[] = 'Motala kommun';
        $array[] = 'Mullsjö Kommun';
        $array[] = 'Munkedals kommun';
        $array[] = 'Munkfors kommun';
        $array[] = 'Mölndals kommun';
        $array[] = 'Mönsterås kommun';
        $array[] = 'Mörbylånga kommun';
        $array[] = 'Nacka kommun';
        $array[] = 'Nora kommun';
        $array[] = 'Norbergs kommun';
        $array[] = 'Nordanstigs kommun';
        $array[] = 'Nordmalings kommun';
        $array[] = 'Norrköpings kommun';
        $array[] = 'Norrtälje kommun';
        $array[] = 'Norsjö Kommun';
        $array[] = 'Nybro kommun';
        $array[] = 'Nykvarns kommun';
        $array[] = 'Nyköpings kommun';
        $array[] = 'Nynäshamns kommun';
        $array[] = 'Nässjö Kommun';
        $array[] = 'Ockelbo kommun';
        $array[] = 'Olofströms kommun';
        $array[] = 'Orsa kommun';
        $array[] = 'Orusts kommun';
        $array[] = 'Osby kommun';
        $array[] = 'Oskarshamns kommun';
        $array[] = 'Ovanåkers kommun';
        $array[] = 'Oxelösunds kommun';
        $array[] = 'Pajala kommun';
        $array[] = 'Partille kommun';
        $array[] = 'Perstorps kommun';
        $array[] = 'Piteå Kommun';
        $array[] = 'Ragunda kommun';
        $array[] = 'Robertsfors kommun';
        $array[] = 'Ronneby kommun';
        $array[] = 'Rättviks kommun';
        $array[] = 'Sala kommun';
        $array[] = 'Salems kommun';
        $array[] = 'Sandvikens kommun';
        $array[] = 'Sigtuna kommun';
        $array[] = 'Simrishamns kommun';
        $array[] = 'Sjöbo kommun';
        $array[] = 'Skara kommun';
        $array[] = 'Skellefteå Kommun';
        $array[] = 'Skinnskattebergs kommun';
        $array[] = 'Skurups kommun';
        $array[] = 'Skövde kommun';
        $array[] = 'Smedjebackens kommun';
        $array[] = 'Sollefteå Kommun';
        $array[] = 'Sollentuna kommun';
        $array[] = 'Solna kommun';
        $array[] = 'Sorsele kommun';
        $array[] = 'Sotenäs kommun';
        $array[] = 'Staffanstorps kommun';
        $array[] = 'Stenungsunds kommun';
        $array[] = 'Stockholms kommun';
        $array[] = 'Storfors kommun';
        $array[] = 'Storumans kommun';
        $array[] = 'Strängnäs kommun';
        $array[] = 'Strömstads kommun';
        $array[] = 'Strömsunds kommun';
        $array[] = 'Sundbybergs kommun';
        $array[] = 'Sundsvalls kommun';
        $array[] = 'Sunne kommun';
        $array[] = 'Surahammars kommun';
        $array[] = 'Svalövs kommun';
        $array[] = 'Svedala kommun';
        $array[] = 'Svenljunga kommun';
        $array[] = 'Säffle kommun';
        $array[] = 'Säters kommun';
        $array[] = 'Sävsjö Kommun';
        $array[] = 'Söderhamns kommun';
        $array[] = 'Söderköpings kommun';
        $array[] = 'Södertälje kommun';
        $array[] = 'Sölvesborgs kommun';
        $array[] = 'Tanums kommun';
        $array[] = 'Tibro kommun';
        $array[] = 'Tidaholms kommun';
        $array[] = 'Tierps kommun';
        $array[] = 'Timrå Kommun';
        $array[] = 'Tingsryds kommun';
        $array[] = 'Tjörns kommun';
        $array[] = 'Tomelilla kommun';
        $array[] = 'Torsby kommun';
        $array[] = 'Torsås kommun';
        $array[] = 'Tranemo kommun';
        $array[] = 'Tranås kommun';
        $array[] = 'Trelleborgs kommun';
        $array[] = 'Trollhättans kommun';
        $array[] = 'Trosa kommun';
        $array[] = 'Tyresö Kommun';
        $array[] = 'Täby kommun';
        $array[] = 'Töreboda kommun';
        $array[] = 'Uddevalla kommun';
        $array[] = 'Ulricehamns kommun';
        $array[] = 'Umeå Kommun';
        $array[] = 'Upplands-Bro kommun';
        $array[] = 'Upplands Väsby kommun';
        $array[] = 'Uppsala kommun';
        $array[] = 'Uppvidinge kommun';
        $array[] = 'Vadstena kommun';
        $array[] = 'Vaggeryds kommun';
        $array[] = 'Valdemarsviks kommun';
        $array[] = 'Vallentuna kommun';
        $array[] = 'Vansbro kommun';
        $array[] = 'Vara kommun';
        $array[] = 'Varbergs kommun';
        $array[] = 'Vaxholms kommun';
        $array[] = 'Vellinge kommun';
        $array[] = 'Vetlanda kommun';
        $array[] = 'Vilhelmina kommun';
        $array[] = 'Vimmerby kommun';
        $array[] = 'Vindelns kommun';
        $array[] = 'Vingåkers kommun';
        $array[] = 'Vårgårda kommun';
        $array[] = 'Vänersborgs kommun';
        $array[] = 'Vännäs kommun';
        $array[] = 'Värmdö Kommun';
        $array[] = 'Värnamo kommun';
        $array[] = 'Västerviks kommun';
        $array[] = 'Västerås kommun';
        $array[] = 'Växjö Kommun';
        $array[] = 'Ydre kommun';
        $array[] = 'Ystads kommun';
        $array[] = 'Åmåls kommun';
        $array[] = 'Ånge kommun';
        $array[] = 'Åre kommun';
        $array[] = 'Årjängs kommun';
        $array[] = 'Åsele kommun';
        $array[] = 'Åstorps kommun';
        $array[] = 'Åtvidabergs kommun';
        $array[] = 'Älmhults kommun';
        $array[] = 'Älvdalens kommun';
        $array[] = 'Älvkarleby kommun';
        $array[] = 'Älvsbyns kommun';
        $array[] = 'Ängelholms kommun';
        $array[] = 'Öckerö Kommun';
        $array[] = 'Ödeshögs kommun';
        $array[] = 'Örebro kommun';
        $array[] = 'Örkelljunga kommun';
        $array[] = 'Örnsköldsviks kommun';
        $array[] = 'Östersunds kommun';
        $array[] = 'Österåkers kommun';
        $array[] = 'Östhammars kommun';
        $array[] = 'Östra Göinge kommun';
        $array[] = 'Överkalix kommun';
        $array[] = 'Övertorneå Kommun';

        foreach ($array as $municipality) {
            $this->addSql(sprintf("INSERT INTO municipality (name) VALUES ('%s')", $municipality));
        }

        $this->addSql('UPDATE fos_user SET municipality_id = 199'); // Stockholm

        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479AE6F181C FOREIGN KEY (municipality_id) REFERENCES municipality (id)');
        $this->addSql('CREATE INDEX IDX_957A6479AE6F181C ON fos_user (municipality_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479AE6F181C');
        $this->addSql('DROP TABLE municipality');
        $this->addSql('DROP INDEX IDX_957A6479AE6F181C ON fos_user');
        $this->addSql('ALTER TABLE fos_user DROP municipality_id');
    }
}
