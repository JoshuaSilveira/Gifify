<?php
session_start();
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    'secret',
    'secret',
    'https://joshuasilveira.ca/gifify/callback.php'
);

$options = [
    'auto_refresh' => true,
    'scope' => [
        'playlist-read-private',
        'user-read-private',
    ],
];

header('Location: ' . $session->getAuthorizeUrl($options));
die();
?>