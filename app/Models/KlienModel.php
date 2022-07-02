<?php

namespace App\Models;

use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class KlienModel extends Model
{
    use SoftDeletes;

    protected $table = 'klien';
    protected $dates = ['deleted_at'];

    public function companies()
    {
        return $this->hasMany(PerusahaanModel::class, 'id_klien');
    }

    public function employees()
    {
        return $this->hasMany(KaryawanModel::class, 'id_klien');
    }

    public function storeClient(array $requestedData, int $adminId) : int
    {
        return $this->insertGetId(
            [
                'nama_lengkap' => $requestedData['nama_lengkap'],
                'phone' => $requestedData['phone'],
                'email' => $requestedData['email'],
                'bagian' => $requestedData['bagian'],
                'budget' => $requestedData['budget'],
                'lokasi' => $requestedData['lokasi'],
                'zip' => $requestedData['zip'],
                'kota' => $requestedData['kota'],
                'negara' => $requestedData['negara'],
                'created_at' => date('Y-m-d'),
                'is_active' => true,
                'dibuat_oleh' => $adminId
            ]
        );
    }

    public function updateClient(int $id, array $requestedData, int $adminId) : int
    {
        return $this->where('id', '=', $id)->update(
            [
                'nama_lengkap' => $requestedData['nama_lengkap'],
                'phone' => $requestedData['phone'],
                'email' => $requestedData['email'],
                'bagian' => $requestedData['bagian'],
                'budget' => $requestedData['budget'],
                'lokasi' => $requestedData['lokasi'],
                'zip' => $requestedData['zip'],
                'kota' => $requestedData['kota'],
                'negara' => $requestedData['negara'],
                'updated_at' => date('Y-m-d'),
                'diedit_oleh' => $adminId
            ]
        );
    }

    public function setClientActive(int $id, int $activeType) : int
    {
        return $this->where('id', '=', $id)->update(
            [
                'is_active' => $activeType,
                'updated_at' => date('Y-m-d')
            ]
        );
    }

    public function countClients() : int
    {
        return $this->all()->count();
    }

    public static function getClientsInLatestMonth() : float
    {
        $clientCount = self::where('created_at', '>=', Carbon::now()->subMonth())->count();
        $allClient = self::all()->count();

        return ($allClient / 100) * $clientCount;
    }

    public function getDeactivated() : int
    {
        return $this->where('is_active', '=', 0)->count();
    }

    public function getClientByGivenClientId(int $clientId) : self
    {
        $query = $this->find($clientId);

        if(is_null($query)) {
            throw new BadRequestHttpException('User with given clientId not exists.');
        }

        Arr::add($query, 'companiesCount', count($query->companies));
        Arr::add($query, 'employeesCount', count($query->employees));
        Arr::add($query, 'formattedBudget', Money::{SettingModel::getSettingValue('currency')}($query->budget . '00'));

        return $query;
    }

    public function getClientSortedBy()
    {
        $query = $this->all()->sortBy('created_at');

        foreach($query as $key => $client) {
            $query[$key]->budget = Money::{SettingModel::getSettingValue('currency')}($client->budget . '00');
        }
        
        return $query;
    }

    public function getPaginate()
    {
        $query = $this;
        
        if (request()->q != '') {
            $query = $query->where('nama_lengkap', 'LIKE', '%' . request()->q . '%');
        }

        $query = $query->paginate(SettingModel::getSettingValue('pagination_size'));

        foreach($query as $key => $client) {
            $query[$key]->budget = Money::{SettingModel::getSettingValue('currency')}($client->budget . '00');
        }

        return $query;
    }

    public function getKlien()
    {
        return $this->select('nama_lengkap', 'id')->get();
    }

    public function deleteClient(int $clientId, int $adminId)
    {
        $this->where('id', '=', $clientId)->update([
            'dihapus_oleh' => $adminId
        ]);

        return $this->where('id', $clientId)->delete();
    }
}
