<?php

namespace App\Http\Controllers;
use App\Models\Currency;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;


class CurrencyController extends Controller
{
    public function fetch(){
        try {
        $response = Http::get('http://api.nbp.pl/api/exchangerates/tables/A/');
        $tables= json_decode($response->body());
        $table= $tables[0];
        foreach($table->rates as $data){
            $currency = new Currency;
            $currency->name = $data->currency;
            $currency->exchange_rate = $data->mid;
            $currency->currency_code = $data->code;
            $record=Currency::where('currency_code', $currency->currency_code);
            if($record->exists()) {
                $record->update(['exchange_rate'=>$currency->exchange_rate]);}
            else {
                $currency->save();}
            
        }} catch(\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
        

        return "DATABASE HAS BEEN UPDATED WITH NEW EXCHANGE RATES";
    }
    public function calculate(Request $request){
        $this->fetch();
        $data=['id_from'=>Currency::find($request->get('currency_from'))->id,
        'id_to'=>Currency::find($request->get('currency_to'))->id,];
        $result= (Currency::find($request->get('currency_from'))->exchange_rate) * $request->get('amount') / (Currency::find($request->get('currency_to'))->exchange_rate);
        $string=$request->get('amount')." ".Currency::find($request->get('currency_from'))->currency_code." = ".round($result,3)." ".Currency::find($request->get('currency_to'))->currency_code;
return view('welcome', compact('string', 'data'));
    }
}
