<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\Yaml\Yaml;
use Neoxygen\NeoClient\ClientBuilder;

header("Access-Control-Allow-Origin: *");

require __DIR__.'/vendor/autoload.php';

$app = new Application();

if (false !== getenv('GRAPHSTORY_URL')) {
    $cnx = parse_url(getenv('GRAPHSTORY_URL'));
} else {
    $config = Yaml::parse(file_get_contents(__DIR__.'/config/config.yml'));
    $cnx = parse_url($config['neo4j_url']);
}

$neo4j = ClientBuilder::create()
    ->addConnection('default', $cnx['scheme'], $cnx['host'], $cnx['port'], true, $cnx['user'], $cnx['pass'])
    ->setAutoFormatResponse(true)
    ->setDefaultTimeout(20)
    ->build();

$app->get('/', function () {
    return file_get_contents(__DIR__.'/static/index.html');
});

$app->get('/next', function () {
    return file_get_contents(__DIR__.'/static/next.html');
});





$app->post('/create', function() use ($neo4j) {
	
	
    $q = 'MERGE (ee:Person{name: "'.$_POST['name'].'", from: "Greece"})
          RETURN ee';
    
    
    $result = $neo4j->sendCypherQuery($q)->getResult();
    
    $response = new JsonResponse();
    $response->setData($result);

    return $response;
});


$app->post('/login', function() use ($neo4j) {

	
    $q = 
	
	'MATCH (u:User { id: "'.$_POST["id"].'"})
	SET u.name="'.$_POST["name"].'", u.first="'.$_POST["first"].'", u.last="'.$_POST["last"].'", u.email="'.$_POST["email"].'"';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'MERGE (uu:User{id: "'.$_POST["id"].'", name: "'.$_POST["name"].'", first: "'.$_POST["first"].'", last: "'.$_POST["last"].'", email: "'.$_POST["email"].'"})
    RETURN uu';
    
    $result = $neo4j->sendCypherQuery($q)->getResult();
	
	
	for ($i=0; $i<count($_POST['friends']); $i++) {
	
	$q = 
	'MATCH (u:User {name: "'.$_POST["name"].'"}), (y:User {name: "'.$_POST["friends"][$i]["name"].'"})
	CREATE UNIQUE (u)-[r:IS_FRIEND_WITH]-(y)
	RETURN u,r,y';
	
	 $result = $neo4j->sendCypherQuery($q)->getResult();
	}
    
    $response = new JsonResponse();
    $response->setData($result);

    return $response;
	
});




$app->post('/music', function() use ($neo4j) {


	
	for ($i=0; $i<count($_POST['mname']); $i++) {
	
	$q = 
	'MERGE (m:Music{name: "'.$_POST["mname"][$i].'"})
	RETURN m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'
	MATCH (u:User { id: "'.$_POST["id"].'" }), (m:Music {name: "'.$_POST["mname"][$i].'"})
	CREATE UNIQUE (u)-[r:LIKES_MUSIC]->(m)
	RETURN u,r,m
	
	';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	
	//tag1
	$q = 
	'MERGE (g:Music_Genre{name: "'.$_POST["tag1"][$i].'"})
	RETURN g
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'MATCH (g:Music_Genre {name: "'.$_POST["tag1"][$i].'"}), (m:Music {name: "'.$_POST["mname"][$i].'"})
	 CREATE UNIQUE (m)-[r:IS {count: "'.$_POST["count1"][$i].'"}]->(g)
	 RETURN g,r,m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	
	//tag2
	$q = 
	'MERGE (g:Music_Genre{name: "'.$_POST["tag2"][$i].'"})
	RETURN g
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'MATCH (g:Music_Genre {name: "'.$_POST["tag2"][$i].'"}), (m:Music {name: "'.$_POST["mname"][$i].'"})
	 CREATE UNIQUE (m)-[r:IS {count: "'.$_POST["count2"][$i].'"}]->(g)
	 RETURN g,r,m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	//tag3
	$q = 
	'MERGE (g:Music_Genre{name: "'.$_POST["tag3"][$i].'"})
	RETURN g
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'MATCH (g:Music_Genre {name: "'.$_POST["tag3"][$i].'"}), (m:Music {name: "'.$_POST["mname"][$i].'"})
	 CREATE UNIQUE (m)-[r:IS {count: "'.$_POST["count3"][$i].'"}]->(g)
	 RETURN g,r,m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	
	}
	
    
    $response = new JsonResponse();
    $response->setData($result);

    return $response;
	
});



