<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class GeometryCastTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_serializes_and_deserializes_geometry_object(): void
    {
        $point = new Point(180, 0);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => $point,
        ])->fresh();

        $this->assertEquals($point, $testPlace->point);
    }

    /** @test */
    public function it_throws_exception_when_serializing_invalid_geometry_object(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TestPlace::factory()->make([
            'point' => new LineString([
                new Point(180, 0),
                new Point(179, 1),
            ]),
        ]);
    }

    /** @test */
    public function it_throws_exception_when_serializing_invalid_type(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TestPlace::factory()->make([
            'point' => 'not-a-point-object',
        ]);
    }

    /** @test */
    public function it_throws_exception_when_deserializing_invalid_geometry_object(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TestPlace::factory()->create([
            'point_with_line_string_cast' => DB::raw('POINT(0, 180)'),
        ]);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::firstOrFail();

        $testPlace->getAttribute('point_with_line_string_cast');
    }

    /** @test */
    public function it_serializes_and_deserializes_null(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => null,
        ])->fresh();

        $this->assertEquals(null, $testPlace->point);
    }
}
