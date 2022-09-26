<?php

declare(strict_types=1);

use App\Services\Shipping\BillableWeightCalculatorService;
use App\Services\Shipping\DimDivisor;
use App\Services\Shipping\PackageDimension;
use App\Services\Shipping\Weight;

require_once __DIR__ . '/../vendor/autoload.php';

$package = [
    'weight'    => 6,
    'dimensions' => [
        'width'  => 9,
        'length' => 15,
        'height' => 7,
    ],
];

$packageDimension = new PackageDimension(
    $package['dimensions']['width'],
    $package['dimensions']['height'],
    $package['dimensions']['length'],
);

$weight = new Weight($package['weight']);

$billableWeightService = new BillableWeightCalculatorService();

$widerPackageDimensions = $packageDimension->increaseWidth(10);

$billableWeight = $billableWeightService->calculate(
    $packageDimension,
    $weight,
    DimDivisor::FEDEX,
);

$widerPackageBillableWieght = $billableWeightService->calculate(
    $widerPackageDimensions,
    $weight,
    DimDivisor::FEDEX,
);

echo $billableWeight . ' lb' . PHP_EOL;
echo $widerPackageBillableWieght . ' lb' . PHP_EOL;
