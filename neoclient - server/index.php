<?php
use Silex\Application;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\Yaml\Yaml;
use Neoxygen\NeoClient\ClientBuilder;

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