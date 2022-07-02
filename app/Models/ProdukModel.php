<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class ProdukModel extends Model
{
    use SoftDeletes;

    protected $table = 'produk';
    protected $dates = ['deleted_at'];

    public function sales()
    {
        return $this->hasMany(PenjualanModel::class, 'id');
    }

    public function storeProduct(array $requestedData, int $adminId) : int
    {
        return $this->insertGetId(
            [
                'nama' => $requestedData['nama'],
                'kategori' => $requestedData['kategori'],
                'stok' => $requestedData['stok'],
                'harga' => $requestedData['harga'],
                'created_at' => date('Y-m-d'),
                'is_active' => true,
                'dibuat_oleh' => $adminId
            ]
        );
    }

    public function updateProduct(int $productId, array $requestedData, int $adminId) : int
    {
        return $this->where('id', '=', $productId)->update(
            [
                'nama' => $requestedData['nama'],
                'kategori' => $requestedData['kategori'],
                'stok' => $requestedData['stok'],
                'harga' => $requestedData['harga'],
                'updated_at' => date('Y-m-d'),
                'diedit_oleh' => $adminId
            ]
        );
    }

    public function setActive(int $productId, int $activeType) : int
    {
        return $this->where('id', '=', $productId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => date('Y-m-d')
            ]
        );
    }

    public function countProducts() : int
    {
        return $this->get()->count();
    }

    public function getProductsByCreatedAt()
    {
        $arrayWithFormattedProduk = [];
        $query = $this->all()->sortBy('created_at', 0, true)->slice(0, 5);

        foreach($query as $key => $produk) {
            $arrayWithFormattedProduk[$key] = [
                'id' => $produk->id,
                'nama' => $produk->nama,
                'stok' => $produk->stok,
                'created_at' => $produk->created_at->diffForHumans()
            ];
        }

        return $arrayWithFormattedProduk;
    }

    public function findClientByGivenClientId(int $productId)
    {
        $query = $this->find($productId);

        Arr::add($query, 'salesCount', count($query->sales));

        return $query;
    }

    public function getProducts()
    {
        return $this->all()->sortBy('created_at');
    }

    public function getPaginate()
    {
        $query = $this;

        if (request()->q != '') {
            $query = $query->where('nama', 'LIKE', '%' . request()->q . '%');
        }

        $query = $query->paginate(SettingModel::getSettingValue('pagination_size'));

        foreach($query as $key => $produk) {
            $query[$key]->harga = Money::{SettingModel::getSettingValue('currency')}($produk->harga . '00');
        }
        
        return $query;
    }

    public function getProduct(int $productId) : self
    {
        return $this->find($productId);
    }
}
