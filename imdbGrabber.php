<?php

/**
 * PHP IMDB GRABBER CLASS
 *
 * This php class allows you to grab movie data from imdb.com
 *
 * IF you liked this class & you want me to keep updating it , donate me $1 via paypal : mrjk990@gmail.com
 *
 *
 * @author  Mahmoud j.k <mrjk990@gmail.com
 * @license https://opensource.org/licenses/MIT The MIT License
 * @link    https://github.com/mrjk990/IMDB_GRABBER GitHub Repository
 * @version 1.0.1
 *
*/
 
class imdbGrabber
{
	/** 
	 * @var string / movie page content
	*/
	public $html;
	
	/** 
	 * @var string / imdb url
	*/
	public $imdb = 'https://www.imdb.com/title';
	
	/** 
	 * @var bool / error in grabbing
	*/
	public $error = false;
	
	/** 
	 * @var string / error message
	*/
	public $errorMessage = null;
	
	/** 
	 * @var bool / error in curl
	*/
	public $curlHasError = false;
	
	/** 
	 * @var string /curl error message
	*/
	public $curlErrorMessage = null;
	
	/** 
	 * @var array / default movie data
	*/
	public $data = array(
		'id'			=> null,
		'title'			=> null,
		'description'	=> null,
		'year'			=> null,
		'release_date'	=> null,
		'status'		=> 'unreleased',
		'rated' 		=> 'PG',
		'rating' 		=> null,
		'votes' 		=> null,
		'duration' 		=> null,
		'duration_time'	=> null,
		'poster' 		=> null,
		'poster_large'	=> null,
		'poster_full'	=> null,
		'country' 		=> null,
		'language' 		=> null,
		'genre'			=> [],
		'actor' 		=> [],
		'genre'			=> [],
		'writer' 		=> [],
		'director' 		=> [],
		'trailer' 		=> null,
		'trailer_embed'		=> null,
	);

	public function __construct()
	{
        if (!function_exists('curl_init')) {
            $this->error = true;
			$this->errorMessage = 'You need to enable the PHP cURL extension.';
        }
	}

	public function hasError(){
		return $this->error != false;
	}

	public function getError(){
		return $this->errorMessage;
	}

	public function dataJson(){
		return json_encode($this->data, JSON_PRETTY_PRINT);
	}
	
	public function get($id)
	{
		if (!preg_match("/(tt[0-9]+)/i", $id, $matched)) {
			$this->error = true;
			$this->errorMessage = 'Incorrect IMDb ID.';
			return false;
		}
		
		$this->getById($matched[1]);
	}
	
	public function getById($id)
	{
		$this->curl( "{$this->imdb}/{$id}/" );
		
		if ($this->curlHasError == true) {
			$this->error = true;
			$this->errorMessage = $this->curlErrorMessage;
			return false;
		}
		
		$this->setId();
		$this->setTitle();
		$this->setYear();
		$this->setRated();
		$this->setRating();
		$this->setVotes();
		$this->setGenres();
		$this->setPoster();
		$this->setActor();
		$this->setWriter();
		$this->setCountry();
		$this->setLanguage();
		$this->setDirector();
		$this->setDuration();
		$this->setReleaseDate();
		$this->setDescription();
		$this->setTrailer();
		
		return true;
	}
	
	
	public function setId()
	{
		if (preg_match('/<meta property=\'og:title\' content=\"(.*?)\s\([0-9]{4}\).*?\"\s\/>/s', $this->html, $matched)) {
			$this->data['id'] = $matched[1];
		}
	}
	
	public function setTitle()
	{
		if (preg_match('/<meta property=\'og:title\' content="(.*)\s\([0-9]{4}\)" \/>/s', $this->html, $matched)) {
			$this->data['title'] = trim($matched[1]);
		}
	}

	public function setYear()
	{
		if (preg_match('/<meta property=\'og:title\' content=".*\s\(([0-9]{4})\)" \/>/s', $this->html, $matched)) {
			$this->data['year'] = trim($matched[1]);
		}
	}
	
	public function setDescription()
	{
		if (preg_match('/<div class="inline canwrap" itemprop="description">.*?<p>(.*?)<em class="nobr">/ms', $this->html, $matched)) {
			$this->data['description'] = trim($matched[1]);
		}
	}
	
	public function setRated()
	{
		if (preg_match('/<meta itemprop="contentRating" content="([^"]+)">/s', $this->html, $matched)) {
			$this->data['rated'] = trim($matched[1]);
		}
	}

	public function setDuration()
	{
		if (preg_match('/<time itemprop="duration" datetime=".*">([^<]+)<\/time>/ms', $this->html, $matched)) {
			$this->data['duration'] = $matched[1];
			$this->data['duration_time'] = date('H:i:s', mktime(0, intval($matched[1])));
		}
	}
	
	public function setReleaseDate()
	{
		if (preg_match('/<meta itemprop="datePublished" content="([^"]+)" \/>/s', $this->html, $matched)) {
			$this->data['status'] = 'released';
			$this->data['release_date'] = trim($matched[1]);
		}
	}
	
