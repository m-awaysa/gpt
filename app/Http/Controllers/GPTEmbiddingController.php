<?php

namespace App\Http\Controllers;

use App\Models\EmbeddeRecipe;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GPTEmbiddingController extends Controller
{


    public function askGPT(Request $request)
    {

        // Generate embedding for the user's question
        $userEmbedding = $this->generateEmbeddingService($request->question);

        // Retrieve all recipe embeddings from EmbedRecipes
        $embeddings = EmbeddeRecipe::all();

        $mostSimilar = null;
        $highestSimilarity = 0;
        $array = [];
        $values = [];
        foreach ($embeddings as $embed) {
            // Calculate similarity between user's embedding and each stored embedding
            $similarity = $this->cosineSimilarity($userEmbedding, json_decode($embed->data, true));

            // Update most similar if this is the highest similarity we've found
            $recipe = Recipe::find($embed->recipe_id);
            $array[$embed->id] = $recipe ? "{$recipe->name}: $recipe->description" . "  sem:" .   $similarity : 'No similar recipe found.';
            $values[$embed->id] = $similarity;

            // if ($similarity > $highestSimilarity) {
            //     $highestSimilarity = $similarity;
            //     $mostSimilar = $embed;
            // }
        }
        $max = 0;
        $keyIndex = -1;
        foreach ($values as $key => $value) {
            if ((double)$value > $max) {
                $keyIndex = $key;
                $max = (double)$value;
            }
        }
        $spotted =   $keyIndex != -1 ?  $array[$keyIndex] : 'nothing';
        // Retrieve the full recipe information using the recipe_id from the most similar embedding
        $recipe = null;
        $description = '';

        if ($mostSimilar) {
            $recipe = Recipe::find($mostSimilar->recipe_id);
            $description = $recipe ? $recipe->description : 'No description found.';
        }

        // Prepare the answer
        $answer = $recipe ? "{$recipe->name}: $description" . "  sem:" . $highestSimilarity : 'No similar recipe found.';

        // Return the view with the answer
        return view('embidding', [
            'array' => $array,
            'spotted'=> $spotted ?$spotted:''
        ]);
    }

    function cosineSimilarity($vectorA, $vectorB)
    {
        $dotProduct = array_sum(array_map(function ($a, $b) {
            return $a * $b;
        }, $vectorA, $vectorB));
        $normA = sqrt(array_sum(array_map(function ($a) {
            return $a * $a;
        }, $vectorA)));
        $normB = sqrt(array_sum(array_map(function ($b) {
            return $b * $b;
        }, $vectorB)));
        return $dotProduct / ($normA * $normB);
    }

    public function embedRecipes(Request $request)
    {
        $recipes = Recipe::get(); // Assuming you have a Recipe model with your recipes

        foreach ($recipes as $recipe) {
            $embedding = $this->generateEmbeddingService($recipe->description);

            if ($embedding) {
                EmbeddeRecipe::create([
                    'recipe_id' => $recipe->id,
                    'data' => json_encode($embedding)
                ]);
            }
        }

        return back()->with('status', 'Embeddings generated and saved successfully!');
    }


    public function generateEmbeddingService(string $text)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/embeddings', [
            'input' => $text,
            'model' => 'text-embedding-ada-002',
        ]);

        if ($response->successful()) {
            return $response->json()['data'][0]['embedding'];
        }

        return null;
    }
}
