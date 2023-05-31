<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Api\Messenger\CommandHandler\Vendor;

use BitBag\OpenMarketplace\Api\Messenger\Command\Vendor\CreateProductListingInterface;
use BitBag\OpenMarketplace\Api\Messenger\CommandHandler\Vendor\CreateProductListingHandler;
use BitBag\OpenMarketplace\Component\ProductListing\Entity\Draft;
use BitBag\OpenMarketplace\Component\ProductListing\Entity\ListingInterface;
use BitBag\OpenMarketplace\Component\ProductListing\ProductListingAdministrationToolInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;

final class CreateProductListingHandlerSpec extends ObjectBehavior
{
    public function let(
        ProductListingAdministrationToolInterface $productListingFromDraftFactory,
        ObjectManager $manager,
        ImageUploaderInterface $imageUploader
    ): void {
        $this->beConstructedWith($productListingFromDraftFactory, $manager, $imageUploader);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CreateProductListingHandler::class);
    }

    public function it_creates_product_listing(
        CreateProductListingInterface $createProductListing,
        Draft $productDraft,
        VendorInterface $vendor,
        ProductListingAdministrationToolInterface $productListingFromDraftFactory,
        ListingInterface $productListing,
        ImageInterface $image,
        ImageUploaderInterface $imageUploader,
        ObjectManager $manager
    ): void {
        $createProductListing->getProductDraft()->willReturn($productDraft);
        $createProductListing->getVendor()->willReturn($vendor);

        $productDraft->getVendor()->willReturn($vendor);

        $productListingFromDraftFactory->createNewProductListing($productDraft, $vendor)->willReturn($productDraft);

        $images = new ArrayCollection([$image->getWrappedObject()]);
        $productDraft->getImages()->willReturn($images);

        $image->setOwner($productDraft)->shouldBeCalled();
        $imageUploader->upload(Argument::any())->shouldBeCalled();

        $productDraft->getProductListing()->willReturn($productListing);
        $manager->persist($productListing)->shouldBeCalled();

        $this($createProductListing)->shouldReturn($productListing);
    }
}
