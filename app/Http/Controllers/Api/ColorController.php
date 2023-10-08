<?php

namespace App\Http\Controllers\Api;

use App\Data\Color\ColorData;
use App\Http\Controllers\ApiController;
use App\Models\Color;
use Illuminate\Http\Request;

final class ColorController extends ApiController
{
    public function index()
    {
        $colors = Color::query()->get();

        return ColorData::collection($colors);
    }

    public function show(Request $request, Color $color)
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, Color $color)
    {

    }

    public function destroy(Color $color)
    {

    }
}
