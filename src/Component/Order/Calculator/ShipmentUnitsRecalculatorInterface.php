<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Component\Order\Calculator;

use BitBag\OpenMarketplace\Component\Order\Entity\OrderInterface;

interface ShipmentUnitsRecalculatorInterface
{
    public function recalculateShipmentUnits(OrderInterface $order): void;
}
