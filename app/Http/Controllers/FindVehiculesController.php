<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\FindVehicules;

class FindVehiculesController extends Controller
{

    private $urlBase = "https://www.seminovos.com.br";

    public function find(Request $request){

        $filters = array();

        if(!isset($request->vehicule)){
            return response()->json(['result'=>'Vehicle is a required field'], 400);
        }else{
            if($request->vehicule != "carro" && $request->vehicule != "moto" && $request->vehicule != "caminhao"){
                return response()->json(['result'=>'Unregistered vehicle category'], 400);
            }else{
                array_push($filters,$request->vehicule);
            }
        }

        if(!isset($request->brand)){
            return response()->json(['result'=>'Brand is a required field'], 400);
        }else{
            array_push($filters,$request->brand);
        }

        if(!isset($request->model)){
            return response()->json(['result'=>'Model is a required field'], 400);
        }else{
            array_push($filters,$request->model);
        }

        if(isset($request->startYear) && $request->startYear != '' && isset($request->endYear) && $request->endYear != ''){
            array_push($filters,"ano-".$request->startYear."-".$request->endYear);
        }

        if(isset($request->minValue) && $request->minValue != '' && isset($request->maxValue) && $request->maxValue != ''){
            array_push($filters,"preco-".$request->minValue."-".$request->maxValue);
        }

        if($request->conservationState == "new")
            array_push($filters,"estado-novo");
        if($request->estado_conservacao == "used")
            array_push($filters,"estado-seminovo");

        if(isset($request->cities)){
            $cities = explode(',',$request->cities);
            array_push($filters,"cidade[]-". implode('-', $cities));
        }

        $page = isset($request->page) ? $request->page : '1';

        $urlFinal = $this->urlBase .'/'. implode('/',$filters);

        $urlFinal .= "?ordenarPor=2&registrosPagina=50&page=" . $page;

        $vehicules = new FindVehicules();

        $result = $vehicules->crawlerAll($this->urlBase,$urlFinal);

        return $result;
    }

}
