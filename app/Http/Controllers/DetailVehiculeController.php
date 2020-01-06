<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\DetailVehicule;


class DetailVehiculeController
{
    private $urlBase = "https://www.seminovos.com.br";

    public function details(Request $request){

        $vehicules = new DetailVehicule();

        $idCar = $request->id;

        $result = $vehicules->crawler($this->urlBase,$idCar);

        return $result;
    }
}