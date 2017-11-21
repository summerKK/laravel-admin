<?php

namespace App\Admin\Extensions;

use Illuminate\Support\Facades\Cache;
use Mockery\CountValidator\Exception;

class Currency{

    /**
     * @var string apiKey
     */
    private $apiKey = [
        'af14fe34a41091c36334ca8259389c9b',
        '9f56e1aec6408c36a6f6b1bd30dd13a3',
        'b7b0239abb5d07a39ec788dcd911b1e5',
        'c33df95142d9a48b14388ff6c28a3e45',
        'ddb0fe8bc7f99a3b437bc374b9496f49',
        'e90d64ac77c7fc3a83e47488aff49ccb',
    ];
    /**
     * @var string cachePrefix
     */
    private $cachePrefix = 'JustForFrontCurrency';

    /**
     * @var array currencyCodeAlt
     */
    protected $currencyCodeAlt = ["CNY"=>"RMB"];

    /**
     * @var string BaseCurrency
     */
    public $BaseCurrency = "USD";

    /**
     * @var string oldBaseCurrency
     */
    private $oldBaseCurrency = '';

    /**
     * @var array currencies
     */
    protected $currencies;


    /**
     * @var array ratioList
     */
    protected $ratioList;


    /**
     * @var float usdRatio
     */
    protected $usdRatio = 1;

    /**
     * Currency constructor.
     * @param string $baseCode
     */
    public function __construct($baseCode=''){

        $list = Cache::get($this->cachePrefix.'_currencies');
        $this->currencies = $list?explode(",",$list):["USD"];

        $list = Cache::get($this->cachePrefix.'_currencies_ratio');
        $this->ratioList = $list?json_decode($list,true):["USD"=>1];

        if($baseCode){

            $this->SetBaseCurrency($baseCode);

        }

    }

    /**
     * Currency constructor.
     * @param string $baseCode
     */
    public function SetBaseCurrency($currencyCode){

        $currencyCode = strtoupper($currencyCode);

        if(!in_array($currencyCode,$this->currencies)){

            $this->addToCurrencies($currencyCode);

        }

        $this->BaseCurrency = $currencyCode;

    }

    /**
     * Currency constructor.
     * @param string $baseCode
     */
    public function addToCurrencies($currencyCode){


        $this->currencies[] = $currencyCode;

        $code = array_search($currencyCode,$this->currencyCodeAlt,true);

        if($code!==false){

            $this->currencies[] = $code;

        }

        Cache::put($this->cachePrefix.'_currencies',join(",",$this->currencies));

    }

    /**
     * @param string $currencyCode
     * @param float $amount
     * @return float
     */
    public function To($currencyCode,$amount){

        return $this->Exchange($this->BaseCurrency,$currencyCode,$amount);

    }

    /**
     * @param string $baseCurrencyCode
     * @param string $targetCurrencyCode
     * @param float $amount
     * @return float
     */
    public function Exchange($baseCurrencyCode,$targetCurrencyCode,$amount){

        $baseCurrencyCode = strtoupper($baseCurrencyCode);
        $targetCurrencyCode = strtoupper($targetCurrencyCode);


        if($baseCurrencyCode!=$this->BaseCurrency){

            $this->oldBaseCurrency = $this->BaseCurrency;

            $this->SetBaseCurrency($baseCurrencyCode);

        }

        if(!in_array($targetCurrencyCode,$this->currencies)){

            $this->addToCurrencies($targetCurrencyCode);

        }

        $ratio = $this->GetRatio($targetCurrencyCode);

        if($this->oldBaseCurrency ){

            $this->BaseCurrency = $this->oldBaseCurrency;
            $this->oldBaseCurrency = '';

        }

        return $amount*$ratio;

    }

    /**
     * @param string $currencyCode
     * @return float
     */
    public function GetRatio($currencyCode){

        $ratioList = $this->ratioList;

        if(!isset($ratioList[$this->BaseCurrency])||!isset($ratioList[$currencyCode])){

            $this->LoadSrc();
            $ratioList = $this->ratioList;

        }


        $baseRatio = $ratioList[$this->BaseCurrency];
        $ratio = $ratioList[$currencyCode];

        return $ratio/$baseRatio;

    }

    public function LoadSrc(){

        try{

//            $rawTxt = join("",file("http://www.apilayer.net/api/live?access_key=".$this->apiKey."&currencies=".join(",",$this->currencies)));
//
//            $data = json_decode($rawTxt,true);

            $pointer = 0;

            do{
                $rawTxt = join("",file("http://www.apilayer.net/api/live?access_key=".$this->apiKey[$pointer]."&currencies=".join(",",$this->currencies)));
                //$rawTxt = '{"success":true,"terms":"https:\/\/currencylayer.com\/terms","privacy":"https:\/\/currencylayer.com\/privacy","timestamp":1495633453,"source":"USD","quotes":{"USDUSD":1,"USDCNY":6.888701,"USDHKD":7.78834}}';
                $data = json_decode($rawTxt,true);
                if(!isset($this->apiKey[$pointer])){
                    break;
                }
                $pointer++;
            }while(!$data['success']);

            $this->ratioList = ["USD"=>1];
            $currencyCodeAlt = $this->currencyCodeAlt;

            foreach($data["quotes"] as $k=>$v){

                $name = str_replace("USD","",$k);

                if(isset($currencyCodeAlt[$name])){

                    $this->ratioList[$currencyCodeAlt[$name]] = $v;

                }

                $this->ratioList[$name] = $v;

            }

            Cache::put($this->cachePrefix.'_currencies_ratio',json_encode($this->ratioList),480);

        }catch (Exception $e){

            throwException(new Exception("Apilayer error:".$e->getMessage()));

        }

    }

}