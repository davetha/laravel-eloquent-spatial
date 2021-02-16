# Laravel Eloquent Spatial

[![Latest Version on Packagist](https://img.shields.io/packagist/v/matanyadaev/laravel-eloquent-spatial.svg?style=flat-square)](https://packagist.org/packages/matanyadaev/laravel-eloquent-spatial)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/matanyadaev/laravel-eloquent-spatial/Tests?label=tests)
![Lint](https://github.com/matanyadaev/laravel-eloquent-spatial/workflows/Lint/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/matanyadaev/laravel-eloquent-spatial.svg?style=flat-square)](https://packagist.org/packages/matanyadaev/laravel-eloquent-spatial)

Laravel package to easily work with [MySQL Spatial Data Types](https://dev.mysql.com/doc/refman/8.0/en/spatial-type-overview.html) and [MySQL Spatial Functions](https://dev.mysql.com/doc/refman/8.0/en/spatial-function-reference.html).

This package supports MySQL 5.7 & 8.0. The package works on PHP 8 & Laravel 8 only.

## Installation

You can install the package via composer:

```bash
composer require matanyadaev/laravel-eloquent-spatial
```

## Quickstart
Generate a new model with a migration file:
```bash
php artisan make:model --migration
```

Add some spatial columns to the migration file:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlacesTable extends Migration
{
    public function up(): void
    {
        Schema::create('places', static function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->point('location')->nullable();
            $table->polygon('area')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
}
```

Run the migration:

```bash
php artisan migrate
```

Fill the `$fillable` and `$casts` arrays and add custom eloquent builder to your new model:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

/**
 * @property Point $location
 * @property Polygon $area
 * @method static SpatialBuilder query()
 */
class Place extends Model
{
    protected $fillable = [
        'name'
        'location',
        'area',
    ];

    protected $spatialFields = [
        'location' => Point::class,
        'area' => Polygon::class,
    ];
    
    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }
}
```

Access spatial data:

```php
use App\Models\Place;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;

$londonEye = Place::create([
    'name' => 'London Eye',
    'location' => new Point(51.5032973, -0.1195537)
]);

$vaticanCity = Place::create([
    'name' => 'Vatican City',
    'area' => new Polygon([
        new LineString([
              new Point(12.455363273620605, 41.90746728266806),
              new Point(12.450309991836548, 41.906636872349075),
              new Point(12.445632219314575, 41.90197359839437),
              new Point(12.447413206100464, 41.90027269624499),
              new Point(12.457906007766724, 41.90000118654431),
              new Point(12.458517551422117, 41.90281205461268),
              new Point(12.457584142684937, 41.903107507989986),
              new Point(12.457734346389769, 41.905918239316286),
              new Point(12.45572805404663, 41.90637337450963),
              new Point(12.455363273620605, 41.90746728266806),
        ])
    ])
])
```

Retrieve a record with spatial data:

```php
echo $londonEye->location->latitude; // 51.5032973
echo $londonEye->location->longitude; // -0.1195537

echo $vacationCity->area->toJson(); // {"type":"Polygon","coordinates":[[[41.90746728266806,12.455363273620605],[41.906636872349075,12.450309991836548],[41.90197359839437,12.445632219314575],[41.90027269624499,12.447413206100464],[41.90000118654431,12.457906007766724],[41.90281205461268,12.458517551422117],[41.903107507989986,12.457584142684937],[41.905918239316286,12.457734346389769],[41.90637337450963,12.45572805404663],[41.90746728266806,12.455363273620605]]]}
```

## Geometry classes

| MatanYadaev\LaravelEloquentSpatial\Objects                   | OpenGIS Class   |
| ------------------------------------------------------------ | --------------- |
| `Point(float $latitude, float $longitude)`                   | [Point](https://dev.mysql.com/doc/refman/8.0/en/gis-class-point.html) |
| `MultiPoint(Point[] \| Collection<Point>)`                   | [MultiPoint](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipoint.html) |
| `LineString(Point[] \| Collection<Point>)`                   | [LineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-linestring.html) |
| `MultiLineString(LineString[] \| Collection<LineString>)`    | [MultiLineString](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multilinestring.html) |
| `Polygon(LineString[] \| Collection<LineString>)`            | [Polygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-polygon.html) |
| `MultiPolygon(Polygon[] \| Collection<Polygon>)`             | [MultiPolygon](https://dev.mysql.com/doc/refman/8.0/en/gis-class-multipolygon.html) |
| `GeometryCollection(Geometry[] \| Collection<Geometry>)`     | [GeometryCollection](https://dev.mysql.com/doc/refman/8.0/en/gis-class-geometrycollection.html) |

## Available functions

## Tests

``` bash
composer phpunit
# or with coverage
composer phpunit-cover
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
