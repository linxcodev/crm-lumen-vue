<?php

namespace App\Services;

use App\Models\KeuanganModel;
use App\Models\ProdukModel;
use App\Models\PenjualanModel;
use App\Models\TaskModel;
use Carbon\Carbon;
use Cknow\Money\Money;
use Illuminate\Support\Facades\DB;

class CalculateCashService
{
    private SettingService $settingsService;

    public function __construct()
    {
        $this->settingsService = new SettingService();
    }

    /**
     * @return
     */
    public function loadCountCashTurnover()
    {
        $products = ProdukModel::all();
        $sales = PenjualanModel::all();
        $finances = KeuanganModel::all();

        $productSum = 0;
        $salesSum = 0;
        $financesSum = 0;

        foreach($products as $product) {
            $productSum += $product->price * $product->count;
        }

        foreach($finances as $finance) {
            $financesSum += $finance->net;
        }

        foreach($sales as $sale) {
            $salesSum += $sale->price * $sale->quantity;
        }

        $officialSum = $productSum + $salesSum + $financesSum;

        return Money::{$this->settingsService->loadSettingValue('currency')}($officialSum);
    }

    /**
     * @return
     */
    public function loadCountTodayIncome()
    {
        $products = ProdukModel::whereDate('created_at', Carbon::today())->get();
        $sales = PenjualanModel::whereDate('created_at', Carbon::today())->get();
        $finances = KeuanganModel::whereDate('created_at', Carbon::today())->get();
        $productSum = 0;
        $salesSum = 0;
        $financesSum = 0;

        foreach($products as $product) {
            $productSum += $product->price * $product->count;
        }

        foreach($sales as $sale) {
            $salesSum += $sale->price * $sale->quantity;
        }
        foreach($finances as $finance) {
            $financesSum += $finance->net;
        }

        $todayIncome = $productSum + $salesSum + $financesSum;

        return Money::{$this->settingsService->loadSettingValue('currency')}($todayIncome);
    }

    /**
     * @return
     */
    public function loadCountYesterdayIncome()
    {
        $products = ProdukModel::whereDate('created_at', Carbon::yesterday())->get();
        $sales = PenjualanModel::whereDate('created_at', Carbon::yesterday())->get();
        $finances = KeuanganModel::whereDate('created_at', Carbon::yesterday())->get();
        $salesSum = 0;
        $productSum = 0;
        $financesSum = 0;

        foreach($products as $product) {
            $productSum += $product->price * $product->count;
            foreach($sales as $sale) {
                $salesSum += $product->price * $sale->quantity;
            }
            foreach($finances as $finance) {
                $financesSum += $finance->net;
            }
        }

        $yesterdayIncome = $productSum + $salesSum + $financesSum;

        return Money::{$this->settingsService->loadSettingValue('currency')}($yesterdayIncome);
    }

    /**
     * @return int
     */
    public function loadCountAllRowsInDb(): int
    {
        $counter = 0;
        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
            $counter += DB::table($table->Tables_in_b_softcrm)->count();
        }

        return $counter;
    }

    public function loadTaskEveryMonth($isCompleted) {

        $hari = [];
        $dates = collect();
        foreach( range( -6, 0 ) AS $i ) {
            $date = Carbon::now()->addDays( $i )->format( 'Y-m-d' );
            $hari[] = Carbon::now()->addDays( $i )->format( 'w' );
            $dates->put( $date, 0);
        }

        if($isCompleted) {
            $posts = TaskModel::where( 'created_at', '>=', $dates->keys()->first() )->where('completed', '=', 1)
                ->groupBy( 'date' )
                ->orderBy( 'date' )
                ->get( [
                    DB::raw( 'DATE( created_at ) as date' ),
                    DB::raw( 'COUNT( * ) as "count"' )
                ] )
                ->pluck( 'count', 'date' );
        } else {
            $posts = TaskModel::where( 'created_at', '>=', $dates->keys()->first() )->where('completed', '=', 0)
                ->groupBy( 'date' )
                ->orderBy( 'date' )
                ->get( [
                    DB::raw( 'DATE( created_at ) as date' ),
                    DB::raw( 'COUNT( * ) as "count"' )
                ] )
                ->pluck( 'count', 'date' );
        }

        $dates = $dates->merge($posts)->toArray();

        return [ 
            'dates' => array_values($dates),
            'hari' => $hari,
        ];
    }
}
