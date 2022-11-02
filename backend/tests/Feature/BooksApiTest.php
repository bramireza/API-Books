<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
  use RefreshDatabase;

  /** @test */

  function can_get_all_books(){
    //$book = Book::factory()->create();
    $books = Book::factory(4)->create();

    //dd(route('books.index'));

    $this->getJson(route('books.index'))
      ->assertjsonFragment([
        'title' => $books[0]->title
      ])->assertjsonFragment([
        'title' => $books[1]->title
      ]);


    //$this->get(route('books.index'))->dump();
    //dd($books->count());
  }

  /** @test */

  function can_get_one_book(){
    $book = Book::factory()->create();

    //dd(route('books.show', $book));

    $this->getJson(route('books.show', $book))
        ->assertjsonFragment([
          'title' => $book->title
        ]);
  }

  /** @test */

  function can_create_one_book(){

    $this->postJson(route('books.store'), [
    ])->assertJsonValidationErrorFor('title');

    $this->postJson(route('books.store'), [
      'title' => 'My new Book'
    ])->assertJsonFragment([
      'title' => "My new Book"
    ]);

    $this->assertDatabaseHas('books', [
      'title' => "My new Book"
    ]);
  }

  /** @test */

  function can_update_books(){
    $book = Book::factory()->create();

    $this->patchJson(route('books.update', $book), [
    ])->assertJsonValidationErrorFor('title');

    $this->patchJson(route('books.update', $book), [
      'title' => 'Edited Book'
    ])->assertJsonFragment([
      'title' => 'Edited Book'
    ]);

    $this->assertDatabaseHas('books', [
      'title' => 'Edited Book'
    ]);
  }

  /** @test */
  function can_delete_books(){
    $book = Book::factory()->create();

    $this->deleteJson(route('books.destroy', $book));
    
    $this->assertDatabaseCount('books', 0);
  }

}
