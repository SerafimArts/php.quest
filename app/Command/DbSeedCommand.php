<?php

declare(strict_types=1);

namespace App\Command;

use App\Domain\Documentation\Category;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'db:seed')]
class DbSeedCommand extends Command
{
    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create();

        for ($i = 0; $i < \random_int(1, 5); ++$i) {
            $category = new Category(\ucfirst($faker->words(\random_int(1, 5), true)));

            for ($j = 0; $j < \random_int(1, 10); ++$j) {
                $title = \ucfirst($faker->words(\random_int(1, 5), true));

                $page = new Documentation($category, $title, file_get_contents(__DIR__ . '/test.md'));

                $this->em->persist($page);
            }

            $this->em->persist($category);
        }

        $this->em->flush();

        return self::SUCCESS;
    }
}
