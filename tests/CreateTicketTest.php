<?php

namespace SGCompTech\FilamentTicketing\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use SGCompTech\FilamentTicketing\Tests\User;

uses(RefreshDatabase::class);

// beforeEach(fn () => User::factory()->create());

it('ticket requires title and content', function () {
			/** @var mixed $author */
			$author = User::factory()->create();

			$this->actingAs($author)->post(route('posts.store'), [
					'title' => '',
					'body' => 'Some valid body',
			])->assertSessionHasErrors('title');

			$this->actingAs($author)->post(route('posts.store'), [
					'title' => 'Some valid title',
					'body' => '',
			])->assertSessionHasErrors('body');

});