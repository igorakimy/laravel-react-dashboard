<?php

namespace Database\Seeders;

use App\Enums\FieldType;
use App\Models\LocalField;
use Illuminate\Database\Seeder;

class LocalFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $localFields = [
            [
                'name'        => 'Name',
                'slug'        => 'name',
                'field_type'  => FieldType::TEXT,
                'order'       => 1,
                'validations' => [
                    'min'      => 2,
                    'max'      => 255,
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'Name',
                ]
            ],
            [
                'name'        => 'SKU',
                'slug'        => 'sku',
                'field_type'  => FieldType::TEXT,
                'order'       => 2,
                'validations' => [
                    'min'      => 5,
                    'max'      => 255,
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'SKU',
                ]
            ],
            [
                'name'        => 'Quantity',
                'slug'        => 'quantity',
                'field_type'  => FieldType::NUMBER,
                'order'       => 3,
                'validations' => [
                    'min'      => 0,
                    'max'      => 9999999,
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'Quantity in stock',
                ]
            ],
            [
                'name'        => 'Cost Price',
                'slug'        => 'cost_price',
                'field_type'  => FieldType::NUMBER,
                'order'       => 4,
                'validations' => [
                    'min'      => 0,
                    'max'      => 9999999,
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'Cost Price',
                    'addon'       => '$',
                ]
            ],
            [
                'name'        => 'Selling Price',
                'slug'        => 'selling_price',
                'field_type'  => FieldType::NUMBER,
                'order'       => 5,
                'validations' => [
                    'min'      => 0,
                    'max'      => 9999999,
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'Selling Price',
                    'addon'       => '$',
                ]
            ],
            [
                'name'        => 'Margin',
                'slug'        => 'margin',
                'field_type'  => FieldType::NUMBER,
                'order'       => 6,
                'validations' => [
                    'min'      => 0,
                    'max'      => 1000,
                    'required' => false,
                ],
                'properties'  => [
                    'placeholder' => 'Margin',
                    'addon'       => '%',
                ]
            ],
            [
                'name'        => 'Width',
                'slug'        => 'width',
                'field_type'  => FieldType::NUMBER,
                'order'       => 7,
                'validations' => [
                    'min'      => 0,
                    'max'      => 9999999,
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'Width',
                ]
            ],
            [
                'name'        => 'Height',
                'slug'        => 'height',
                'field_type'  => FieldType::NUMBER,
                'order'       => 8,
                'validations' => [
                    'min'      => 0,
                    'max'      => 9999999,
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'Height',
                ]
            ],
            [
                'name'        => 'Weight',
                'slug'        => 'weight',
                'field_type'  => FieldType::NUMBER,
                'order'       => 9,
                'validations' => [
                    'min'      => 0,
                    'max'      => 9999999,
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'Weight',
                ]
            ],
            [
                'name'        => 'Barcode/UPC',
                'slug'        => 'barcode',
                'field_type'  => FieldType::TEXT,
                'order'       => 10,
                'validations' => [
                    'max'      => 255,
                    'required' => false,
                ],
                'properties'  => [
                    'placeholder' => 'Barcode/UPC',
                ]
            ],
            [
                'name'        => 'Location/Bin Number',
                'slug'        => 'location',
                'field_type'  => FieldType::TEXT,
                'order'       => 11,
                'validations' => [
                    'max'      => 255,
                    'required' => false,
                ],
                'properties'  => [
                    'placeholder' => 'Location/Bin Number',
                ]
            ],
            [
                'name'        => 'Color',
                'slug'        => 'color',
                'field_type'  => FieldType::SELECT,
                'order'       => 12,
                'validations' => [
                    'required' => false,
                ],
                'properties'  => [
                    'placeholder' => 'Color',
                    'resource'    => 'App\Models\Color',
                    'resource_url' => '/colors',
                    'resource_key' => 'id',
                    'resource_label' => 'name',
                ]
            ],
            [
                'name'        => 'Material',
                'slug'        => 'material',
                'field_type'  => FieldType::SELECT,
                'order'       => 13,
                'validations' => [
                    'required' => false,
                ],
                'properties'  => [
                    'placeholder' => 'Material',
                    'resource'    => 'App\Models\Material',
                    'resource_url' => '/materials',
                    'resource_key' => 'id',
                    'resource_label' => 'name',
                ]
            ],
            [
                'name'        => 'Vendor',
                'slug'        => 'vendor',
                'field_type'  => FieldType::SELECT,
                'order'       => 14,
                'validations' => [
                    'required' => false,
                ],
                'properties'  => [
                    'placeholder' => 'Vendor',
                    'resource'    => 'App\Models\Vendor',
                    'resource_url' => '/vendors',
                    'resource_key' => 'id',
                    'resource_label' => 'name',
                ]
            ],
            [
                'name'        => 'Type',
                'slug'        => 'type',
                'field_type'  => FieldType::SELECT,
                'order'       => 15,
                'validations' => [
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'Type',
                    'resource'    => 'App\Models\Type',
                    'resource_url' => '/types',
                    'resource_key' => 'id',
                    'resource_label' => 'name',
                ]
            ],
            [
                'name'        => 'Categories',
                'slug'        => 'categories',
                'field_type'  => FieldType::MULTISELECT,
                'order'       => 16,
                'validations' => [
                    'required' => true,
                ],
                'properties'  => [
                    'placeholder' => 'Categories',
                    'resource'    => 'App\Models\Category',
                    'resource_url' => '/categories',
                    'resource_key' => 'id',
                    'resource_label' => 'name',
                ]
            ],
            [
                'name'        => 'Caption',
                'slug'        => 'caption',
                'field_type'  => FieldType::TEXTAREA,
                'order'       => 17,
                'validations' => [
                    'required' => false,
                    'max'      => 5000,
                ],
                'properties'  => [
                    'placeholder' => 'Caption',
                ]
            ],
            [
                'name'        => 'Description',
                'slug'        => 'description',
                'field_type'  => FieldType::TEXTAREA,
                'order'       => 18,
                'validations' => [
                    'required' => false,
                    'max'      => 5000,
                ],
                'properties'  => [
                    'placeholder' => 'Description',
                ]
            ],
        ];

        foreach ($localFields as $localField) {
            LocalField::query()->updateOrCreate([
                'slug' => $localField['slug'],
            ], $localField);
        }
    }
}
