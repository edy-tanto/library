<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_library()
    {
        $response = $this->post('/books', [
            'title' => 'Wonderful World',
            'author' => 'Gajah Mada',
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
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
        $this->post('/books', [
            'title' => 'Hello World Book',
            'author' => 'Justin Me',
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'Yes Boss!',
            'author' => 'Justin Lee'
        ]);

        $this->assertEquals('Yes Boss!', Book::first()->title);
        $this->assertEquals('Justin Lee', Book::first()->author);
        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->post('/books', [
            'title' => 'Book To Be Deleted',
            'author' => 'No Wan',
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }
}
