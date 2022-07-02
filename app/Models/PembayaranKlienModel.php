<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PembayaranKlienModel extends Model
{
    protected $table = 'pembayaran_klien';

    public function klien()
    {
        return $this->belongsTo(KlienModel::class, 'id_klien');
    }

    public function tipePembayaran()
    {
        return $this->belongsTo(KategoriModel::class, 'tipe_pembayaran', 'key');
    }

    public function kategoriPembayaran()
    {
        return $this->belongsTo(KategoriModel::class, 'tipe_pembayaran', 'key');
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d');
    }

    public function store(array $requestedData, int $adminId) : int
    {
        return $this->insertGetId(
            [
                'dibuat_oleh' => $adminId,
                'invoice' => $this->invoice(),
                'created_at' => date('Y-m-d'),
                'nominal' => $requestedData['nominal'],
                'catatan' => $requestedData['catatan'],
                'id_klien' => $requestedData['id_klien'],
                'tipe_pembayaran' => $requestedData['tipe_pembayaran'],
            ]
        );
    }

    public function updatePembayaran(int $id, array $requestedData) : int
    {
        return $this->where('id', $id)->update(
            [
                'updated_at' => date('Y-m-d'),
                'nominal' => $requestedData['nominal'],
                'catatan' => $requestedData['catatan'],
                'id_klien' => $requestedData['id_klien'],
                'tipe_pembayaran' => $requestedData['tipe_pembayaran'],
            ]
        );
    }

    public function getPaginate()
    {
        $query = $this->with('klien', 'tipePembayaran');
        $search = request()->q;

        if ($search != '') {
            $query = $query->where('tipe_pembayaran', $search);
        }

        $query = $query->orderBy('created_at', 'DESC')->paginate(SettingModel::getSettingValue('pagination_size'));

        return $query;
    }

    public function invoice()
    {
        $zero = '000';
        $num = $this->whereDate('created_at', Carbon::today())->count();

        if (strlen($num) > 1) {
            $zero = '00';
        }
        
        return date('Ymd') . $zero . $num + 1;
    }
}
