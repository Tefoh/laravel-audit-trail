<?php

namespace Tofiq\AuditTrail\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tofiq\AuditTrail\Tests\AuditTrailTestCase;
use Tofiq\AuditTrail\Tests\Models\Post;
use Tofiq\AuditTrail\Tests\Models\User;

class AuditTrailTest extends AuditTrailTestCase
{
    #[Test]
    public function it_should_audit_the_sql_query_with_query_builder()
    {
        DB::table('posts')->get();

        $this->assertDatabaseHas('audit_log', [
            'table_name' => 'posts',
            'operation_type' => 'SELECT',
            'query' => 'select * from "users"',
        ]);
    }

    #[Test]
    public function it_should_audit_the_sql_query_with_model_creation()
    {
        Post::factory()->create([
            'title' => 'Test title',
            'content' => 'Test content',
            'published_at' => $date = now(),
        ]);
        $userData = json_decode(DB::table('audit_log')->first()->bindings, true);

        $this->assertDatabaseHas('audit_log', [
            'table_name' => 'posts',
            'operation_type' => 'INSERT',
            'query' => 'insert into "posts" ("title", "content", "published_at", "updated_at", "created_at") values (?, ?, ?, ?, ?)',
        ]);

        $this->assertEquals('Test title', $userData[0]);
        $this->assertEquals('Test content', $userData[1]);
        $this->assertEquals($date->toDateTimeString(), Carbon::parse($userData[2])->toDateTimeString());
    }

    #[Test]
    public function it_should_audit_the_store_the_logged_in_user()
    {
        $this->actingAs(
            User::factory()->create()
        );

        $userId = DB::table('audit_log')->first()->user_id;

        Post::factory()->create();

        $this->assertDatabaseHas('audit_log', [
            'table_name' => 'posts',
            'operation_type' => 'INSERT',
            'user_id' => $userId,
        ]);
    }
}
