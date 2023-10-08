<?php

namespace App\Http\Controllers\Api;

use App\Data\Material\MaterialData;
use App\Http\Controllers\ApiController;
use App\Models\Material;
use Illuminate\Http\Request;

final class MaterialController extends ApiController
{
    public function index()
    {
        $materials = Material::query()->get();

        return MaterialData::collection($materials);
    }

    public function show(Request $request, Material $material)
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, Material $material)
    {

    }

    public function destroy(Material $material)
    {

    }
}
