<?php

namespace Tests\Feature;

use App\Models\Book;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
 
    /** @test */

    function can_get_all_books()

    {
        $books = Book::factory(2)->create();  

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([

            'title' => $books[0]->title

        ])->assertJsonFragment([

            'title' => $books[1]->title
        ]); 
    }



     /** @test */

     function can_get_one_book()

     {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([

            'title' => $book->title
        ]);
     }


        /** @test */

        function can_create_book()

        {
           $this->postJson(route('books.store'),[])->assertJsonValidationErrorFor('title');

           $this->postJson(route('books.store'),[

                'title' => 'nuevo libro'
           ])->assertJsonFragment([
            'title' => 'nuevo libro']);

           $this->assertDatabaseHas('books', [

                'title' => 'nuevo libro'
           ]); 
        
          
        }


        /** @test */

        function can_update_book()

        {

            $book = Book::factory()->create();

            $this->patchJson(route('books.update', $book),[])->assertJsonValidationErrorFor('title');



            $this->patchJson(route('books.update',$book),[

                    'title'  => 'libro editado'

            ])->assertJsonFragment([ 

                'title'  => 'libro editado'
            ]);

            $this->assertDatabaseHas('books', [

                'title'  => 'libro editado'

            ]);

        }


         /** @test */

         function can_delete_books()

         {
            $book = Book::factory()->create();

            $this->deleteJson(route('books.destroy', $book))->assertNoContent();

            $this->assertDatabaseCount('books', 0);

         }

}
