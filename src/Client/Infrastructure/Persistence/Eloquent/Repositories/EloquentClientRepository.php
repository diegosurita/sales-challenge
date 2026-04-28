<?php

namespace Module\Client\Infrastructure\Persistence\Eloquent\Repositories;

use Illuminate\Support\Facades\DB;
use Module\Client\Core\Contracts\ClientRepositoryContract;
use Module\Client\Core\DTOs\ClientFormDTO;
use Module\Client\Core\Entities\ClientEntity;
use Module\Client\Infrastructure\Persistence\Eloquent\Models\Client;
use Module\Shared\Core\Exceptions\NotFoundException;

class EloquentClientRepository implements ClientRepositoryContract
{
    public function getAll(): array
    {
        return Client::all()->map(fn (Client $client) => new ClientEntity(
            $client->id,
            $client->name,
        ))->toArray();
    }

    public function createUser(ClientFormDTO $dto): void
    {
        Client::create([
            'name' => $dto->name,
        ]);
    }

    public function getByID(int $id): ClientEntity
    {
        $client = Client::find($id);

        if (! $client) {
            throw new NotFoundException('Client', $id);
        }

        return new ClientEntity(
            $client->id,
            $client->name,
        );
    }

    public function updateClient(ClientFormDTO $dto): void
    {
        $client = Client::find($dto->id);

        if (! $client) {
            throw new NotFoundException('Client', $dto->id);
        }

        $client->update([
            'name' => $dto->name,
        ]);
    }

    public function delete(int $id): void
    {
        $client = Client::find($id);

        if (! $client) {
            throw new NotFoundException('Client', $id);
        }

        $client->delete();
    }

    /**
     * @return array<int, array{name: string, total_sales_value: float}>
     */
    public function getTopClientsBySalesValue(int $limit): array
    {
        $saleProductTotalsBySale = DB::table('sale_products')
            ->select([
                'sale_products.sale_id',
                DB::raw('SUM(sale_products.price * sale_products.quantity) as product_total'),
            ])
            ->groupBy('sale_products.sale_id');

        $saleServiceTotalsBySale = DB::table('sale_services')
            ->select([
                'sale_services.sale_id',
                DB::raw('SUM(sale_services.price) as service_total'),
            ])
            ->groupBy('sale_services.sale_id');

        return DB::table('sales')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->leftJoinSub($saleProductTotalsBySale, 'sale_product_totals', function ($join): void {
                $join->on('sales.id', '=', 'sale_product_totals.sale_id');
            })
            ->leftJoinSub($saleServiceTotalsBySale, 'sale_service_totals', function ($join): void {
                $join->on('sales.id', '=', 'sale_service_totals.sale_id');
            })
            ->select([
                'clients.name',
                DB::raw('SUM(COALESCE(sale_product_totals.product_total, 0) + COALESCE(sale_service_totals.service_total, 0)) as total_sales_value'),
            ])
            ->groupBy('clients.id', 'clients.name')
            ->orderByDesc('total_sales_value')
            ->limit($limit)
            ->get()
            ->map(fn (object $row): array => [
                'name' => (string) $row->name,
                'total_sales_value' => (float) $row->total_sales_value,
            ])
            ->toArray();
    }
}
