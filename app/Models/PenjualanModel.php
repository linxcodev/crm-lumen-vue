<?php

namespace App\Models;

use Cknow\Money\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenjualanModel extends Model
{
    use SoftDeletes;

    protected $table = 'penjualan';
    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->belongsTo(ProdukModel::class, 'id_produk');
    }

    public function storeSale(array $requestedData, int $adminId) : int
    {
        return $this->insertGetId(
            [
                'nama' => $requestedData['nama'],
                'quantity' => $requestedData['quantity'],
                'tangal_pembayaran' => $requestedData['tangal_pembayaran'],
                'id_produk' => $requestedData['id_produk'],
                'harga' => $requestedData['harga'],
                'created_at' => date('Y-m-d'),
                'is_active' => true,
                'dibuat_oleh' => $adminId
            ]
        );
    }

    public function updateSale(int $saleId, array $requestedData, $adminId) : int
    {
        return $this->where('id', '=', $saleId)->update(
            [
                'nama' => $requestedData['nama'],
                'quantity' => $requestedData['quantity'],
                'tangal_pembayaran' => $requestedData['tangal_pembayaran'],
                'id_produk' => $requestedData['id_produk'],
                'harga' => $requestedData['harga'],
                'updated_at' => date('Y-m-d'),
                'diedit_oleh' => $adminId
            ]
        );
    }

    public function setActive(int $saleId, int $activeType) : int
    {
        return $this->where('id', '=', $saleId)->update(
            [
                'is_active' => $activeType,
                'updated_at' => date('Y-m-d')
            ]
        );
    }

    public function countSales() : int
    {
        return $this->all()->count();
    }

    public function getPaginate()
    {
        $query = $this->with('products');

        if (request()->q != '') {
            $query = $query->where('nama', 'LIKE', '%' . request()->q . '%');
        }

        $query = $query->paginate(SettingModel::getSettingValue('pagination_size'));

        foreach($query as $key => $dt) {
            $query[$key]->harga = Money::{SettingModel::getSettingValue('currency')}($dt->harga . '00');
        }
        
        return $query;
    }

    public function getSalesSortedByCreatedAt()
    {
        return $this->all()->sortBy('created_at');
    }

    public function getSale(int $saleId) : self
    {
        return $this->with('products')->find($saleId);
    }
}
