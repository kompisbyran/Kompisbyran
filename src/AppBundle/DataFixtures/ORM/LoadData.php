<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\City;
use AppBundle\Entity\Connection;
use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\GeneralCategory;
use AppBundle\Entity\Municipality;
use AppBundle\Entity\MusicCategory;
use AppBundle\Entity\User;
use AppBundle\Enum\ExtraPersonTypes;
use AppBundle\Enum\FriendTypes;
use AppBundle\Enum\MatchingProfileRequestTypes;
use AppBundle\Enum\OccupationTypes;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadCategories($manager);
        $this->loadMusicCategories($manager);
        $this->loadCities($manager);
        $this->loadMunicipalities($manager);
        $this->loadUsers($manager);
        $this->loadConnectionRequests($manager);
        $this->loadConnections($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadCategories(ObjectManager $manager)
    {
        $categories = [
            'Dans' => ['en' => 'Dancing'],
            'Djur och Natur' => ['en' => 'Animals and nature'],
            'Familj' => ['en' => 'Family'],
            'Fika' => ['en' => 'Film/ TV'],
            'Film' => ['en' => 'Literature'],
            'Konst' => ['en' => 'Cooking'],
            'Matlagning' => ['en' => 'Music'],
            'Musik' => ['en' => 'Politics'],
            'Politik' => ['en' => 'Go for a walk'],
            'Resor' => ['en' => 'Travelling'],
            'Sport' => ['en' => 'Sport'],
            'Träning' => ['en' => 'Training'],
        ];
        $i = 0;
        $repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        foreach ($categories as $categoryName => $translations) {
            $category = new GeneralCategory();
            $category->setName($categoryName);

            foreach ($translations as $locale => $translation) {
                $repository->translate($category, 'name', $locale, $translation);
            }

            $manager->persist($category);
            $this->addReference(sprintf('category-%s', $i++), $category);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadMusicCategories(ObjectManager $manager)
    {
        $categories = [
            'Jazz' => ['en' => 'Jazz'],
            'Hip hop' => ['en' => 'Hip hop'],
            'Hårdrock' => ['en' => 'Hard rock'],
        ];
        $i = 0;
        $repository = $manager->getRepository('Gedmo\\Translatable\\Entity\\Translation');
        foreach ($categories as $categoryName => $translations) {
            $category = new MusicCategory();
            $category->setName($categoryName);

            foreach ($translations as $locale => $translation) {
                $repository->translate($category, 'name', $locale, $translation);
            }

            $manager->persist($category);
            $this->addReference(sprintf('music-category-%s', $i++), $category);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadCities(ObjectManager $manager)
    {
        $cities = [
            'Örebro',
            'Stockholm',
            'Göteborg',
        ];
        foreach ($cities as $i => $cityName) {
            $city = new City();
            $city->setName($cityName);
            $city->setSenderEmail('matchning@kompisbyran.se');
            $manager->persist($city);
            $this->addReference(sprintf('city-%s', $i), $city);
        }
    }

    protected function loadMunicipalities(ObjectManager $manager)
    {
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

        foreach ($array as $i => $value) {
            $municipality = new Municipality();
            $municipality->setName($value);
            if ($i == 198) {
                $municipality->setStartMunicipality(true);
                $municipality->setMatchFamily(true);
                $municipality->setMeetingPlace('på Slottet');
            }
            $manager->persist($municipality);
            $this->addReference('municipality-' . $i, $municipality);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('learner@example.com');
        $user->setFirstName('Kalle');
        $user->setLastName('Anka');
        $user->setEnabled(true);
        $user->addRole('ROLE_COMPLETE_USER');
        $user->setWantToLearn(true);
        $user->setAge(35);
        $user->setAbout('Sportintresserad man med 3 barn');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('SY');
        $user->setGender('M');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/men/1.jpg');
        $user->setDistrict('Södermalm');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-learner', $user);

        $user = new User();
        $user->setEmail('fluentspeaker@example.com');
        $user->setFirstName('Kal p');
        $user->setLastName('Dal');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
        $user->setWantToLearn(false);
        $user->setAge(40);
        $user->setAbout('Sportintresserad lärare');
        $user->setCategories([$this->getReference('category-0'), $this->getReference('category-1')]);
        $user->setFrom('SE');
        $user->setGender('M');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/men/2.jpg');
        $user->setDistrict('Årsta');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->addCity($this->getReference('city-0'));
        $user->addCity($this->getReference('city-1'));
        $user->addCity($this->getReference('city-2'));
        $user->addAdminMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-fluentspeaker', $user);

        $user = new User();
        $user->setEmail('glenn@example.com');
        $user->setFirstName('Glenn');
        $user->setLastName('GBG');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER', 'ROLE_ADMIN']);
        $user->setWantToLearn(false);
        $user->setAge(20);
        $user->setAbout('Göteborgare');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('SE');
        $user->setGender('M');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/men/3.jpg');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-glenn', $user);

        $user = new User();
        $user->setEmail('incomplete@example.com');
        $user->setEnabled(true);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('emma@example.com');
        $user->setFirstName('Emma');
        $user->setLastName('Hansson');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER', 'ROLE_ADMIN']);
        $user->setWantToLearn(false);
        $user->setAge(20);
        $user->setAbout('-');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('SE');
        $user->setGender('F');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/women/3.jpg');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-emma', $user);

        $user = new User();
        $user->setEmail('cecilia@example.com');
        $user->setFirstName('Cecilia');
        $user->setLastName('Holmgren');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER', 'ROLE_ADMIN']);
        $user->setWantToLearn(false);
        $user->setAge(20);
        $user->setAbout('-');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('SE');
        $user->setGender('F');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/women/4.jpg');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-cecilia', $user);

        $user = new User();
        $user->setType(FriendTypes::START);
        $user->setEmail('jon@example.com');
        $user->setFirstName('Jon');
        $user->setLastName('Gotlin');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER']);
        $user->setWantToLearn(true);
        $user->setAge(20);
        $user->setAbout('-');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('SE');
        $user->setGender('M');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/men/4.jpg');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-jon', $user);

        $user = new User();
        $user->setType(FriendTypes::START);
        $user->setEmail('kal@example.com');
        $user->setFirstName('Kal');
        $user->setLastName('Ström');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER']);
        $user->setWantToLearn(true);
        $user->setAge(20);
        $user->setAbout('-');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('SE');
        $user->setGender('M');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/men/5.jpg');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-kal', $user);

        $user = new User();
        $user->setType(FriendTypes::START);
        $user->setEmail('malin@example.com');
        $user->setFirstName('Malin');
        $user->setLastName('Gotlin');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER']);
        $user->setWantToLearn(false);
        $user->setAge(20);
        $user->setAbout('-');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('SE');
        $user->setGender('F');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/women/5.jpg');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-malin', $user);

        $user = new User();
        $user->setType(FriendTypes::START);
        $user->setEmail('stella@example.com');
        $user->setFirstName('Stella');
        $user->setLastName('Gotlin');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER']);
        $user->setWantToLearn(false);
        $user->setAge(20);
        $user->setAbout('-');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('SE');
        $user->setGender('F');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/women/6.jpg');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-stella', $user);

        $user = new User();
        $user->setType(FriendTypes::START);
        $user->setEmail('municipality@example.com');
        $user->setFirstName('Magdalena');
        $user->setLastName('Svan');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_MUNICIPALITY']);
        $user->addAdminMunicipality($this->getReference('municipality-198'));
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-magdalena', $user);


        $user = new User();
        $user->setType(FriendTypes::START);
        $user->setEmail('anna@example.com');
        $user->setFirstName('Anna');
        $user->setLastName('Andersson');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER']);
        $user->setWantToLearn(false);
        $user->setAge(20);
        $user->setAbout('-');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('SE');
        $user->setGender('F');
        $user->setProfilePicture('http://api.randomuser.me/portraits/thumb/women/6.jpg');
        $user->setMunicipality($this->getReference('municipality-198'));
        $user->setOccupation(OccupationTypes::EMPLOYED);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);
        $this->addReference('user-anna', $user);
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadConnectionRequests(ObjectManager $manager)
    {
        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setWantToLearn(true);
        $connectionRequest->setCity($this->getReference('city-1'));
        $connectionRequest->setUser($this->getReference('user-learner'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setWantToLearn(false);
        $connectionRequest->setCity($this->getReference('city-1'));
        $connectionRequest->setUser($this->getReference('user-fluentspeaker'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setWantToLearn(false);
        $connectionRequest->setCity($this->getReference('city-2'));
        $connectionRequest->setUser($this->getReference('user-glenn'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setWantToLearn(false);
        $connectionRequest->setCity($this->getReference('city-1'));
        $connectionRequest->setUser($this->getReference('user-emma'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $connectionRequest->setExtraPerson(true);
        $connectionRequest->setExtraPersonGender(User::GENDER_MALE);
        $connectionRequest->setExtraPersonType(ExtraPersonTypes::FAMILY);
        $connectionRequest->setMatchingProfileRequestType(MatchingProfileRequestTypes::SAME_AGE);

        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setType(FriendTypes::START);
        $connectionRequest->setWantToLearn(false);
        $connectionRequest->setMunicipality($this->getReference('municipality-198'));
        $connectionRequest->setUser($this->getReference('user-cecilia'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setType(FriendTypes::START);
        $connectionRequest->setWantToLearn(true);
        $connectionRequest->setMunicipality($this->getReference('municipality-198'));
        $connectionRequest->setUser($this->getReference('user-jon'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(false);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setType(FriendTypes::START);
        $connectionRequest->setWantToLearn(true);
        $connectionRequest->setMunicipality($this->getReference('municipality-198'));
        $connectionRequest->setUser($this->getReference('user-kal'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(false);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setType(FriendTypes::START);
        $connectionRequest->setWantToLearn(false);
        $connectionRequest->setMunicipality($this->getReference('municipality-198'));
        $connectionRequest->setUser($this->getReference('user-malin'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(false);
        $connectionRequest->setAvailableWeekend(true);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setType(FriendTypes::START);
        $connectionRequest->setWantToLearn(false);
        $connectionRequest->setMunicipality($this->getReference('municipality-198'));
        $connectionRequest->setUser($this->getReference('user-stella'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(false);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setWantToLearn(true);
        $connectionRequest->setCity($this->getReference('city-1'));
        $connectionRequest->setUser($this->getReference('user-learner'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $this->setReference('connection-request-learner', $connectionRequest);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setWantToLearn(false);
        $connectionRequest->setCity($this->getReference('city-1'));
        $connectionRequest->setUser($this->getReference('user-fluentspeaker'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $this->setReference('connection-request-fluentspeaker', $connectionRequest);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setType(FriendTypes::START);
        $connectionRequest->setWantToLearn(true);
        $connectionRequest->setMunicipality($this->getReference('municipality-198'));
        $connectionRequest->setUser($this->getReference('user-anna'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(false);
        $this->setReference('connection-request-anna', $connectionRequest);
        $manager->persist($connectionRequest);

        $connectionRequest = new ConnectionRequest();
        $connectionRequest->setWantToLearn(false);
        $connectionRequest->setCity($this->getReference('city-1'));
        $connectionRequest->setUser($this->getReference('user-fluentspeaker'));
        $connectionRequest->setInspected(true);
        $connectionRequest->setAvailableDay(true);
        $connectionRequest->setAvailableEvening(true);
        $connectionRequest->setAvailableWeekday(true);
        $connectionRequest->setAvailableWeekend(true);
        $this->setReference('connection-request-fluentspeaker2', $connectionRequest);
        $manager->persist($connectionRequest);
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadConnections(ObjectManager $manager)
    {
        $connection = new Connection($this->getReference('user-fluentspeaker'));
        $connection->setCity($this->getReference('city-1'));
        $connection->setFluentSpeaker($this->getReference('user-fluentspeaker'));
        $connection->setLearner($this->getReference('user-learner'));
        $connection->setFluentSpeakerConnectionRequestCreatedAt(new \DateTime());
        $connection->setLearnerConnectionRequestCreatedAt(new \DateTime());
        $connection->setFluentSpeakerConnectionRequest($this->getReference('connection-request-fluentspeaker'));
        $connection->setLearnerConnectionRequest($this->getReference('connection-request-learner'));
        $manager->persist($connection);

        $connection = new Connection($this->getReference('user-fluentspeaker'));
        $connection->setCity($this->getReference('city-1'));
        $connection->setFluentSpeaker($this->getReference('user-fluentspeaker'));
        $connection->setLearner($this->getReference('user-anna'));
        $connection->setFluentSpeakerConnectionRequestCreatedAt(new \DateTime());
        $connection->setLearnerConnectionRequestCreatedAt(new \DateTime());
        $connection->setFluentSpeakerConnectionRequest($this->getReference('connection-request-fluentspeaker2'));
        $connection->setLearnerConnectionRequest($this->getReference('connection-request-anna'));
        $manager->persist($connection);
    }
}
