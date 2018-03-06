# PHP IMDB SCRAPER CLASS
 This php class allows you to grab movie data from imdb.com
 
# USAGE
```php
<?php
include('imdbScraper.php');
//Start the class
$imdb = new imdbScraper('tt0081059');
//Check if no errors
if (!$imdb->hasError()) {
  echo $imdb->dataJson();
} else {
  echo $imdb->getError();
}
?>
```
# RESULT
```
{
    "id": "tt0081059",
    "title": "Lion of the Desert",
    "description": "In the Fascist Italy Pre-World War II of Benito Mussolini, the cruel General Rodolfo Graziani is directly assigned by Il Duce to fight in the colonial war in Libya to vanquish the Arab nation. However, his troops are frequently defeated by the national leader Omar Mukhtar and his army of Bedouins. But the Butcher of Ethiopia and Libya uses a dirty war against the natives, slaughtering children, women and aged people, to subdue Mukhtar.",
    "year": "1980",
    "release_date": "1981-05-16",
    "rated": null,
    "rating": "8.4",
    "votes": "11,436",
    "duration": "156 min",
    "duration_time": "02:36:37",
    "poster": "https:\/\/images-na.ssl-images-amazon.com\/images\/M\/MV5BNGUxMDYzNjktNjgwZi00ZjIwLTgwNzItNDQ5Mzg5ZWU0YWRlL2ltYWdlL2ltYWdlXkEyXkFqcGdeQXVyNjc1NTYyMjg@._V1_SX250.jpg",
    "poster_large": "https:\/\/images-na.ssl-images-amazon.com\/images\/M\/MV5BNGUxMDYzNjktNjgwZi00ZjIwLTgwNzItNDQ5Mzg5ZWU0YWRlL2ltYWdlL2ltYWdlXkEyXkFqcGdeQXVyNjc1NTYyMjg@._V1_SX450.jpg",
    "poster_full": "https:\/\/images-na.ssl-images-amazon.com\/images\/M\/MV5BNGUxMDYzNjktNjgwZi00ZjIwLTgwNzItNDQ5Mzg5ZWU0YWRlL2ltYWdlL2ltYWdlXkEyXkFqcGdeQXVyNjc1NTYyMjg@.jpg",
    "country": "Libya",
    "language": "English",
    "genre": [
        "Biography",
        "Drama",
        "History"
    ],
    "actor": [
        "Anthony Quinn",
        "Oliver Reed",
        "Irene Papas",
        "Raf Vallone",
        "Rod Steiger",
        "John Gielgud",
        "Andrew Keir",
        "Gastone Moschin",
        "Stefano Patrizi",
        "Adolfo Lastretti",
        "Sky du Mont",
        "Takis Emmanuel",
        "Rodolfo Bigotti",
        "Robert Brown",
        "Eleonora Stathopoulou"
    ],
    "writer": [
        "David Butler",
        "H.A.L. Craig"
    ],
    "director": [
        "Moustapha Akkad"
    ],
    "trailer": "https:\/\/www.youtube.com\/watch?v=Rsp5beaSgzw"
}
```

