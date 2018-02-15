<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

Route::get('/', function () {


    $serviceAccount = ServiceAccount::fromJsonFile('../'.env('GOOGLE_APPLICATION_CREDENTIALS'));

    $firebase = (new Factory)
        ->withServiceAccount($serviceAccount)
        ->withDatabaseUri(env('FIREBASE_CREDENTIALS'))
        ->create();

    $database = $firebase->getDatabase();

    $newPost = $database
        ->getReference('blog/posts')
        ->push([
            'title' => 'Post title',
            'body' => 'This should probably be longer.'
        ]);

    $database->getReference('config/website')
        ->set([
            'name' => 'My Application',
            'emails' => [
                'support' => 'support@domain.tld',
                'sales' => 'sales@domain.tld',
            ],
            'website' => 'https://app.domain.tld',
        ]);

    $database->getReference('config/website/name')->set('New name');

//    $newPost->getKey(); // => -KVr5eu8gcTv7_AHb-3-
//    $newPost->getUri(); // => https://my-project.firebaseio.com/blog/posts/-KVr5eu8gcTv7_AHb-3-
//
//    $newPost->getChild('title')->set('Changed post title');
//    $newPost->getValue(); // Fetches the data from the realtime database
//    $newPost->remove();

    return view('welcome');
});
