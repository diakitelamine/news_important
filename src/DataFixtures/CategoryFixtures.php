<?php

namespace App\DataFixtures;

use App\Entity\Category;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture

{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [
            'Politique',
            'Société',
            'Sport',
            'Cinéma',
            'Mode',
            'Santé',
            'Hi-Tech',
            'Économie',
            'Sciences',
            'Environnement',
        ];

        foreach ($categories as $cat) {

            $category = new Category();
            
            $category->setName($cat);
            $category->setAlias($this->slugger->slug($cat));

            $manager->persist($category);
        }

        $manager->flush();
    }
}
