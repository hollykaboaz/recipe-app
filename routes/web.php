<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $videoId = 'z-jyskOCw5w';

    $response = Http::withHeaders([
        'Authorization' => 'Basic ' . getenv('YOUTUBE_TRANSCRIPT_API_KEY'),
        'Content-Type'  => 'application/json',
    ])
        ->post("https://www.youtube-transcript.io/api" . '/transcripts', [
            'ids' => [$videoId],
        ]);

    $transcriptJson  = $response->json();
    $transcriptItems = $transcriptJson[0]['tracks'][0]['transcript'];
    $transcriptText  = '';

    foreach ($transcriptItems as $subtitle) {
        $transcriptText .= gmdate("i:s", $subtitle['start']) . ': ' . $subtitle['text'] . "\n";
    }

    // Open AI Transcript to Recipe Steps
    $recipePrompt = file_get_contents(__DIR__ . "/../recipe-prompt.txt");
    ini_set('max_execution_time', 300);

    $client = OpenAI::client(getenv('OPENAI_API_KEY'));

    $response = $client->responses()->create([
        'model'        => 'gpt-4o',
        'input'        => $transcriptText,
        'instructions' => $recipePrompt,
        'text'         => [
            'format' => [
                'strict'      => true,
                'type'        => 'json_schema',
                'name'        => 'Recipe',
                'description' => 'Turn a cooking tutorial transcript into atomic recipe steps with per-step title, ingredients, description, and start timestamp.',
                'schema'      => [
                    'type'                 => 'object',
                    'properties'           => [
                        'steps' => [
                            'type'        => 'array',
                            'description' => 'Ordered list of atomic steps to complete the recipe.',
                            'items'       => [
                                'type'                 => 'object',
                                'properties'           => [
                                    'title'       => [
                                        'type'        => 'string',
                                        'description' => 'Imperative, ≤6 words (e.g., "Preheat oven").'
                                    ],
                                    'ingredients' => [
                                        'type'        => 'array',
                                        'description' => 'Only ingredients used in this step; lowercase singular nouns; no invented items; quantities only if explicitly stated.',
                                        'items'       => ['type' => 'string']
                                    ],
                                    'description' => [
                                        'type'        => 'string',
                                        'description' => 'Neutral, concise (≤30 words) description of exactly what to do, including any explicit times/temps.'
                                    ],
                                    'timestamp'   => [
                                        'type'        => 'string',
                                        'description' => 'Start time of the step in MM:SS'
                                    ],
                                ],
                                'required'             => ['title', 'ingredients', 'description', 'timestamp'],
                                'additionalProperties' => false
                            ]
                        ],
                    ],
                    'required'             => ['steps'],
                    'additionalProperties' => false
                ]
            ]
        ]
    ]);

    $recipeJson = json_decode($response->outputText, true);
    $recipe = collect($recipeJson['steps']);

    $recipe = $recipe->map(function ($step) {
        list($minutes, $seconds) = explode(':', $step['timestamp']);
        $step['timestamp_seconds'] = (int)$minutes * 60 + (int)$seconds;
        return $step;
    });

    $ingredients = $recipe->pluck('ingredients')->flatten()->unique()->sort()->values();

//    dd($ingredients);

    return Inertia::render('Welcome', [
        'recipe'  => $recipe,
        'ingredients' => $ingredients,
        'videoId' => $videoId,
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
