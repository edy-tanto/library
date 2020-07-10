<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_library()
    {
        $response = $this->post('/books', $this->data());

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
        $response = $this->post('/books', array_merge($this->data(), ['author_id' => '']));

        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->post('/books', $this->data());

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'Yes Boss!',
            'author_id' => 'Justin Lee'
        ]);

        $this->assertEquals('Yes Boss!', Book::first()->title);
        // $this->assertEquals(Author::orderBy('id', 'desc')->first()->id, Book::first()->author_id);
        $this->assertEquals(2, Book::first()->author_id);
        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->post('/books', $this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }


    /** @test */
    public function a_new_author_is_automatically_added()
    {
        // $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Booyah Title',
            'author_id' => 'Marcus Phd',
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function data(): array
    {
        return [
            'title' => 'Wonderful World',
            'author_id' => 'Gajah Mada',
        ];
    }
}
