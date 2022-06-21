<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\VendorProfileUpdater;

use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorAddressUpdate;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorProfileInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorProfileUpdate;
use BitBag\SyliusMultiVendorMarketplacePlugin\Factory\VendorProfileUpdateFactoryInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\VendorProfileUpdateRemover\RemoverInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

class VendorProfileUpdater implements VendorProfileUpdaterInterface
{
    private EntityManagerInterface $entityManager;

    private SenderInterface $sender;

    private RemoverInterface $remover;

    private VendorProfileUpdateFactoryInterface $profileUpdateFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        SenderInterface $sender,
        RemoverInterface $remover,
        VendorProfileUpdateFactoryInterface $profileUpdateFactory
    ) {
        $this->entityManager = $entityManager;
        $this->sender = $sender;
        $this->remover = $remover;
        $this->profileUpdateFactory = $profileUpdateFactory;
    }

    public function createPendingVendorProfileUpdate(VendorProfileInterface $vendorData, VendorInterface $currentVendor): void
    {
        $token = $this->generateToken();
        $pendingVendorUpdate = $this->profileUpdateFactory->createVendorUpdateInformationWithTokenAndVendor($token, $currentVendor);

        $this->setVendorFromData($pendingVendorUpdate, $vendorData);

        $user = $currentVendor->getShopUser();
        if (null == $user) {
            return;
        }
        $email = $user->getUsername();
        if (null == $email) {
            return;
        }
        $this->sender->send('vendor_profile_update', [$email], ['token' => $token]);
    }

    public function generateToken(): string
    {
        return md5(mt_rand(1, 90000) . 'SALT');
    }

    public function setVendorFromData(VendorProfileInterface $vendor, VendorProfileInterface $data): void
    {
        $vendor->setCompanyName($data->getCompanyName());
        $vendor->setTaxIdentifier($data->getTaxIdentifier());
        $vendor->setPhoneNumber($data->getPhoneNumber());
        $newVendorAddress = $data->getVendorAddress();

        if (null == $newVendorAddress) {
            return;
        }
        if (null !== $vendor->getVendorAddress()) {
            $vendor->getVendorAddress()->setCity($newVendorAddress->getCity());
            $vendor->getVendorAddress()->setCountry($newVendorAddress->getCountry());
            $vendor->getVendorAddress()->setPostalCode($newVendorAddress->getPostalCode());
            $vendor->getVendorAddress()->setStreet($newVendorAddress->getStreet());
        }
        $this->entityManager->persist($vendor);
        $this->entityManager->flush();
    }

    public function updateVendorFromPendingData(VendorProfileInterface $vendorData): void
    {
        $vendor = $vendorData->getVendor();
        if (null == $vendor) {
            return;
        }
        $this->setVendorFromData($vendor, $vendorData);
        $this->remover->removePendingData($vendorData);
    }
}
