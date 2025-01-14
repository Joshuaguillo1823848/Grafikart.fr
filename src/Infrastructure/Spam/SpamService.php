<?php

namespace App\Infrastructure\Spam;

use App\Helper\OptionManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class SpamService
{
    public function __construct(
        private readonly iterable $entities,
        private readonly EntityManagerInterface $em,
        private readonly OptionManagerInterface $optionManager
    ) {
    }

    public function count(): int
    {
        $count = 0;
        /** @var class-string $entity */
        foreach ($this->entities as $entity) {
            /** @var EntityRepository $repository */
            $repository = $this->em->getRepository($entity);
            $count += $repository->count(['spam' => true]);
        }

        return $count;
    }

    /**
     * Renvoie la liste des mots constituant du spam.
     *
     * @return string[]
     */
    public function words(): array
    {
        $spamWords = preg_split('/\r\n|\r|\n/', $this->optionManager->get('spam_words') ?: '');

        return is_array($spamWords) ? $spamWords : [];
    }
}
