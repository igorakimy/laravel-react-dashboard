<?php

namespace App\Http\Controllers\Api;

use App\Data\Type\TypeData;
use App\Http\Controllers\ApiController;
use App\Models\Type;
use Illuminate\Http\Request;

final class TypeController extends ApiController
{
    public function index()
    {
        $types = Type::query()->get();

        return TypeData::collection($types);
    }

    public function show(Request $request, Type $type)
    {

    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, Type $type)
    {

    }

    public function destroy(Type $type)
    {

    }
}
