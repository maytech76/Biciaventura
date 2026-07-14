<?php

namespace App\Traits;

use App\Helpers\DolarHelper;

trait HasPriceInDollars
{
    //actualiza el precio en Bs cada vez que se accede a este atributo, usando la tasa actual del dólar
    public function getPriceInBsAttribute(){
        return DolarHelper::toBs($this->price);
    }
    
    //actualiza el precio en Bs cada vez que se accede a este atributo, usando la tasa actual del dólar y formateándolo con el símbolo y dos decimales
    public function getFormattedPriceInBsAttribute(){
        return DolarHelper::formatBs($this->price);
    }
    
    //
    public function getFormattedPriceInUsdAttribute(){

        return DolarHelper::formatUsd($this->price);
    }
    
    //
    public function getFormattedPriceAttribute()
    {
        return DolarHelper::formatBoth($this->price);
    }
}