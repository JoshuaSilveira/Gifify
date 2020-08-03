<?php
require 'vendor/autoload.php';
ini_set("allow_url_fopen", 1);

$api = new SpotifyWebAPI\SpotifyWebAPI();
session_start();
// Fetch the saved access token from somewhere. A database for example.
//get that accesstoken that was set in callback.php
$api->setAccessToken($_SESSION['access']);

// It's now possible to request data about the currently authenticated user
//var_dump($api->me());


// Getting Spotify catalog data is of course also possible

//var_dump($api->getTrack('7EjyzZcbLxW7PaaLua9Ksb'));

if(isset($_POST['searchSong'])){
    //echo $_POST['search'];
    $options = ['market'=>'US','limit'=>1,];
    $results=$api->search($_POST['search'],'track,artist',$options);

    //var_dump($results->tracks->items[0]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gifify</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container mt-5">
    
    <h1>Hello <? echo $api->me()->display_name;?></h1>

    <h2><i>"Gifify"</i> a song or an artist or both</h2>

    <p>Some example searchwords 'Drake', 'Hello Adele', 'This Too Shall Pass', 'Eminem'.</p>
    <form  action="app.php" method="post">
        <div class="form-group">
            <input type="text" class="form-control" name="search">
            <input type="submit" class="btn btn-success" name="searchSong" value="search">
         </div>
        
    </form>

    <?php
    if(isset($_POST['searchSong'])){
        foreach($results->tracks->items as $songs){
            //echo '<div>';
            //echo $songs->album->images[0]->url;
            echo '<div class="card w-100" style="width: 20rem;">';
                echo '<div class="row no-gutters">';
                echo '<div class="col-auto">';
                    echo '<img src="'.$songs->album->images[0]->url.'" class="img-fluid" style="width: 10rem;">';
                echo '</div>';

                echo '<div class="col">';
                echo '<div class="card-body">';
                    echo '<h5 class="card-title">'.$songs->name.'</h5>';
                    $name=str_replace(" ","-",$songs->name);
                    //only one artist credited to this song
                    if(count($songs->artists)==1){
                        echo '<h6 class="card-text">by Artist: ';
                        echo $songs->artists[0]->name;
                        echo '</h6>';
                        
                        echo '</div>';
                        $artname = str_replace(" ","-",$songs->artists[0]->name);
                        echo '<div class="col">';
                        echo '<a class="btn btn-primary" href="https://genius.com/'.$artname.'-'.$name.'-lyrics">Lyrics</a>';
                        echo '</div>';
                        //here we try to query the gfycat api
                        //seems easier than curl
                        //but we have to scrub the searchkeys for spaces and replace them with '+'s
                        $gifjson = file_get_contents('https://api.gfycat.com/v1/gfycats/search?search_text='.str_replace(" ","+",$songs->name).'+'.str_replace(" ","+",$songs->artists[0]->name));
                            $gifjson = json_decode($gifjson);
                        //var_dump($gifjson->gfycats);
                        
                    }
                    else{
                        echo '<h6 class="card-text">by Artists: ';
                            //list each artist credited to the song
                        foreach($songs->artists as $artist){
                            
                                echo $artist->name.'<br>';
                                //echo '<a href="https://genius.com/"'.$artist->name.'"-"'.$name.'"-lyrics">Lyrics</a>';
                            } 
                            echo '</h6>';
                        
                            echo '</div>';
                            echo '<div class="col">';
                            //something extra tried to use genius.com to get lyrics
                            echo '<a class="btn btn-primary" href="https://genius.com/"'.$songs->artists[0]->name.'"-"'.$name.'"-lyrics">Lyrics</a>';
                            echo '</div>';
                            $gifjson = file_get_contents('https://api.gfycat.com/v1/gfycats/search?search_text='.$name.'+'.$songs->artists[0]->name);
                                $gifjson = json_decode($gifjson);
                            
                        }
            
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
            
            echo '<div class="row no-gutters mt-3">';
            foreach($gifjson->gfycats as $gifs){
                echo '<div class="col-md-4"><div class="thumbnail">'; 
                //used 1mb gifs so the browser isnt overwhelmed
                echo '<a title="'.$gifs->title.'"><img src="'.$gifs->max1mbGif.'" alt="'.$gifs->title.'"style="width:100%;">';
                echo '</div></div>';
            }
            echo '</div>';
            echo '</div>';
       
       }
    } 
    ?>
    
    </div>
    <footer class="container">Using the spotify api to search artist names and tracks and then quering gyfcat api with the trackname+artistname for gifs!</footer>
</body>
</html>