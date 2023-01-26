<?php

namespace App\Http\Controllers;
use App\Models\Currency;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function fetch(){
        $response = Http::get('http://api.nbp.pl/api/exchangerates/tables/A/');
        $tables= json_decode($response->body());
        $table= $tables[0];
        foreach($table->rates as $data){
            $currency = new Currency;
            $currency->name = $data->currency;
            $currency->exchange_rate = $data->mid;
            $currency->currency_code = $data->code;
            if(empty($record = Currency::where('currency_code', $currency->currency_code))){
            $currency->save();}
            else{$record->update(['exchange_rate'=>$currency->exchange_rate]);}
        }
        return "DONE";
    }
}
