<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 05/11/2018
 * Time: 12:04
 */
return [
    'cache_lifetime'  => env('APP_ENV') === 'production' && env('CACHE_LIFETIME') ? env('CACHE_LIFETIME', 10) : 0,

    'pagination_size' => (int)env('PAGINATION_SIZE', 10),
];