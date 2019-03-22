<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/sources', ['as'=>'sources.index', 'uses'=>'SourceController@index']);
$router->get('/sources/byDate', ['as'=>'sources.byDate', 'uses'=>'SourceController@dataByEdition']);
$router->get('/sources/content/{idOzae}',  ['as'=>'sources.content', 'uses'=>'SourceController@content']);
$router->get('/sources/{id}', ['as'=>'sources.show', 'uses'=>'SourceController@show']);

$router->get('/words', ['as'=>'words.index', 'uses'=>'WordController@index']);
$router->get('/words/{id}', ['as'=>'words.show', 'uses'=>'WordController@show']);

$router->get('/test', ['as' => 'articles.test', 'uses'=>'ArticleController@manualTest']);
$router->get('/articles/data',  ['as'=>'articles.data', 'uses'=>'ArticleController@getArticleData']);
$router->get('/articles/data',  ['as'=>'articles.data', 'uses'=>'ArticleController@getArticleData']);
$router->get('/articles/{id}', ['as' => 'articles.show', 'uses'=>'ArticleController@show']);
$router->get('/articles/content/{idOzae}', ['as' => 'articles.showContent', 'uses'=>'ArticleController@showContent']);
$router->get('/articles/word/{word_id}', ['as' => 'articles.fetchByWord', 'uses'=>'ArticleController@fetchByWord']);
$router->get('/articles/query/{query}/{date}', ['as' => 'articles.fetchByQuery', 'uses'=>'ArticleController@fetchByQuery']);