$app->post('/movies', function() use ($neo4j) {


	
	for ($i=0; $i<count($_POST['mname']); $i++) {
	
	$q = 
	'MERGE (m:Movie{name: "'.$_POST["mname"][$i].'"})
	RETURN m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'
	MATCH (u:User { id: "'.$_POST["id"].'" }), (m:Movie {name: "'.$_POST["mname"][$i].'"})
	CREATE UNIQUE (u)-[r:LIKES_MOVIE]->(m)
	RETURN u,r,m
	
	';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	
	//tag1
	$q = 
	'MERGE (g:Movie_Genre{name: "'.$_POST["tag1"][$i].'"})
	RETURN g
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'MATCH (g:Movie_Genre {name: "'.$_POST["tag1"][$i].'"}), (m:Movie {name: "'.$_POST["mname"][$i].'"})
	 CREATE UNIQUE (m)-[r:IS]->(g)
	 RETURN g,r,m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	//tag2
	$q = 
	'MERGE (g:Movie_Genre{name: "'.$_POST["tag2"][$i].'"})
	RETURN g
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'MATCH (g:Movie_Genre {name: "'.$_POST["tag2"][$i].'"}), (m:Movie {name: "'.$_POST["mname"][$i].'"})
	 CREATE UNIQUE (m)-[r:IS]->(g)
	 RETURN g,r,m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	//tag3
	$q = 
	'MERGE (g:Movie_Genre{name: "'.$_POST["tag3"][$i].'"})
	RETURN g
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'MATCH (g:Movie_Genre {name: "'.$_POST["tag3"][$i].'"}), (m:Movie {name: "'.$_POST["mname"][$i].'"})
	 CREATE UNIQUE (m)-[r:IS]->(g)
	 RETURN g,r,m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	}
	
    
    $response = new JsonResponse();
    $response->setData($result);

    return $response;
	
});



$app->post('/similarity', function(Request $request) use ($neo4j) {

/////MATCH (u:User {name: "Giwrgos Goutos"})--(m:Movie)--(g:Movie_Genre)--(l:Movie)--(User {name: "Myrto Goutou"})
/////RETURN g.name AS genre, count(DISTINCT m) AS me, count(DISTINCT l) AS friend ORDER BY genre ASC
	
/////me	
	$q = 
	'MATCH (u:User {name: "'.$_POST["name"].'"})--(m)--(g:Music_Genre)
	RETURN g.name AS genre, count(*) AS likes ORDER BY genre ASC';
	
	$genre = $neo4j->sendCypherQuery($q)->getResult();
	$me[] = $genre->getTableFormat();
	
	
	$q = 
	'MATCH (u:User {name: "'.$_POST["name"].'"})--(m)--(g:Movie_Genre)
	RETURN g.name AS genre, count(*) AS likes ORDER BY genre ASC';
	
	$genre = $neo4j->sendCypherQuery($q)->getResult();
	$me[] = $genre->getTableFormat();
	
	
	$q = 'MATCH (u:User {name: "'.$_POST["name"].'"})
	RETURN u AS me';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();	
	$help = $result->getTableFormat();
	$me[] = $help[0]["me"];
	
	$genres[] = $me;

//////friends
   $q =
   'MATCH (u:User {name: "'.$_POST["name"].'"})-[r:IS_FRIEND_WITH]-(m)
	RETURN  m AS friend';

	$result = $neo4j->sendCypherQuery($q)->getResult();	
	
	$friends = $result->getTableFormat();
	
	
	
	for ($i=0; $i<count($friends); $i++) {
	
	$q = 
	'MATCH (u:User {name: "'.$friends[$i]["friend"]["name"].'"})--(m)--(g:Music_Genre)
	RETURN g.name AS genre, count(*) AS likes ORDER BY genre ASC';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	$genres[$i+1][] = $result->getTableFormat();
	
	
    $q = 
	'MATCH (u:User {name: "'.$friends[$i]["friend"]["name"].'"})--(m)--(g:Movie_Genre)
	RETURN g.name AS genre, count(*) AS likes ORDER BY genre ASC';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	$genres[$i+1][] = $result->getTableFormat();
	$genres[$i+1][] = $friends[$i]["friend"];
	
	}
	
	
    $response = new JsonResponse();
    $response->setData($genres);
    return $response;
	
});


