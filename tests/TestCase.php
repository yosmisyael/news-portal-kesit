<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("DELETE FROM pictures");
        DB::delete("DELETE FROM suspensions");
        DB::delete("DELETE FROM reviews");
        DB::delete("DELETE FROM submissions");
        DB::delete("DELETE FROM category_post");
        DB::delete("DELETE FROM categories");
        DB::delete("DELETE FROM headlines");
        DB::delete("DELETE FROM posts");
        DB::delete("DELETE FROM users");
        DB::delete("DELETE FROM admins");
    }
}
