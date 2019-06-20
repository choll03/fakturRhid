<?php

Route::resource('items', 'ItemsController', [
	'except' => ['create', 'edit', 'show']
]);

Route::get('customers', 'CustomerController@get');

Route::get('faktur', 'FakturController@get');

