<?php

use App\Model\Category;
use App\Model\Like;
use App\Model\Question;
use App\Model\Reply;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 20)->create();
        factory(Category::class, 10)->create();
        factory(Question::class, 8)->create();
        factory(Reply::class, 50)->create()->each(function($reply) {
            return $reply->likes()->save(factory(Like::class)->make());
        });
    }
}
