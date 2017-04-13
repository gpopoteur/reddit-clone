<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostsControllerTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_guest_can_see_all_the_posts()
    {
        // Arrange
        $posts = factory(\App\Post::class, 10)->create();
        
        // Act
        $response = $this->get(route('posts_path'));
    
        // Assert
        $response->assertStatus(200);
        foreach ($posts as $post) {
            $response->assertSee($post->title);
        }
    }

    /** @test */
    public function it_sees_posts_author()
    {
        // Arrange
        $posts = factory(\App\Post::class, 10)->create();
        
        // Act
        $response = $this->get(route('posts_path'));
    
        // Assert
        $response->assertStatus(200);
        foreach ($posts as $post) {
            $response->assertSee($post->title);
            $response->assertSee(
                e($post->user->name)
            );
        }
    }

    /** @test */
    public function a_registered_user_can_see_all_the_posts()
    {
        // Arrange
        $user = factory(\App\User::class)->create();

        $this->userSignIn($user);

        $posts = factory(\App\Post::class, 10)->create();
        
        // Act
        $response = $this->get(route('posts_path'));
    
        // Assert
        $response->assertStatus(200);
        foreach ($posts as $post) {
            $response->assertSee($post->title);
        }
    }

    /** @test */
    public function a_guest_cannot_see_the_creation_form()
    {
        // Act
        $response = $this->get(route('create_post_path'));
    
        // Assert
        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_guest_cannot_create_posts()
    {
        // Act
        $response = $this->post(route('store_post_path'));
    
        // Assert
        $response->assertRedirect('/login');
    }

    /** @test */
    public function a_registered_user_can_create_posts()
    {
        // Arrange
        $user = factory(\App\User::class)->create();

        $this->userSignIn($user);

        // Act
        $response = $this->post(route('store_post_path'), [
            'title' => 'Titulo',
            'description' => 'Descripción',
            'url' => 'http://gpopoteur.com'
        ]);
    
        // Assert
        $post = \App\Post::first();
        $this->assertSame(\App\Post::count(), 1);
        $this->assertSame($user->id, $post->user_id);
    }

    /** @test */
    public function only_author_can_edit_post()
    {
        // Arrange
        $user = factory(\App\User::class)->create();
        $post = factory(\App\Post::class)->create([ 'user_id' => $user->id ]);

        $this->userSignIn($user);
        
        // Act
        $response = $this->put(route('update_post_path', ['post' => $post->id]), [
            'title' => 'editado',
            'description' => 'editado',
            'url' => 'http://gpopoteur.com',
        ]);
    
        // Assert
        $post = \App\Post::first();
        $this->assertSame($post->title, 'editado');
        $this->assertSame($post->description, 'editado');
        $this->assertSame($post->url, 'http://gpopoteur.com');
    }

    /** @test */
    public function if_not_author_cannot_edit_post()
    {
        // Arrange
        $user = factory(\App\User::class)->create();
        $post = factory(\App\Post::class)->create();

        $this->userSignIn($user);
        
        // Act
        $response = $this->put(route('update_post_path', ['post' => $post->id]), [
            'title' => 'editado',
            'description' => 'editado',
            'url' => 'http://gpopoteur.com',
        ]);
    
        // Assert
        $post = \App\Post::first();
        $this->assertNotSame($post->title, 'editado');
        $this->assertNotSame($post->description, 'editado');
        $this->assertNotSame($post->url, 'http://gpopoteur.com');
    }

    /** @test */
    public function only_author_can_delete_post()
    {
        // Arrange
        $user = factory(\App\User::class)->create();
        $post = factory(\App\Post::class)->create([ 'user_id' => $user->id ]);

        $this->userSignIn($user);

        // Act
        $this->delete(route('delete_post_path', ['post' => $post->id]));

        $response = $this->get(route('posts_path'));
    
        // Assert
        $response->assertDontSee($post->title);
        
        $post = $post->fresh();
        $this->assertNull($post);
    }

    /** @test */
    public function if_not_author_cannot_delete_post()
    {
        // Arrange
        $user = factory(\App\User::class)->create();
        $post = factory(\App\Post::class)->create();

        $this->userSignIn($user);

        // Act
        $this->delete(route('delete_post_path', ['post' => $post->id]));

        $response = $this->get(route('posts_path'));
    
        // Assert
        $response->assertSee($post->title);
        
        $post = $post->fresh();
        $this->assertNotNull($post);
    }
}
