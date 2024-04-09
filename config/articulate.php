<?php

use Articulate\Metadata\Types;

return [

    /*
    |--------------------------------------------------------------------------
    | Class mappings
    |--------------------------------------------------------------------------
    |
    | If you have any mappings that sit outside the database/metadata
    | directory, you can list them here.
    |
    */

    'mappings' => [],

    /*
    |--------------------------------------------------------------------------
    | Types
    |--------------------------------------------------------------------------
    |
    | This is where the default set of types are provided, which will be mapped
    | as the initial set.
    | You may add your own here, but be aware that order is important.
    |
    */

    'types' => [

        // Column Types
        'column'   => [
            Types\Columns\BooleanColumnType::class,
            Types\Columns\IntegerColumnType::class,
            Types\Columns\JsonColumnType::class,
            Types\Columns\StringColumnType::class,
            Types\Columns\TimestampColumnType::class,
        ],

        // Property types
        'property' => [
            Types\Properties\ArrayPropertyType::class,
            Types\Properties\BooleanPropertyType::class,
            Types\Properties\FloatPropertyType::class,
            Types\Properties\IntegerPropertyType::class,
            Types\Properties\StringPropertyType::class,
            // Property class types
            Types\Properties\CarbonPropertyType::class,
            Types\Properties\DateTimePropertyType::class,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default types
    |--------------------------------------------------------------------------
    |
    | Some property types will have default column types and vice verse, this
    | is where they're mapped.
    |
    */

    'defaults' => [

        // Property types to column types
        'property' => [
            Types\Properties\BooleanPropertyType::NAME => Types\Columns\BooleanColumnType::NAME,
            Types\Properties\IntegerPropertyType::NAME => Types\Columns\IntegerColumnType::NAME,
            Types\Properties\ArrayPropertyType::NAME   => Types\Columns\JsonColumnType::NAME,
            Types\Properties\StringPropertyType::NAME  => Types\Columns\StringColumnType::NAME,
            Types\Properties\CarbonPropertyType::NAME  => Types\Columns\TimestampColumnType::NAME,
        ],

        // Column types to property types
        'column'   => [
            Types\Columns\BooleanColumnType::NAME   => Types\Properties\BooleanPropertyType::NAME,
            Types\Columns\IntegerColumnType::NAME   => Types\Properties\IntegerPropertyType::NAME,
            Types\Columns\JsonColumnType::NAME      => Types\Properties\ArrayPropertyType::NAME,
            Types\Columns\StringColumnType::NAME    => Types\Properties\StringPropertyType::NAME,
            Types\Columns\TimestampColumnType::NAME => Types\Properties\CarbonPropertyType::NAME,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Mapping inspections
    |--------------------------------------------------------------------------
    |
    | This is where you can enable or disable mapping inspections.
    | When Articulate maps entities and fields, if this is enabled, the
    | mapper will inspect the class and properties through reflection, and
    | make use of any attributes or other discernible traits that can enrich
    | the mapping.
    | If this is disabled, the mapping will consist only of the information
    | provided manually.
    |
    */

    'inspect' => env('ARTICULATE_INSPECTION', true),

    /*
    |--------------------------------------------------------------------------
    | Case conversion
    |--------------------------------------------------------------------------
    |
    | This setting will define how Articulate converts cases between class
    | properties and database columns.
    |
    | Supported: "snake", "camel", "kebab", "null" and "studly"
    |
    */

    'case_conversion' => env('ARTICULATE_CASE_CONVERSION', 'snake'),

    /*
    |--------------------------------------------------------------------------
    | Field override
    |--------------------------------------------------------------------------
    |
    | Sometimes a field will have multiple definitions, one in a mapping, and
    | one as an attribute on the class property.
    | This option lets you specify which is preferred when mapping fields.
    |
    | Supported: "attribute", "mapping"
    |
    */

    'field_override' => env('ARTICULATE_FIELD_OVERRIDE', 'mapping'),

];
