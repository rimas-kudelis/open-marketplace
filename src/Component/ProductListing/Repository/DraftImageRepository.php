<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Component\ProductListing\Repository;

use BitBag\OpenMarketplace\Component\ProductListing\Entity\DraftInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

final class DraftImageRepository extends EntityRepository implements DraftImageRepositoryInterface
{
    public function findVendorDraftImages(DraftInterface $draft): array
    {
        $draftId = $draft->getId();

        $queryBuilder = $this->createQueryBuilder('o')
            ->andWhere('o.owner = :draft')
            ->setParameter('draft', $draftId);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
