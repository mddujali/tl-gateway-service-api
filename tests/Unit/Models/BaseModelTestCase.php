<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

abstract class BaseModelTestCase extends TestCase
{
    use RefreshDatabase;

    protected Model $model;

    protected string $table;

    protected array $columns;

    protected array $fillable = [];

    public function test_it_should_have_a_table(): void
    {
        $this->assertEquals($this->table, $this->model->getTable());
    }

    public function test_it_should_have_columns(): void
    {
        $this->assertTrue(
            Schema::hasColumns(
                $this->model->getTable(),
                $this->columns
            )
        );
    }

    public function test_it_should_have_correct_columns_count(): void
    {
        $this->assertEqualsCanonicalizing($this->columns, Schema::getColumnListing($this->model->getTable()));

        $this->assertCount(
            count($this->columns),
            Schema::getColumnListing($this->model->getTable())
        );
    }

    public function test_it_should_have_correct_fillable(): void
    {
        $this->assertEqualsCanonicalizing($this->fillable, $this->model->getFillable());

        $this->assertCount(
            count($this->fillable),
            $this->model->getFillable()
        );
    }
}
