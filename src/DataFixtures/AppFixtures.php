<?php

namespace App\DataFixtures;

use App\Entity\AboutHighlight;
use App\Entity\Allergen;
use App\Entity\Category;
use App\Entity\ContactMessage;
use App\Entity\FeaturedItem;
use App\Entity\GalleryImage;
use App\Entity\OpeningHour;
use App\Entity\Page;
use App\Entity\Product;
use App\Entity\Review;
use App\Entity\SiteSettings;
use App\Entity\SocialLink;
use App\Enum\ContactMessageStatus;
use App\Enum\DayOfWeek;
use App\Enum\FeaturedItemType;
use App\Enum\PageCode;
use App\Enum\ReviewSourceType;
use App\Enum\SocialLinkType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $siteSettings = $this->createSiteSettings();
        $manager->persist($siteSettings);

        $pages = $this->createPages($siteSettings);
        foreach ($pages as $page) {
            $manager->persist($page);
        }

        $allergens = $this->createAllergens();
        foreach ($allergens as $allergen) {
            $manager->persist($allergen);
        }

        $categories = $this->createCategories();
        foreach ($categories as $category) {
            $manager->persist($category);
        }

        $products = $this->createProducts($categories, $allergens);
        foreach ($products as $product) {
            $manager->persist($product);
        }

        foreach ($this->createOpeningHours($siteSettings) as $openingHour) {
            $manager->persist($openingHour);
        }

        foreach ($this->createSocialLinks($siteSettings) as $socialLink) {
            $manager->persist($socialLink);
        }

        foreach ($this->createGalleryImages($siteSettings) as $galleryImage) {
            $manager->persist($galleryImage);
        }

        foreach ($this->createReviews($siteSettings) as $review) {
            $manager->persist($review);
        }

        foreach ($this->createContactMessages($siteSettings) as $message) {
            $manager->persist($message);
        }

        foreach ($this->createAboutHighlights($siteSettings) as $highlight) {
            $manager->persist($highlight);
        }

        foreach ($this->createFeaturedItems($siteSettings, $categories, $products) as $featuredItem) {
            $manager->persist($featuredItem);
        }

        $manager->flush();
    }

    private function createSiteSettings(): SiteSettings
    {
        return (new SiteSettings())
            ->setBusinessName('Comptoir Saint-Gilles')
            ->setSlogan('Sandwichs artisanaux, assiettes genereuses et service rapide a midi.')
            ->setPhone('+32 2 555 19 26')
            ->setEmail('bonjour@comptoirstgilles.demo')
            ->setWhatsapp('+32 470 12 34 56')
            ->setFullAddress("Chaussée de Waterloo 214\n1060 Saint-Gilles\nBelgique")
            ->setShortAddress('Saint-Gilles, Bruxelles')
            ->setLatitude('50.8246500')
            ->setLongitude('4.3512200')
            ->setLogo('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=400&q=80')
            ->setHeroImage('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1600&q=80')
            ->setNotificationEmail('contact@comptoirstgilles.demo')
            ->setEmailNotificationsEnabled(true)
            ->setGeneralNotes('Jeu de donnees de demonstration V1 pour un commerce de petite restauration rapide.');
    }

    /**
     * @return list<Page>
     */
    private function createPages(SiteSettings $siteSettings): array
    {
        return [
            $this->createPage(
                $siteSettings,
                PageCode::HOME,
                'Sandwicherie de quartier a Saint-Gilles',
                'accueil',
                'Sandwicherie artisanale a Saint-Gilles | Comptoir Saint-Gilles',
                'Sandwichs, assiettes et formules dejeuner prepares a la commande dans une adresse de quartier a Bruxelles.',
                'Une adresse de quartier pour manger vite, bien et sans complication.',
                "Le Comptoir Saint-Gilles propose une carte courte, lisible et rassurante.\nNos recettes vont a l'essentiel : bon pain, produits bien choisis, preparation rapide et accueil direct."
            ),
            $this->createPage(
                $siteSettings,
                PageCode::MENU,
                'Notre carte',
                'carte',
                'Carte de la sandwicherie | Comptoir Saint-Gilles',
                'Consultez la carte de demonstration avec sandwichs, assiettes, menus et boissons.',
                'Une carte simple a lire, pensee pour le service du midi.',
                "Chaque categorie illustre un cas d'usage concret : sandwichs signatures, assiettes, formules et boissons.\nLe but est de montrer une carte credible, administrable et facile a personnaliser."
            ),
            $this->createPage(
                $siteSettings,
                PageCode::ABOUT,
                'A propos du comptoir',
                'a-propos',
                'A propos | Comptoir Saint-Gilles',
                'Decouvrez l\'esprit du Comptoir Saint-Gilles, sa cuisine rapide et sa logique de quartier.',
                'Un commerce pense pour les habitants du quartier, les salaries et les pauses dejeuner sans detour.',
                "Le Comptoir Saint-Gilles est une enseigne fictive concue pour une demonstration realiste.\nOn y retrouve les codes d'une bonne sandwicherie de ville : service fluide, portions genereuses, recettes lisibles et ambiance chaleureuse.\nCette page sert de base pour raconter l'histoire du lieu, ses engagements et ses points forts."
            ),
            $this->createPage(
                $siteSettings,
                PageCode::CONTACT,
                'Contact et acces',
                'contact',
                'Contact, horaires et acces | Comptoir Saint-Gilles',
                'Retrouvez l\'adresse, les horaires, les reseaux sociaux et les informations utiles pour venir ou contacter le commerce.',
                'Toutes les informations utiles pour nous joindre ou venir sur place.',
                "La page contact centralise l'adresse, les horaires, la carte et les canaux directs.\nElle doit rester utile meme sans commande en ligne."
            ),
            $this->createPage(
                $siteSettings,
                PageCode::LEGAL,
                'Mentions legales',
                'mentions-legales',
                'Mentions legales | Comptoir Saint-Gilles',
                'Exemple de page de mentions legales pour le socle Sandwicherie V1.',
                'Un contenu legal de demonstration a adapter avant mise en ligne.',
                "Cette page est fournie a titre d'exemple pour illustrer la structure editoriale du site.\nLes informations doivent etre remplacees avant toute mise en production."
            ),
            $this->createPage(
                $siteSettings,
                PageCode::PRIVACY,
                'Politique de confidentialite',
                'politique-de-confidentialite',
                'Politique de confidentialite | Comptoir Saint-Gilles',
                'Exemple de politique de confidentialite pour les formulaires et donnees de contact.',
                'Un cadre simple pour expliquer la collecte minimale de donnees.',
                "Les donnees collectees via le formulaire sont limitees a l'echange commercial ou informatif.\nCette page sert de base de travail avant personnalisation juridique."
            ),
            $this->createPage(
                $siteSettings,
                PageCode::COOKIES,
                'Politique cookies',
                'cookies',
                'Politique cookies | Comptoir Saint-Gilles',
                'Exemple de page cookies pour la V1 du socle vitrine.',
                'Une page de demonstration pour documenter l\'usage eventuel de cookies.',
                "Le socle V1 reste volontairement simple.\nCette page permet d'ajouter ensuite une information claire sur les cookies techniques, statistiques ou tiers si necessaire."
            ),
        ];
    }

    private function createPage(
        SiteSettings $siteSettings,
        PageCode $code,
        string $title,
        string $slug,
        string $seoTitle,
        string $metaDescription,
        string $intro,
        string $content
    ): Page {
        return (new Page())
            ->setSiteSettings($siteSettings)
            ->setCode($code)
            ->setTitle($title)
            ->setSlug($slug)
            ->setSeoTitle($seoTitle)
            ->setMetaDescription($metaDescription)
            ->setIntroduction($intro)
            ->setContent($content)
            ->setIsActive(true);
    }

    /**
     * @return array<string, Allergen>
     */
    private function createAllergens(): array
    {
        $rows = [
            ['gluten', 'Gluten'],
            ['milk', 'Lait'],
            ['egg', 'Oeufs'],
            ['sesame', 'Sesame'],
            ['mustard', 'Moutarde'],
            ['soy', 'Soja'],
            ['sulfites', 'Sulfites'],
        ];

        $allergens = [];
        foreach ($rows as $index => [$code, $name]) {
            $allergens[$code] = (new Allergen())
                ->setCode($code)
                ->setName($name)
                ->setPosition($index + 1)
                ->setIsActive(true);
        }

        return $allergens;
    }

    /**
     * @return array<string, Category>
     */
    private function createCategories(): array
    {
        $definitions = [
            'sandwichs' => [
                'name' => 'Sandwichs',
                'description' => 'Les signatures du comptoir, prepares a la minute sur pain croustillant.',
                'image' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=1200&q=80',
            ],
            'assiettes' => [
                'name' => 'Assiettes',
                'description' => 'Des assiettes completes pour un dejeuner plus pose, avec accompagnements et sauces maison.',
                'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1200&q=80',
            ],
            'menus' => [
                'name' => 'Menus',
                'description' => 'Des formules simples pour gagner du temps sans perdre en generosite.',
                'image' => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?auto=format&fit=crop&w=1200&q=80',
            ],
            'boissons' => [
                'name' => 'Boissons',
                'description' => 'Boissons fraiches et chaudes pour completer la pause midi.',
                'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        $categories = [];
        $position = 1;
        foreach ($definitions as $key => $definition) {
            $categories[$key] = (new Category())
                ->setName($definition['name'])
                ->setDescription($definition['description'])
                ->setImage($definition['image'])
                ->setPosition($position)
                ->setIsActive(true)
                ->setSeoTitle($definition['name'].' | Carte du Comptoir Saint-Gilles')
                ->setMetaDescription($definition['description']);
            ++$position;
        }

        return $categories;
    }

    /**
     * @param array<string, Category> $categories
     * @param array<string, Allergen> $allergens
     *
     * @return array<string, Product>
     */
    private function createProducts(array $categories, array $allergens): array
    {
        $definitions = [
            'club-poulet' => ['sandwichs', 'Club poulet grille', 'Poulet marine, cheddar, salade romaine, tomate, sauce yaourt citron.', '9.50', ['gluten', 'milk', 'egg']],
            'pastrami-comte' => ['sandwichs', 'Pastrami comte', 'Pastrami, comte affine, pickles d\'oignon rouge, moutarde douce.', '10.50', ['gluten', 'milk', 'mustard']],
            'falafel-herbes' => ['sandwichs', 'Falafel herbes fraiches', 'Falafels, houmous, concombre, menthe, coriandre, sauce tahini.', '8.90', ['gluten', 'sesame']],
            'assiette-kefta' => ['assiettes', 'Assiette kefta', 'Kefta boeuf, boulgour, crudites, sauce blanche et pickles.', '13.90', ['gluten', 'milk']],
            'assiette-vegetale' => ['assiettes', 'Assiette vegetale', 'Falafels, legumes rotis, houmous, salade croquante, vinaigrette citron.', '12.50', ['sesame']],
            'assiette-poulet' => ['assiettes', 'Assiette poulet citron', 'Poulet roti, pommes grenaille, salade du jour, sauce citronnee.', '13.50', ['milk', 'mustard']],
            'menu-midi' => ['menus', 'Menu midi express', 'Un sandwich signature, une boisson fraiche et un cookie du jour.', '12.90', ['gluten', 'milk', 'egg']],
            'menu-assiette' => ['menus', 'Menu assiette complete', 'Une assiette au choix, une boisson et un cafe espresso.', '16.50', ['milk']],
            'menu-veggie' => ['menus', 'Menu veggie', 'Sandwich falafel, citronnade maison et salade de saison.', '12.50', ['gluten', 'sesame']],
            'citronnade' => ['boissons', 'Citronnade maison', 'Citron presse, menthe fraiche, eau petillante et sirop leger.', '3.80', []],
            'the-glace' => ['boissons', 'The glace peche', 'The noir infuse maison, peche blanche et glacons.', '3.90', []],
            'mate-citron' => ['boissons', 'Mate citron', 'Boisson petillante au mate et citron vert.', '4.20', []],
        ];

        $products = [];
        $positionByCategory = [];

        foreach ($definitions as $key => [$categoryKey, $name, $ingredients, $price, $allergenCodes]) {
            $positionByCategory[$categoryKey] = ($positionByCategory[$categoryKey] ?? 0) + 1;

            $product = (new Product())
                ->setCategory($categories[$categoryKey])
                ->setName($name)
                ->setIngredients($ingredients)
                ->setPrice($price)
                ->setPosition($positionByCategory[$categoryKey])
                ->setIsAvailable(true)
                ->setImage(null);

            foreach ($allergenCodes as $allergenCode) {
                $product->addAllergen($allergens[$allergenCode]);
            }

            $products[$key] = $product;
        }

        return $products;
    }

    /**
     * @return list<OpeningHour>
     */
    private function createOpeningHours(SiteSettings $siteSettings): array
    {
        $definitions = [
            [DayOfWeek::MONDAY, false, '11:30', '14:30', '18:00', '21:30'],
            [DayOfWeek::TUESDAY, false, '11:30', '14:30', '18:00', '21:30'],
            [DayOfWeek::WEDNESDAY, false, '11:30', '14:30', '18:00', '21:30'],
            [DayOfWeek::THURSDAY, false, '11:30', '14:30', '18:00', '21:30'],
            [DayOfWeek::FRIDAY, false, '11:30', '14:30', '18:00', '22:00'],
            [DayOfWeek::SATURDAY, false, '12:00', '15:00', '18:30', '22:00'],
            [DayOfWeek::SUNDAY, true, null, null, null, null],
        ];

        $hours = [];
        foreach ($definitions as $index => [$day, $isClosed, $firstOpen, $firstClose, $secondOpen, $secondClose]) {
            $hours[] = (new OpeningHour())
                ->setSiteSettings($siteSettings)
                ->setDayOfWeek($day)
                ->setIsClosed($isClosed)
                ->setFirstOpeningAt($this->time($firstOpen))
                ->setFirstClosingAt($this->time($firstClose))
                ->setSecondOpeningAt($this->time($secondOpen))
                ->setSecondClosingAt($this->time($secondClose))
                ->setPosition($index + 1);
        }

        return $hours;
    }

    /**
     * @return list<SocialLink>
     */
    private function createSocialLinks(SiteSettings $siteSettings): array
    {
        return [
            (new SocialLink())
                ->setSiteSettings($siteSettings)
                ->setType(SocialLinkType::INSTAGRAM)
                ->setLabel('Instagram')
                ->setUrl('https://instagram.com/comptoirstgilles.demo')
                ->setPosition(1)
                ->setIsActive(true),
            (new SocialLink())
                ->setSiteSettings($siteSettings)
                ->setType(SocialLinkType::FACEBOOK)
                ->setLabel('Facebook')
                ->setUrl('https://facebook.com/comptoirstgilles.demo')
                ->setPosition(2)
                ->setIsActive(true),
            (new SocialLink())
                ->setSiteSettings($siteSettings)
                ->setType(SocialLinkType::TIKTOK)
                ->setLabel('TikTok')
                ->setUrl('https://tiktok.com/@comptoirstgilles.demo')
                ->setPosition(3)
                ->setIsActive(true),
        ];
    }

    /**
     * @return list<GalleryImage>
     */
    private function createGalleryImages(SiteSettings $siteSettings): array
    {
        $images = [
            ['https://images.unsplash.com/photo-1552566626-52f8b828add9?auto=format&fit=crop&w=1200&q=80', 'Vue du comptoir et de la preparation du midi'],
            ['https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80', 'Selection de sandwichs et assiettes servis au comptoir'],
            ['https://images.unsplash.com/photo-1482049016688-2d3e1b311543?auto=format&fit=crop&w=1200&q=80', 'Formule dejeuner avec sandwich, boisson et dessert'],
            ['https://images.unsplash.com/photo-1528735602780-2552fd46c7af?auto=format&fit=crop&w=1200&q=80', 'Pain garni et ingredients frais en gros plan'],
        ];

        $gallery = [];
        foreach ($images as $index => [$url, $altText]) {
            $gallery[] = (new GalleryImage())
                ->setSiteSettings($siteSettings)
                ->setImage($url)
                ->setAltText($altText)
                ->setPosition($index + 1)
                ->setIsActive(true);
        }

        return $gallery;
    }

    /**
     * @return list<Review>
     */
    private function createReviews(SiteSettings $siteSettings): array
    {
        return [
            (new Review())
                ->setSiteSettings($siteSettings)
                ->setFirstName('Julie')
                ->setLastInitial('M')
                ->setContent('Service rapide, sandwich bien garni et equipe agreable. Parfait pour la pause dejeuner.')
                ->setRating(5)
                ->setSourceType(ReviewSourceType::GOOGLE)
                ->setSourceUrl('https://maps.google.com/')
                ->setPosition(1)
                ->setIsVisible(true),
            (new Review())
                ->setSiteSettings($siteSettings)
                ->setFirstName('Karim')
                ->setLastInitial('B')
                ->setContent('La formule midi est claire, les produits sont frais et la commande par telephone est pratique.')
                ->setRating(5)
                ->setSourceType(ReviewSourceType::DIRECT)
                ->setPosition(2)
                ->setIsVisible(true),
            (new Review())
                ->setSiteSettings($siteSettings)
                ->setFirstName('Sophie')
                ->setLastInitial('D')
                ->setContent('Bonne adresse de quartier, mention speciale pour l\'assiette vegetale et la citronnade maison.')
                ->setRating(4)
                ->setSourceType(ReviewSourceType::FACEBOOK)
                ->setSourceUrl('https://facebook.com/')
                ->setPosition(3)
                ->setIsVisible(true),
            (new Review())
                ->setSiteSettings($siteSettings)
                ->setFirstName('Nicolas')
                ->setLastInitial('R')
                ->setContent('Carte lisible, tarifs coherents et vraie sensation de commerce local plutot que de chaine.')
                ->setRating(5)
                ->setSourceType(ReviewSourceType::OTHER)
                ->setSourceLabel('Avis client')
                ->setPosition(4)
                ->setIsVisible(true),
        ];
    }

    /**
     * @return list<ContactMessage>
     */
    private function createContactMessages(SiteSettings $siteSettings): array
    {
        $messages = [
            ['Claire Dubois', 'claire@example.com', '0470 00 11 22', 'Bonjour, proposez-vous une formule vegetarienne pour un groupe de 8 personnes demain midi ?', ContactMessageStatus::NEW, '-2 days'],
            ['Marc El Idrissi', 'marc@example.com', null, 'Pouvez-vous preparer trois menus midi pour retrait a 12h15 vendredi ?', ContactMessageStatus::READ, '-1 day'],
            ['Ana Pereira', 'ana@example.com', '0488 55 66 77', 'Je souhaite savoir si certaines recettes peuvent etre adaptees sans gluten.', ContactMessageStatus::PROCESSED, '-6 hours'],
        ];

        $entities = [];
        foreach ($messages as [$name, $email, $phone, $message, $status, $when]) {
            $createdAt = new \DateTimeImmutable($when);
            $entities[] = (new ContactMessage())
                ->setSiteSettings($siteSettings)
                ->setName($name)
                ->setEmail($email)
                ->setPhone($phone)
                ->setMessage($message)
                ->setStatus($status)
                ->setCreatedAt($createdAt)
                ->setUpdatedAt($createdAt);
        }

        return $entities;
    }

    /**
     * @return list<AboutHighlight>
     */
    private function createAboutHighlights(SiteSettings $siteSettings): array
    {
        $rows = [
            ['Preparation minute', 'Chaque commande est preparee a la demande pour garder du rythme sans sacrifier la fraicheur.'],
            ['Carte courte', 'Un nombre limite de recettes, lisibles et faciles a faire vivre dans l\'admin client.'],
            ['Ancrage local', 'Une identite de quartier, utile au quotidien, avec des coordonnees et horaires clairs.'],
            ['Canaux directs', 'Telephone, WhatsApp et formulaire restent prioritaires dans la V1 pour garder un parcours simple.'],
        ];

        $highlights = [];
        foreach ($rows as $index => [$title, $shortText]) {
            $highlights[] = (new AboutHighlight())
                ->setSiteSettings($siteSettings)
                ->setTitle($title)
                ->setShortText($shortText)
                ->setPosition($index + 1)
                ->setIsActive(true);
        }

        return $highlights;
    }

    /**
     * @param array<string, Category> $categories
     * @param array<string, Product> $products
     *
     * @return list<FeaturedItem>
     */
    private function createFeaturedItems(SiteSettings $siteSettings, array $categories, array $products): array
    {
        return [
            (new FeaturedItem())
                ->setSiteSettings($siteSettings)
                ->setType(FeaturedItemType::PRODUCT)
                ->setProduct($products['club-poulet'])
                ->setPosition(1)
                ->setCustomTitle('Le midi sans attente')
                ->setShortText('Notre sandwich le plus direct pour les pauses dejeuner qui vont vite.')
                ->setShowPrice(true)
                ->setIsActive(true),
            (new FeaturedItem())
                ->setSiteSettings($siteSettings)
                ->setType(FeaturedItemType::PRODUCT)
                ->setProduct($products['assiette-kefta'])
                ->setPosition(2)
                ->setCustomTitle('Assiette signature')
                ->setShortText('Une assiette genereuse pour ceux qui veulent plus qu\'un sandwich.')
                ->setShowPrice(true)
                ->setIsActive(true),
            (new FeaturedItem())
                ->setSiteSettings($siteSettings)
                ->setType(FeaturedItemType::CATEGORY)
                ->setCategory($categories['menus'])
                ->setPosition(3)
                ->setCustomTitle('Formules du comptoir')
                ->setShortText('Des menus penses pour aller vite tout en gardant une offre complete.')
                ->setShowPrice(false)
                ->setIsActive(true),
            (new FeaturedItem())
                ->setSiteSettings($siteSettings)
                ->setType(FeaturedItemType::CATEGORY)
                ->setCategory($categories['boissons'])
                ->setPosition(4)
                ->setCustomTitle('Fraicheur maison')
                ->setShortText('Citronnade, the glace et boissons fraiches pour accompagner le service du midi.')
                ->setShowPrice(false)
                ->setIsActive(true),
        ];
    }

    private function time(?string $value): ?\DateTimeImmutable
    {
        return null !== $value ? new \DateTimeImmutable($value) : null;
    }
}
