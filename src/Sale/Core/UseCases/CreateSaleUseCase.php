<?php

namespace Module\Sale\Core\UseCases;

use Module\Sale\Core\Contracts\SaleRepositoryContract;
use Module\Sale\Core\DTOs\SaleCreateProductItemDTO;
use Module\Sale\Core\DTOs\SaleCreateServiceItemDTO;
use Module\Sale\Core\DTOs\SaleFormDTO;
use Module\Sale\Core\DTOs\SaleFormProductItemDTO;
use Module\Sale\Core\DTOs\SaleFormServiceItemDTO;
use Module\Sale\Core\Exceptions\InsufficientStockException;
use Module\Sale\Core\Exceptions\ProductClientLimitExceededException;
use Module\Sale\Core\Exceptions\ServiceNotAvailableException;
use Module\Sale\Core\Exceptions\ServiceRequiredProductMissingFromSaleException;
use Module\Sale\Core\Exceptions\ServiceRequiredProductOutOfStockException;
use Module\Shared\Core\Contracts\ProductQueryServiceContract;
use Module\Shared\Core\Contracts\ServiceQueryServiceContract;

class CreateSaleUseCase
{
    public function __construct(
        private readonly SaleRepositoryContract $saleRepository,
        private readonly ProductQueryServiceContract $productQueryService,
        private readonly ServiceQueryServiceContract $serviceQueryService,
    ) {}

    /**
     * @throws InsufficientStockException
     * @throws ProductClientLimitExceededException
     * @throws ServiceNotAvailableException
     * @throws ServiceRequiredProductMissingFromSaleException
     * @throws ServiceRequiredProductOutOfStockException
     */
    public function execute(SaleFormDTO $dto): void
    {
        $productItems = $this->resolveProducts(clientId: $dto->clientId, products: $dto->products);
        $serviceItems = $this->resolveServices(products: $dto->products, services: $dto->services);

        $this->saleRepository->createSale(
            clientId: $dto->clientId,
            products: $productItems,
            services: $serviceItems,
        );
    }

    /**
     * @param  SaleFormProductItemDTO[]  $products
     * @return SaleCreateProductItemDTO[]
     *
     * @throws InsufficientStockException
     * @throws ProductClientLimitExceededException
     */
    private function resolveProducts(int $clientId, array $products): array
    {
        if ($products === []) {
            return [];
        }

        $requestedQuantitiesByProductId = [];

        foreach ($products as $product) {
            $requestedQuantitiesByProductId[$product->productId] =
                ($requestedQuantitiesByProductId[$product->productId] ?? 0) + $product->quantity;
        }

        $ids = array_keys($requestedQuantitiesByProductId);
        $infos = $this->productQueryService->getProductsInfo($ids);

        $items = [];

        foreach ($requestedQuantitiesByProductId as $productId => $requestedQuantity) {
            $info = $infos[$productId] ?? null;

            if ($info === null) {
                continue;
            }

            $stockCount = $info['stock_count'] ?? 0;

            if ($stockCount < $requestedQuantity) {
                throw new InsufficientStockException($info['name']);
            }

            $clientIds = $this->saleRepository->getDistinctClientIdsForProductToday($productId);

            if (count($clientIds) >= 3 && ! in_array($clientId, $clientIds, strict: true)) {
                throw new ProductClientLimitExceededException($info['name']);
            }

            $items[] = new SaleCreateProductItemDTO(
                productId: $productId,
                price: (float) $info['price'],
                quantity: $requestedQuantity,
            );
        }

        return $items;
    }

    /**
     * @param  SaleFormProductItemDTO[]  $products
     * @param  SaleFormServiceItemDTO[]  $services
     * @return SaleCreateServiceItemDTO[]
     *
     * @throws ServiceNotAvailableException
     * @throws ServiceRequiredProductMissingFromSaleException
     * @throws ServiceRequiredProductOutOfStockException
     */
    private function resolveServices(array $products, array $services): array
    {
        $saleProductIds = array_map(fn (SaleFormProductItemDTO $product) => $product->productId, $products);
        if ($services === []) {
            return [];
        }

        $ids = array_map(fn (SaleFormServiceItemDTO $s) => $s->serviceId, $services);
        $infos = $this->serviceQueryService->getServicesInfo($ids);

        $depProductIds = array_values(
            array_filter(
                array_unique(
                    array_map(fn (array $info) => $info['product']['id'] ?? null, $infos),
                ),
                fn (mixed $id) => $id !== null
            )
        );

        $depProductInfos = $depProductIds !== []
            ? $this->productQueryService->getProductsInfo($depProductIds)
            : [];

        $items = [];

        foreach ($services as $service) {
            $info = $infos[$service->serviceId] ?? null;

            if ($info === null) {
                continue;
            }

            if (! $info['available']) {
                throw new ServiceNotAvailableException($info['name']);
            }

            if (isset($info['product'])) {
                $requiredProductId = $info['product']['id'];

                if (! in_array($requiredProductId, $saleProductIds, strict: true)) {
                    throw new ServiceRequiredProductMissingFromSaleException(
                        serviceName: $info['name'],
                        productName: $info['product']['name'],
                    );
                }

                $depProduct = $depProductInfos[$requiredProductId] ?? null;
                $stockCount = $depProduct['stock_count'] ?? 0;

                if ($stockCount < 1) {
                    throw new ServiceRequiredProductOutOfStockException(
                        serviceName: $info['name'],
                        productName: $info['product']['name'],
                    );
                }
            }

            $items[] = new SaleCreateServiceItemDTO(
                serviceId: $service->serviceId,
                price: (float) $info['price'],
            );
        }

        return $items;
    }
}