	public function setRating()
	{
		if (preg_match('/<span itemprop="ratingValue">(\d\.\d)<\/span>/ms', $this->html, $matched)) {
			$this->data['rating'] = trim($matched[1]);
		}
	}
	
	public function setVotes()
	{
		if (preg_match('/<span class="small" itemprop="ratingCount">(.*?)<\/span>/ms', $this->html, $matched)) {
			$this->data['votes'] = trim($matched[1]);
		}
	}
	
	public function setPoster()
	{
		if (preg_match('/<img.*?title=".*?Poster".*?src="([^"]+)".*?\/>/ms', $this->html, $matched)) {
			$this->data['poster'] = preg_replace('/(\._V.*)(?=\.jpg)/', '._V1_SX250', trim($matched[1]));
			$this->data['poster_large'] = preg_replace('/(\._V.*)(?=\.jpg)/', '._V1_SX450', trim($matched[1]));
			$this->data['poster_full']  = preg_replace('/(\._V.*)(?=\.jpg)/', '', trim($matched[1]));
		}
	}
	
	public function setLanguage()
	{
		if (preg_match('/<h4 class="inline">Language:<\/h4>.*?<a.*?>(.*?)<\/a>/ms', $this->html, $matched)) {
			$this->data['language'] = trim($matched[1]);
		}
	}
	
	public function setCountry()
	{
		if (preg_match('/<h4 class="inline">Country:<\/h4>.*?<a.*?>(.*?)<\/a>/ms', $this->html, $matched)) {
			$this->data['country'] = trim($matched[1]);
		}
	}	
	
	public function setDirector()
	{
		if (preg_match('/<h4 class="inline">Director:<\/h4>(.*?)<\/div>/ms', $this->html, $matched)) {
			if (preg_match_all('/<span class="itemprop" itemprop="name">([^<]+)<\/span>/ms', $matched[1], $directors)) {
				$directors = array_filter($directors[1]);
				foreach ($directors as $director) {
					$this->data['director'][] = trim($director);
				}
			}
		}
	}
	
	
	public function setWriter()
	{
		if (preg_match('/<h4 class="inline">Writers:<\/h4>(.*?)<\/div>/ms', $this->html, $matched)) {
			if (preg_match_all('/<span class="itemprop" itemprop="name">([^<]+)<\/span>/ms', $matched[1], $writers)) {
				$writers = array_filter($writers[1]);
				foreach ($writers as $writer) {
					$this->data['writer'][] = trim($writer);
				}
			}
		}
	}

	public function setActor()
	{
		if (preg_match('/<table class="cast_list">(.*?)<\/table>/ms', $this->html, $matched)) {
			if (preg_match_all('/<span class="itemprop" itemprop="name">([^<]+)<\/span>/ms', $matched[1], $actors)) {
				$actors = array_filter($actors[1]);
				foreach ($actors as $actor) {
					$this->data['actor'][] = trim($actor);
				}
			}
		}
	}

	public function setGenres()
	{
		if (preg_match_all('/<span class="itemprop" itemprop="genre">([\w-]+)<\/span>/ms', $this->html, $matched)) {
			$genres = array_filter($matched[1]);
			foreach ($genres as $genre) {
				$this->data['genre'][] = trim($genre);
			}
		}
	}

	public function setTrailer()
	{
 		$youTube  = 'https://www.youtube.com/results?search_query=';
		$youTube .= urlencode(trim($this->data['title']));
		$youTube .= '+official+trailer&gl=US';

		$this->curl($youTube);
		
		if (!$this->curlHasError) {
			if(preg_match_all('/"\/watch\?v=([^"]+)"/ms', $this->html, $matched)) {
				// get first match 
				$this->data['trailer'] = "https://www.youtube.com/watch?v=".trim($matched[1][0]);
				$this->data['trailer_embed'] = "https://www.youtube.com/embed/".trim($matched[1][0]);
			}
		}
	}

	public function curl( $url, $header = FALSE, $timeout = 25, $referer = "https://www.google.com" )
	{
		$ch = curl_init();
		
		$randomIP = rand(0,255).'.'.rand(0,255).'.'.rand(0,255).'.'.rand(0,255);
		$userAgent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36";

		curl_setopt($ch, CURLOPT_URL, trim($url));
		curl_setopt($ch, CURLOPT_HEADER, $header);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ["REMOTE_ADDR: {$randomIP}", "HTTP_X_FORWARDED_FOR: {$randomIP}"]);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		curl_setopt($ch, CURLOPT_ENCODING , "");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		curl_setopt($ch, CURLOPT_REFERER, $referer);

		$result['html'] = curl_exec($ch);
		$result['info'] = curl_getinfo($ch);
		$result['http_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$this->html = $result['html'];
		$this->infoCode = $result['info'];
		$this->httpCode = $result['http_code'];
		
		if( empty($result['html']) ) {
			$this->curlHasError = true;
			$this->curlErrorMessage = 'IMDb give Empty Result';
		}
		
		if( $result['http_code'] != 200 ) {
			$this->curlHasError = true;
			$this->curlErrorMessage = "IMDb give Page Error [{$result['http_code']}]";
		}
	
		curl_close($ch);
		
		return $result;
	}
}
?>
