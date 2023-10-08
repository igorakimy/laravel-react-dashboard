<?php

namespace App\Http\Controllers\Api;

use App\Data\Vendor\VendorData;
use App\Http\Controllers\ApiController;
use App\Models\Vendor;
use Illuminate\Http\Request;

final class VendorController extends ApiController
{
    public function index()
    {
        $vendors = Vendor::query()->get();

        return VendorData::collection($vendors);
    }

    public function show(Request $request, Vendor $vendor)
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, Vendor $vendor)
    {

    }

    public function destroy(Vendor $vendor)
    {

    }
}
