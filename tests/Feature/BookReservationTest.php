<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_library()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'Wonderful World',
            'author' => 'Gajah Mada',
        ]);

        $response->assertOk();
        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function a_title_is_required()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'Budi Utomo',
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required()
    {
        $response = $this->post('/books', [
            'title' => 'Wonderful World',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Hello World Book',
            'author' => 'Justin Me',
        ]);

        $book = Book::first();

        $this->patch('/books/' . $book->id, [
            'title' => 'Yes Boss!',
            'author' => 'Justin Lee'
        ]);

        $this->assertEquals('Yes Boss!', Book::first()->title);
        $this->assertEquals('Justin Lee', Book::first()->author);
    }

}
