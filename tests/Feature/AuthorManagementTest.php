<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function an_author_can_be_created()
    {
        $this->post('authors', [
            'name' => 'Author Name',
            'dob' => '05/14/1988', // date of birth
        ]);

        $authors = Author::all();

        $this->assertCount(1, $authors);
        $this->assertInstanceOf(Carbon::class, $authors->first()->dob);
        $this->assertEquals('1988-05-14', $authors->first()->dob->format('Y-m-d'));
    }

}
