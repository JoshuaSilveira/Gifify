# Welcome to Gifify!

This is a php app that uses the spotify and gyfcat apis to retrieve gifs based on a song or an artist!


# Redirect URI

The spotify redirect uri is set to `http:localhost/gifify/callback.php`

## To run

Run this like any other php app. 
>Drop the folder in to htdocs in MAMP or similar.<br> 
>Run MAMP or similar.<br> 
>Navigate to `http://localhost/gifify/` or `http://localhost/gifify/index.php`

## Some notes

used [https://github.com/jwilsson/spotify-web-api-php](https://github.com/jwilsson/spotify-web-api-php)
>installed using composer
>gifify uses the default authentication from this PHP api spotify wrapper


The gyfcat search is simple `https://api.gfycat.com/v1/gfycats/search?search_text=`


## Styling

[https://getbootstrap.com/](https://getbootstrap.com/)
with spotify styling

