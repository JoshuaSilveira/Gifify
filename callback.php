<?php
session_start();
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session(
    'secret',
    'secret',
    'https://joshuasilveira.ca/gifify/callback.php'
);

// Request a access token using the code from Spotify
$session->requestAccessToken($_GET['code']);

$accessToken = $session->getAccessToken();
$refreshToken = $session->getRefreshToken();

// Store the access and refresh tokens somewhere. In a database for example.
//session_start();
$_SESSION['access']=$accessToken;
// Send the user along and fetch some data!
header('Location: app.php');
die();
?>