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
     * @throws ServiceRequiredProductOutOfStockException
     */
    public function execute(SaleFormDTO $dto): void
    {
        $productItems = $this->resolveProducts(clientId: $dto->clientId, products: $dto->products);
        $serviceItems = $this->resolveServices(services: $dto->services);

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

        $ids = array_map(fn (SaleFormProductItemDTO $p) => $p->productId, $products);
        $infos = $this->productQueryService->getProductsInfo($ids);

        $items = [];

        foreach ($products as $product) {
            $info = $infos[$product->productId] ?? null;

            if ($info === null) {
                continue;
            }

            $stockCount = $info['stock_count'] ?? 0;

            if ($stockCount < 1) {
                throw new InsufficientStockException($info['name']);
            }

            $clientIds = $this->saleRepository->getDistinctClientIdsForProductToday($product->productId);

            if (count($clientIds) >= 3 && !in_array($clientId, $clientIds, strict: true)) {
                throw new ProductClientLimitExceededException($info['name']);
            }

            $items[] = new SaleCreateProductItemDTO(
                productId: $product->productId,
                price: (float) $info['price'],
            );
        }

        return $items;
    }

    /**
     * @param  SaleFormServiceItemDTO[]  $services
     * @return SaleCreateServiceItemDTO[]
     *
     * @throws ServiceNotAvailableException
     * @throws ServiceRequiredProductOutOfStockException
     */
    private function resolveServices(array $services): array
    {
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

            if (!$info['available']) {
                throw new ServiceNotAvailableException($info['name']);
            }

            if (isset($info['product'])) {
                $depProduct = $depProductInfos[$info['product']['id']] ?? null;
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
