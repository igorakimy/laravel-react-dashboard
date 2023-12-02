<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property string|null $width
 * @property string|null $height
 * @property string|null $weight
 * @property string|null $cost_price
 * @property string|null $selling_price
 * @property string|null $barcode
 * @property string|null $location
 * @property int|null $color_id
 * @property int|null $material_id
 * @property int|null $vendor_id
 * @property int|null $type_id
 * @property string|null $caption
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $related
 * @property-read int|null $related_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $similar
 * @property-read int|null $similar_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereColorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVendorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWidth($value)
 * @method orderByCategories(string $direction)
 * @method orderByType(string $direction)
 * @property int $quantity
 * @property string|null $margin
 * @property-read \App\Models\Color|null $color
 * @property-read \App\Models\Material|null $material
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Type|null $type
 * @property-read \App\Models\Vendor|null $vendor
 * @method static Builder|Product whereMargin($value)
 * @method static Builder|Product whereQuantity($value)
 * @mixin \Eloquent
 */
class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * @var array
     */
    protected $guarded = [];

    // =================== //
    //      RELATIONS      //
    // =================== //

    /**
     * Product categories.
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)
                    ->using(CategoryProduct::class);
    }

    /**
     * Product type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Product vendor.
     *
     * @return BelongsTo
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Product color.
     *
     * @return BelongsTo
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Product material.
     *
     * @return BelongsTo
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Related products.
     *
     * @return BelongsToMany
     */
    public function related(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'related_product',
            'product_id',
            'related_product_id'
        );
    }

    /**
     * Similar products.
     *
     * @return BelongsToMany
     */
    public function similar(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'similar_product',
            'product_id',
            'similar_product_id'
        );
    }

    /**
     * Product fields with values.
     *
     * @return mixed
     */
    public function fields(): mixed
    {
        $categories = $this->categories();

        $fields = $categories->belongsToMany(Field::class, 'category_field')
                             ->wherePivotIn('category_id', $categories->pluck('id')->toArray());

        $values = $this->hasMany(Value::class, 'product_id')
                       ->whereIn('field_id', $fields->pluck('id')->toArray());

        return $fields->withPivot('value_data', function ($query) use ($values) {
            $query->select('value_data')
                  ->from('values')
                  ->whereIn('id', $values->pluck('id')->toArray());
        });
    }

    /**
     * Local fields.
     *
     * @return BelongsToMany
     */
    public function localFields(): BelongsToMany
    {
        return $this->belongsToMany(LocalField::class, 'product_local_fields')
                    ->using(ProductLocalField::class)
                    ->withPivot('value', 'custom_props');
    }

    /**
     * Product meta fields trough category.
     *
     * @return HasManyThrough
     */
    public function metas(): HasManyThrough
    {
        return $this->hasManyThrough(
            Meta::class,
            Category::class
        );
    }

    /**
     * Product meta fields.
     *
     * @return HasMany
     */
    public function productMetas(): HasMany
    {
        return $this->hasMany(ProductMeta::class);
    }

    /**
     * Product integrations.
     *
     * @return BelongsToMany
     */
    public function integrations(): BelongsToMany
    {
        return $this->belongsToMany(
            Integration::class,
            'product_integrations'
        );
    }

    // ================ //
    //      SCOPES      //
    // ================ //

    /**
     * Scope a query to order products by categories.
     *
     * @param  Builder  $query
     * @param  string  $direction
     *
     * @return void
     */
    public function scopeOrderByCategories(Builder $query, string $direction = 'asc'): void
    {
        $query->orderBy(
            Category::query()->selectRaw('STRING_AGG(name, \', \')')
                    ->join(
                        'category_product',
                        'category_product.category_id',
                        '=',
                        'categories.id'
                    )
                    ->whereColumn('category_product.product_id', '=', 'products.id')
                    ->groupBy('products.id'),
            $direction
        );
    }

    /**
     * Scope a query to order products by type.
     *
     * @param  Builder  $query
     * @param  string  $direction
     *
     * @return void
     */
    public function scopeOrderByType(Builder $query, string $direction = 'asc'): void
    {
        $query->orderBy(
            Type::select('name')
                ->whereColumn('types.id', '=', 'products.type_id'),
            $direction
        );
    }

    // ======================= //
    //      OTHER METHODS      //
    // ======================= //

    /**
     * Generate thumbnail.
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
