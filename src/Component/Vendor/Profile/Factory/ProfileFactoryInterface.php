<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Component\Vendor\Profile\Factory;

use BitBag\OpenMarketplace\Component\Vendor\Entity\AddressInterface;
use BitBag\OpenMarketplace\Component\Vendor\Entity\ProfileInterface;

interface ProfileFactoryInterface
{
    public function createVendor(
        string $companyName,
        string $taxIdentifier,
        string $bankAccountNumber,
        string $phoneNumber,
        string $description,
        AddressInterface $address
    ): ProfileInterface;

    public function createNew(): ProfileInterface;
}
