<?php

Route::resource('items', 'ItemsController', [
	'except' => ['create', 'edit', 'show']
]);

Route::get('faktur', 'FakturController@get');

