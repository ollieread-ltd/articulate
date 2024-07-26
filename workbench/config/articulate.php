<?php

use Articulate\Metadata\Types;

return [

    /*
    |--------------------------------------------------------------------------
    | Class mappings
    |--------------------------------------------------------------------------
    |
    | This is where all the settings regarding class mappings are held.
    | You can provide custom directory and namespace mappings for discovery,
    | or provide specific classes.
    |
    */

    'mappings' => [

        'discovery' => [
            'Database\\Mappings\\' => database_path('mappings')
        ],

        'classes' => []

    ],

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
            Types\Properties\Classes\CarbonPropertyType::class,
            Types\Properties\Classes\DateTimePropertyType::class,
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
                Types\BooleanType::NAME                           => Types\BooleanType::NAME,
                Types\IntegerType::NAME                           => Types\IntegerType::NAME,
                Types\StringType::NAME                            => Types\StringType::NAME,
                Types\Properties\ArrayPropertyType::NAME          => Types\Columns\JsonColumnType::NAME,
                Types\Properties\Classes\CarbonPropertyType::NAME => Types\Columns\TimestampColumnType::NAME,
            ],

            // Column types to property types
            'column'   => [
                Types\BooleanType::NAME                 => Types\BooleanType::NAME,
                Types\IntegerType::NAME                 => Types\IntegerType::NAME,
                Types\StringType::NAME                  => Types\StringType::NAME,
                Types\Columns\JsonColumnType::NAME      => Types\Properties\ArrayPropertyType::NAME,
                Types\Columns\TimestampColumnType::NAME => Types\Properties\Classes\CarbonPropertyType::NAME,
            ],

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Metadata enrichment
    |--------------------------------------------------------------------------
    |
    | This is where you can enable or disable metadata enrichment.
    | When Articulate maps entities and fields, if this is enabled, the
    | mapper will inspect the class and properties through reflection, and
    | make use of any attributes or other discernible traits that can enrich
    | the mapping.
    | If this is disabled, the mapping will consist only of the information
    | provided manually.
    |
    */

    'enrichment' => env('ARTICULATE_ENRICHMENT', true),

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