$app->post('/checkFriend', function(Request $request) use ($neo4j) {

/////me	
	$q = 
	'MATCH (u:User {id: "'.$_POST["me"].'"})--(m)--(g:Music_Genre)
	RETURN g.name AS genre, count(*) AS likes ORDER BY genre ASC';
	
	$genre = $neo4j->sendCypherQuery($q)->getResult();
	$me[] = $genre->getTableFormat();
	
	
	$q = 
	'MATCH (u:User {id: "'.$_POST["me"].'"})--(m)--(g:Movie_Genre)
	RETURN g.name AS genre, count(*) AS likes ORDER BY genre ASC';
	
	$genre = $neo4j->sendCypherQuery($q)->getResult();
	$me[] = $genre->getTableFormat();
	
	
	$q = 'MATCH (u:User {id: "'.$_POST["me"].'"})
	RETURN u AS me';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();	
	$help = $result->getTableFormat();
	$me[] = $help[0]["me"];
	
	$genres[] = $me;

//////friend
   $q =
   'MATCH (u:User {id: "'.$_POST["friend"].'"})
	RETURN  u AS friend';

	$result = $neo4j->sendCypherQuery($q)->getResult();	
	
	$friends = $result->getTableFormat();
	
	
	
	
	
	$q = 
	'MATCH (u:User {name: "'.$friends[0]["friend"]["name"].'"})--(m)--(g:Music_Genre)
	RETURN g.name AS genre, count(*) AS likes ORDER BY genre ASC';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	$genres[1][] = $result->getTableFormat();
	
	
    $q = 
	'MATCH (u:User {name: "'.$friends[0]["friend"]["name"].'"})--(m)--(g:Movie_Genre)
	RETURN g.name AS genre, count(*) AS likes ORDER BY genre ASC';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	$genres[1][] = $result->getTableFormat();
	$genres[1][] = $friends[0]["friend"];
	
	
	
	
    $response = new JsonResponse();
    $response->setData($genres);
    return $response;
	
});



$app->get('/search', function (Request $request) use ($neo4j) {
    $a = $request->get('a');
	$b = $request->get('b');
    
    $query = 'MATCH (u:User {name: "'.$a.'"})--(m)--(g:Movie_Genre)
	RETURN g.name AS EIDOS , count(*) AS AKMES ORDER BY EIDOS ASC';
    
	$result = $neo4j->sendCypherQuery($query)->getResult();
   
	$genres = $result->getTableFormat();
	
    $response = new JsonResponse();
    $response->setData($genres);
    return $response;
});


$app->get('/searchhhhhhhhh', function (Request $request) use ($neo4j) {
    $searchTerm = $request->get('q');
    $term = '(?i).*'.$searchTerm.'.*';
    $query = 'MATCH (m:Movie) WHERE m.title =~ {term} RETURN m';
    $params = ['term' => $term];
    $result = $neo4j->sendCypherQuery($query, $params)->getResult();
    $movies = [];
    foreach ($result->getNodes() as $movie){
        $movies[] = ['movie' => $movie->getProperties()];
    }
    $response = new JsonResponse();
    $response->setData($movies);
    return $response;
});


$app->get('/movie/{title}', function ($title) use ($neo4j) {
    $q = 'MATCH (m:Movie) WHERE m.title = {title} OPTIONAL MATCH p=(m)<-[r]-(a:Person) RETURN m,p';
    $params = ['title' => $title];
    $result = $neo4j->sendCypherQuery($q, $params)->getResult();
    $movie = $result->getSingleNodeByLabel('Movie');
    $mov = [
        'title' => $movie->getProperty('title'),
        'cast' => []
        ];
    foreach ($movie->getInboundRelationships() as $rel){
        $actor = $rel->getStartNode()->getProperty('name');
        $relType = explode('_', strtolower($rel->getType()));
        $job = $relType[0];
        $cast = [
            'job' => $job,
            'name' => $actor
        ];
        if (array_key_exists('roles', $rel->getProperties())){
            $cast['role'] = implode(',', $rel->getProperties()['roles']);
        } else {
            $cast['role'] = null;
        }
        $mov['cast'][] = $cast;
    }
    $response = new JsonResponse();
    $response->setData($mov);
    return $response;
});



$app->get('/import', function () use ($app, $neo4j) {
    $q = trim(file_get_contents(__DIR__.'/static/movies.cypher'));
    $neo4j->sendCypherQuery($q);

    return $app->redirect('/');
});

$app->get('/reset', function() use ($app, $neo4j) {
    $q = 'MATCH (n) OPTIONAL MATCH (n)-[r]-() DELETE r,n';
    $neo4j->sendCypherQuery($q);

    return $app->redirect('/import');

});



$app->run();