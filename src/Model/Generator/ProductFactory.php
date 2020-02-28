<?php

namespace Jtl\Connector\Core\Model\Generator;

use Jtl\Connector\Core\Definition\IdentityType;
use Jtl\Connector\Core\Model\Manufacturer;
use Jtl\Connector\Core\Model\Product;
use Jtl\Connector\Core\Serializer\SerializerBuilder;

class ProductFactory extends AbstractModelFactory
{
    protected $withSpecialPrices = false;

    protected $withVariations = false;

    protected $isVariationParent = false;

    public function makeOneArray(array $override = []): array
    {
        /** @var IdentityFactory $identityFactory */
        $identityFactory = $this->getFactory('Identity');

        return array_merge([
            'basePriceUnitId' => $identityFactory->makeOneArray(),
            'manufacturerId' => $this->makeIdentity(IdentityType::MANUFACTURER),
            'measurementUnitId' => $identityFactory->makeOneArray(),
            'partsListId' => $identityFactory->makeOneArray(),
            'productTypeId' => $identityFactory->makeOneArray(),
            'shippingClassId' => $identityFactory->makeOneArray(),
            'unitId' => $identityFactory->makeOneArray(),
            'asin' => '',
            'availableFrom' => null,
            //'basePriceDivisor' => 0.0,
            //'basePriceFactor' => 0.0,
            //'basePriceQuantity' => 0.0,
            //'basePriceUnitCode' => '',
            //'basePriceUnitName' => '',
            'considerBasePrice' => false,
            'considerStock' => false,
            'considerVariationStock' => false,
            'creationDate' => $this->dateBetween(sprintf('-%d %s', mt_rand(1, 60), $this->faker->randomKey(['days', 'years', 'hours', 'minutes', 'seconds']))),
            'ean' => $this->faker->ean8,
            'epid' => '',
            'hazardIdNumber' => $this->faker->sha1,
            'height' => $this->faker->randomFloat(),
            'isActive' => $this->faker->boolean,
            'isBatch' => $this->faker->boolean,
            'isBestBefore' => $this->faker->boolean,
            'isbn' => $this->faker->isbn13,
            'isDivisible' => $this->faker->boolean,
            //'isMasterProduct' => false,
            'isNewProduct' => $this->faker->boolean,
            'isSerialNumber' => $this->faker->boolean,
            'isTopProduct' => $this->faker->boolean,
            'keywords' => $this->faker->words(mt_rand(0, 10), true),
            'length' => $this->faker->randomFloat(),
            'manufacturerNumber' => $this->faker->word(),
            //'manufacturer' => null,
            //'measurementQuantity' => 0.0,
            //'measurementUnitCode' => '',
            'minBestBeforeDate' => $this->dateBetween(sprintf('+%d %s', mt_rand(0, 60), $this->faker->randomKey(['days', 'years', 'hours', 'minutes', 'seconds']))),
            'minimumOrderQuantity' => $this->faker->randomFloat(2),
            'minimumQuantity' => $this->faker->randomFloat(2),
            //'modified' => null,
            //'newReleaseDate' => null,
            //'nextAvailableInflowDate' => null,
            //'nextAvailableInflowQuantity' => 0.0,
            'note' => $this->faker->sentence,
            'originCountry' => $this->faker->languageCode,
            //'packagingQuantity' => 0.0,
            'permitNegativeStock' => $this->faker->boolean,
            'productWeight' => $this->faker->randomFloat(4),
            'purchasePrice' => $this->faker->randomFloat(),
            'recommendedRetailPrice' => $this->faker->randomFloat(),
            'serialNumber' => $this->faker->uuid,
            'shippingWeight' => 0.0,
            'sku' => $this->faker->uuid,
            //'sort' => 0,
            'stockLevel' => null,
            'supplierDeliveryTime' => $this->faker->numberBetween(0, 366),
            'supplierStockLevel' => $this->faker->randomFloat(),
            //'taric' => '',
            //'unNumber' => '',
            'upc' => $this->faker->uuid,
            'vat' => $this->faker->randomFloat(2),
            'width' => $this->faker->randomFloat(2),
            'attributes' => [],
            'categories' => [],
            'checksums' => [],
            //'configGroups' => [],
            //'customerGroupPackagingQuantities' => [],
            //'fileDownloads' => [],
            'i18ns' => [],
            'invisibilities' => [],
            'mediaFiles' => [],
            'partsLists' => [],
            //'prices' => $this->getFactory('ProductPrice')->makeOneArray(['customerGroupId' => $this->getFactory('Identity')->makeOneArray([1 => 0])]),
            'specialPrices' => [],
            'specifics' => [],
            //'varCombinations' => [],
            'variations' => [],
            //'warehouseInfo' => [],
        ], $override);
    }

    public function makeOneProductVariantArray(array $i18ns = null)
    {
        $variationsQuantity = 2;
        if (is_null($i18ns)) {
            $i18ns = $this->getFactory('I18n')->makeArray(mt_rand(1, 3));
        }

        $variantsQuantity = 1;
        $variations = [];
        for ($i = 0; $i < $variationsQuantity; $i++) {
            $variationI18ns = array_map(function (array $i18n) {
                return $this->getFactory('ProductVariationI18n')->makeOneArray($i18n);
            }, $i18ns);

            $values = [];
            $valuesQuantity = mt_rand(1, 10);
            $variantsQuantity *= $valuesQuantity;
            for ($j = 0; $j < $valuesQuantity; $j++) {
                $valueI18ns = [];
                foreach ($i18ns as $i18n) {
                    $valueI18ns[] = $this->getFactory('ProductVariationValueI18n')->makeOneArray($i18n);
                }
                $values[] = $this->getFactory('ProductVariationValue')->makeOneArray(['i18ns' => $valueI18ns]);
            }

            $variations[] = $this->getFactory('ProductVariation')->makeOneArray(['i18ns' => $variationI18ns, 'values' => $values]);
        }

        $parentId = $this->getFactory('Identity')->makeOneArray();
        $variants = [$this->makeOneArray(['id' => $parentId, 'isMasterProduct' => true, 'variations' => $variations])];

        foreach ($variations[0]['values'] as $i => $firstValue) {
            foreach ($variations[1]['values'] as $j => $secondValue) {
                $variants[] = $this->makeOneArray([
                    'masterProductId' => $parentId,
                    'isMasterProduct' => false,
                    'variations' => [array_merge($variations[0], ['values' => [$firstValue]]), array_merge($variations[1], ['values' => [$secondValue]])]
                ]);
            }
        }

        return $variants;
    }

    /**
     * @param array|null $i18ns
     * @return mixed
     */
    public function makeOneProductVariant(array $i18ns = null)
    {
        $serializer = SerializerBuilder::getInstance()->build();
        $type = sprintf('array<%s>', Product::class);
        $data = $this->makeOneProductVariantArray($i18ns);
        return $serializer->fromArray($data, $type);
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Product::class;
    }
}