<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Version20150906201217 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE abstract_post ADD attributes LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function postUp(Schema $schema)
    {
        $oldPostIds = [
            'o-randome-sluchajny-h-chislah' => 36,
            'postroenie-grafikov-qt' => 38,
            'postroenie-gistogramm-qt' => 42,
            'postroenie-sharikov-kvadratikov-i-t-d-qt' => 46,
            'metod-molekulyarnoj-dinamiki' => 51,
            'prostejshij-robot' => 55,
            'vklyuchenie-setki-qwtplot' => 68,
            'podklyuchenie-opengl-v-qt' => 70,
            'povorot-stseny-v-opengl-qt' => 74,
            'ispol-zovanie-tajmera-v-qt' => 76,
            'problemy-programmistov-yumor' => 88,
            'anekdoty-o-programistah' => 87,
            'windows-lyubov-i-obozhanie' => 90,
            'trehkanal-ny-j-termometr-na-ds18b20' => 98,
            'podarok-devushke-svoimi-rukami' => 102,
            'vrashhenie-stseny-v-opengl-pri-pomoshhi-my-shi-qt' => 109,
            'printsip-mvc-u-web-programmirovanii' => 113,
            'ispol-zovanie-qwtplotspectrocurve' => 116,
            'nachalo-raboty-s-mikrokontrollerami-atmel-avr' => 119,
            'pobitovie-operatsii-v-c' => 121,
            'privet-mir-programmistami-raznogo-urovnya' => 133,
            'bilyard-na-qt' => 141,
            'ispolzovanie-qt-layout-dlya-optimizatsii-interfeysa-programmi' => 134,
            'pesni-o-kompyuterakh-i-programmistakh' => 135,
            'yii-freymvork-vvedenie' => 137,
            'konfiguratsiya-qt-proekta' => 153,
            'symfony-framework-vvedenie' => 155,
            'ustanovka-symfony-2' => 158,
            'yazik-programirovaniya-ruby' => 167,
            'haswell-novoe-pokolenie-intel-protsessorov' => 169,
            'nvidia-gtx-780-gtx-770-i-gtx-760-ti' => 174,
            'xbox-one' => 181,
            'ustanovka-i-nastroyka-apache-php-mysql-v-ubuntu' => 184,
            'struktura-prilozheniya-na-symfony' => 163,
            'sony-playstation-4' => 193,
            'sozdanie-i-udalenie-bundle-v-symfony-urok-4' => 197,
            'kontrolleri-v-symfony-framework-urok-5-kontrolleri' => 199,
            'routing-v-symfony-framework' => 191,
            'nastroyka-php-timezone-v-php-ini' => 205,
            'ispolzovanie-qwtplotspectrogram' => 207,
            'ispolzovanie-shablonov-symfony' => 204,
            'forma-bez-tablits' => 213,
            'symfony-framework-assets-stylesheets' => 217,
            'konvertatsiya-tipov-tablits-v-mysql' => 221,
            'sozdanie-svyazey-phpmyadmin' => 223,
            'bazy-dannikh-u-symfony' => 219,
            'spisok-rutinnikh-zadach-web-programmirovaniya' => 229,
            'doctrine-symfony-framework' => 233,
            'div-vertical-expand' => 237,
            'symfony-framework-doctrine-dql' => 239,
            'proekt-symfony-framework-na-servere' => 241,
            'formi-u-symfony-framework' => 244,
            'ogranichenie-dostupa-pri-pomoshchi-htaccess' => 248,
            'formi-u-symfony-framework-neskolko-tablits' => 247,
            'mapping-usb-modema-v-udev' => 255,
            'authentication-identification-authorization' => 259,
            'avtorizatsiya-i-ogranichenie-dostupa-v-symfony' => 258,
            'sozdanie-saytov' => 265,
            'vzaimodeystvie-twig-i-symfony-framework' => 271,
            'rabota-s-bazami-danny-h-v-php-modul-mysql' => 274,
            'rabota-s-bazami-danny-h-v-php-modul-pdo' => 277,
            'razmeshhenie-float-e-lementov' => 281,
            'benq-gw2265-obzor' => 297,
            'ispol-zovanie-assetic-v-sf' => 312,
            'vvedenie-v-seo' => 317,
            'wordpress-vvedenie' => 340,
            'seo-vnutrennyaya-optimizatsiya-chast-1' => 337,
            'doctrine-struktura-entity' => 391,
            'ispolzovanie-composer' => 313,
            'kollektsiya-bundle' => 413,
            'service-container-and-dependency-injection-in-symfony-framework' => 387,
            'rabota-s-polyami-klassa-v-ruby' => 453,
            'nasledovanie-bandlov' => 465,
            'patterny-i-arhitektura-veb-prilozhenij' => 500,
            'configuration' => 490,
            'nastrojka-nginx-php-fpm-v-debian-ubuntu' => 546,
            'ustanovka-predydushhih-versiy-paketov-v-debian' => 576,
            'rezervnoe-kopirovanie-sajta-na-dropbox' => 569,
            'sleep-v-qt' => 599,
            'rodzher-penrouz-novyj-um-korolya' => 627,
            'fikstury-doctrine-fixturesbundle' => 649,
            'fikstury-alicebundle' => 659,
            'kastomizatsiya-formy-vhoda-na-primere-fosuserbundle' => 679,
            'seo-v-wordpress-ili-kak-ya-vyvodil-sajt-iz-poiskovoj-yamy' => 782,
            'povorot-izobrazheniya-na-html5-canvas' => 809,
            'patterny-proektirovaniya-design-patterns-chast-1-porozhdayushhie' => 833,
        ];

        $em = $this->container->get('doctrine.orm.entity_manager');
        $articlesRepository = $em->getRepository('HarentiusBlogBundle:Article');
        $pendingFlushes = [];

        foreach ($oldPostIds as $slug => $id) {
            $article = $articlesRepository->findOneBy(['slug' => $slug]);

            if ($article) {
                $article->setAttributes(['original_id' => $id]);
                $pendingFlushes[] = $article;
            }
        }

        if ($pendingFlushes) {
            $em->flush($pendingFlushes);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE abstract_post DROP attributes');
    }
}
