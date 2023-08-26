<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Pizza
 *
 * @property int $id
 * @property string $name
 * @property string $ingredients
 * @property float $price
 * @property string|null $photo_name
 * @property bool $sold_out
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\PizzaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza whereIngredients($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza wherePhotoName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza whereSoldOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pizza whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pizza extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ingredients',
        'price',
        'photo_name',
        'sold_out',
    ];
}
