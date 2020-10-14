<?php

declare(strict_types=1);

namespace App;

use Harentius\BlogBundle\Entity\AbstractPost;

class LocalizationHelper
{
    public const FALLBACK_LOCALE = 'ru';
    public const DEFAULT_LOCALE = 'en';

    /**
     * @param string $slug
     * @return bool
     */
    public function isLegacyArticlesBySlug(string $slug): bool
    {
        return \in_array($slug, $this->getLegacySlugs(), true);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isLegacyArticlesById(int $id): bool
    {
        return $id <= 129;
    }

    /**
     * @param AbstractPost $post
     * @return bool
     */
    public function isArticleOldAndTranslatedToEn(AbstractPost $post): bool
    {
        return $this->isLegacyArticlesById($post->getId())
            && \in_array($post->getSlug(), $this->getEnTranslatedSlugs(), true)
        ;
    }

    /**
     * @return string[]
     */
    private function getEnTranslatedSlugs(): array
    {
        return [
            'index',
            'sozdanie-svyazey-phpmyadmin',
            'caching-symfony-controller',
            'widgets-bundle',
            'refreshing-nested-set-tree-indexes-in-symfony',
            'nepreryvnaia-integratsiia-dlia-node-js-s-circle-ci-v2',
        ];
    }

    /**
     * @return array
     */
    private function getLegacySlugs(): array
    {
        return [
            'povyshenie-proizvoditelnosti-web-servera-nginx-pered-apache',
            'o-randome-sluchajny-h-chislah',
            'postroenie-grafikov-qt',
            'postroenie-gistogramm-qt',
            'postroenie-sharikov-kvadratikov-i-t-d-qt',
            'metod-molekulyarnoj-dinamiki',
            'prostejshij-robot',
            'vklyuchenie-setki-qwtplot',
            'podklyuchenie-opengl-v-qt',
            'povorot-stseny-v-opengl-qt',
            'ispol-zovanie-tajmera-v-qt',
            'problemy-programmistov-yumor',
            'anekdoty-o-programistah',
            'windows-lyubov-i-obozhanie',
            'trehkanal-ny-j-termometr-na-ds18b20',
            'podarok-devushke-svoimi-rukami',
            'vrashhenie-stseny-v-opengl-pri-pomoshhi-my-shi-qt',
            'printsip-mvc-u-web-programmirovanii',
            'ispol-zovanie-qwtplotspectrocurve',
            'nachalo-raboty-s-mikrokontrollerami-atmel-avr',
            'pobitovie-operatsii-v-c',
            'privet-mir-programmistami-raznogo-urovnya',
            'bilyard-na-qt',
            'ispolzovanie-qt-layout-dlya-optimizatsii-interfeysa-programmi',
            'pesni-o-kompyuterakh-i-programmistakh',
            'yii-freymvork-vvedenie',
            'konfiguratsiya-qt-proekta',
            'symfony-framework-vvedenie',
            'ustanovka-symfony-2',
            'yazik-programirovaniya-ruby',
            'haswell-novoe-pokolenie-intel-protsessorov',
            'nvidia-gtx-780-gtx-770-i-gtx-760-ti',
            'xbox-one',
            'ustanovka-i-nastroyka-apache-php-mysql-v-ubuntu',
            'struktura-prilozheniya-na-symfony',
            'sony-playstation-4',
            'sozdanie-i-udalenie-bundle-v-symfony-urok-4',
            'kontrolleri-v-symfony-framework-urok-5-kontrolleri',
            'routing-v-symfony-framework',
            'nastroyka-php-timezone-v-php-ini',
            'ispolzovanie-qwtplotspectrogram',
            'ispolzovanie-shablonov-symfony',
            'forma-bez-tablits',
            'symfony-framework-assets-stylesheets',
            'konvertatsiya-tipov-tablits-v-mysql',
            'sozdanie-svyazey-phpmyadmin',
            'bazy-dannikh-u-symfony',
            'spisok-rutinnikh-zadach-web-programmirovaniya',
            'doctrine-symfony-framework',
            'div-vertical-expand',
            'symfony-framework-doctrine-dql',
            'proekt-symfony-framework-na-servere',
            'formi-u-symfony-framework',
            'ogranichenie-dostupa-pri-pomoshchi-htaccess',
            'formi-u-symfony-framework-neskolko-tablits',
            'mapping-usb-modema-v-udev',
            'authentication-identification-authorization',
            'avtorizatsiya-i-ogranichenie-dostupa-v-symfony',
            'sozdanie-saytov',
            'vzaimodeystvie-twig-i-symfony-framework',
            'rabota-s-bazami-danny-h-v-php-modul-mysql',
            'rabota-s-bazami-danny-h-v-php-modul-pdo',
            'razmeshhenie-float-e-lementov',
            'benq-gw2265-obzor',
            'ispol-zovanie-assetic-v-sf',
            'vvedenie-v-seo',
            'wordpress-vvedenie',
            'seo-vnutrennyaya-optimizatsiya-chast-1',
            'doctrine-struktura-entity',
            'ispolzovanie-composer',
            'kollektsiya-bundle',
            'service-container-and-dependency-injection-in-symfony-framework',
            'rabota-s-polyami-klassa-v-ruby',
            'nasledovanie-bandlov',
            'patterny-i-arhitektura-veb-prilozhenij',
            'configuration',
            'nastrojka-nginx-php-fpm-v-debian-ubuntu',
            'ustanovka-predydushhih-versiy-paketov-v-debian',
            'rezervnoe-kopirovanie-sajta-na-dropbox',
            'sleep-v-qt',
            'rodzher-penrouz-novyj-um-korolya',
            'fikstury-doctrine-fixturesbundle',
            'fikstury-alicebundle',
            'kastomizatsiya-formy-vhoda-na-primere-fosuserbundle',
            'seo-v-wordpress-ili-kak-ya-vyvodil-sajt-iz-poiskovoj-yamy',
            'povorot-izobrazheniya-na-html5-canvas',
            'patterny-proektirovaniya-design-patterns-chast-1-porozhdayushhie',
            'index',
            'kontakty',
            'resursy',
            'gienii-i-autsaidiery',
            'lokalizatsiia-chislovykh-dieniezhnykh-dannykh',
            'caching-symfony-controller',
            'pencil',
            'widgets-bundle',
            'itieratsiia-po-pieriodu-vriemieni-v-php',
            'symfony-blog-bundle',
            'ispol-zovaniie-php-generatorov',
            'udalit-diefoltnyi-blok-sonata-admin-block-admin-list-u-sonataadminbundle',
            'gotovimsia-k-http-2-rukovodstvo-dlia-vieb-dizainierov-i-razrabotchikov',
            'novoie-v-php-7-opierator-obiedinieniia-so-znachieniiem-null',
            'inkapsuliatsiia-s-pomoshch-iu-zamykanii-v-javascript',
            'sozdaniie-riesponsivnykh-tablits-s-pomoshchiu-css',
            'junior-vs-senior-razrabotchik-v-chiem-v-kontsie-kontsov-raznitsa-miezhdu-nimi',
            'otkuda-ghienii',
            'gulp-file-dlia-frontend-proiekta',
            'reaction-game',
            'versiya-assetov-pri-ispolzovanii-gulp',
            'value-object-y-u-symfony-formakh',
            'dobavlieniie-kommientariiev-na-sait',
            'zagruzka-failov-v-symfony',
            'refreshing-nested-set-tree-indexes-in-symfony',
            'kak-v-php-uznat-stroku-na-kotoroi-priervalos-ispolnieniie',
            'nepreryvnaia-integratsiia-s-ispolzovaniem-travis-ci-i-behat',
            'nepreryvnaia-integratsiia-dlia-node-js-s-circle-ci-v2',
        ];
    }
}
