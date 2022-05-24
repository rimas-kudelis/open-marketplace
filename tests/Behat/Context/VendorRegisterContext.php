<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusMultiVendorMarketplacePlugin\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;
use Tests\BitBag\SyliusMultiVendorMarketplacePlugin\Behat\Page\VendorRegisterPage;
use function PHPUnit\Framework\assertEquals;

class VendorRegisterContext extends MinkContext implements Context
{

    private VendorRegisterPage $vendorRegisterPage;

    public function __construct(VendorRegisterPage $vendorRegisterPage)
    {

        $this->vendorRegisterPage = $vendorRegisterPage;
    }
    /**
     * @Then I should see :arg1 :arg2 times
     */
    public function iShouldSeeTimes($arg1, $arg2)
    {  
//        $this->dashboardPage->open();
        $page = $this->getSession()->getPage();
        $validationMessages = $page->findAll('css', '.sylius-validation-error');
        $validationMessageCount = $this->vendorRegisterPage->getValidationMessageCount();
        assertEquals($arg2, $validationMessageCount);        
    }
    
}
