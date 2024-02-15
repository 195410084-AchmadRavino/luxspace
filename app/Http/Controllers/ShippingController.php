<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Dipantry\Rajaongkir\Constants\RajaongkirCourier;
use Dipantry\Rajaongkir\Models\ROCity;
use Dipantry\Rajaongkir\Models\ROCountry;
use Dipantry\Rajaongkir\Models\ROProvince;
use Dipantry\Rajaongkir\Models\ROSubDistrict;
use Dipantry\Rajaongkir\Rajaongkir;

class ShippingController extends Controller
{
    public function getData()
    {
        $provinces = ROProvince::all();
        $cities = ROCity::all();
        $subDistricts = ROSubDistrict::all();
        $countries = ROCountry::all();

        // Lakukan sesuatu dengan data yang diperoleh
        // ...

        return view('pages.dashboard.transaction.index', compact('provinces', 'cities', 'subDistricts', 'countries'));
    }

    public function calculateShippingCost()
    {
        $biayaPengiriman = Rajaongkir::getOngkirCost(
            $origin = 1,
            $destination = 200,
            $weight = 300,
            $courier = RajaongkirCourier::JNE,
            $originType = 'subdistrict',
            $destinationType = 'subdistrict'
        );

        // Lakukan sesuatu dengan $biayaPengiriman...
    }
}
