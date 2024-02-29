<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::withoutEvents(function () {
            $user = User::query()->where('username', 'test')->first();
            $post = new Post([
                'title' => 'example post',
                'content' => 'example post',
                'user_id' => $user->id,
                'slug' => Str::slug('example post'),
            ]);
            $post->save();

            $post = new Post([
                'title' => 'The Authority of the Algorithms',
                'content' => '
                <p>If you’ve ever used a recipe to bake something delicious or make a meal, you have used an algorithm, for at their most basic, that’s what algorithms are. Algorithms are ancient, going back thousands of years. Today however, they are more complex and have far more impact and influence on our modern societies, around the world, across almost every culture.</p>

                <p>We have given them agency, power, over us as individuals, our sociocultural systems. We have trained them and some argue, they are training us. Algorithms are used across all forms of Artificial Intelligence (AI) from Neural Networks and Machine Learning to Large Language Models (LLMs) like ChatGPT and Claude.</p>

                <p>Algorithms feature in almost every aspect of our daily lives, from the music we listen to, the shows we watch, the bank loans and insurance we apply for, the jobs we want. They are used to assess our job performance, to sentence criminals, to assign our credit scores, detect cancer, create new medicines. Algorithms are used for many good things. Sometimes too, they go wrong.</p>

                <p>It is not the fault of the algorithm when they go wrong. Sometimes, we have no idea why they go wrong. What caused them to go wrong. The algorithms do not know when they have done wrong. The algorithms, the machines, they do not apply judgement, sentiment, reasoning or emotion. For they have none of these aspects of being human. They are machines. They do what they have been trained to do.</p>
                ',
                'user_id' => $user->id,
                'slug' => Str::slug('The Authority of the Algorithms'),
            ]);
            $post->save();


        });
    }
}
