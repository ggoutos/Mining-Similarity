<?php

$app->get('/', function () {
    return file_get_contents(__DIR__.'/static/index.html');
});




$app->post('/update', function() use ($neo4j) {
	
	
    $q = 'MATCH (u:User { id: "'.$_POST["id"].'"})
		  DETACH DELETE u';
    
    
    $result = $neo4j->sendCypherQuery($q)->getResult();
    
    $response = new JsonResponse();
    $response->setData($result);

    return $response;
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




$app->post('/pages', function() use ($neo4j) {

	
 for ($i=0; $i<count($_POST['pages']); $i++) {
 
 if (isset($_POST["pages"][$i]["category"]) && $_POST["pages"][$i]["category"]!="Musician, Band" && $_POST["pages"][$i]["category"]!="Movie") {
	
	$q = 
	'MATCH (n {name: "'.$_POST["pages"][$i]["name"].'"})
	SET n:Page
	RETURN n
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	
	$q = 
	'MERGE (m:Page{name: "'.$_POST["pages"][$i]["name"].'"})
	RETURN m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'
	MATCH (u:User { id: "'.$_POST["id"].'" }), (m:Page {name: "'.$_POST["pages"][$i]["name"].'"})
	CREATE UNIQUE (u)-[r:LIKES]->(m)
	RETURN u,r,m
	
	';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	
	//category
	if (isset($_POST["pages"][$i]["category"])) {
	$q = 
	'MERGE (m:Category{name: "'.$_POST["pages"][$i]["category"].'"})
	RETURN m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'
	MATCH (u:Category { name: "'.$_POST["pages"][$i]["category"].'" }), (m:Page {name: "'.$_POST["pages"][$i]["name"].'"})
	CREATE UNIQUE (m)-[r:IS]->(u)
	RETURN u,r,m
	
	';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	}
	
	}
	
 }
	
    $response = new JsonResponse();
    $response->setData($result);

    return $response;
	
});




$app->post('/places', function() use ($neo4j) {

	
 for ($i=0; $i<count($_POST['places']); $i++) {
	
	$q = 
	'MATCH (n {name: "'.$_POST["places"][$i]["place"]["name"].'"})
	SET n:Place
	RETURN n
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	
	$q = 
	'MERGE (m:Place{name: "'.$_POST["places"][$i]["place"]["name"].'"})
	RETURN m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'
	MATCH (u:User { id: "'.$_POST["id"].'" }), (m:Place {name: "'.$_POST["places"][$i]["place"]["name"].'"})
	CREATE UNIQUE (u)-[r:CHECKED_IN]->(m)
	RETURN u,r,m
	
	';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	if (isset($_POST['places'][$i]["place"]["category_list"])) {
	
	//category
	for ($j=0; $j<count($_POST['places'][$i]["place"]["category_list"]); $j++) {
	
	$q = 
	'MERGE (m:Place_Genre{name: "'.$_POST["places"][$i]["place"]["category_list"][$j]["name"].'"})
	RETURN m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'
	MATCH (u:Place_Genre { name: "'.$_POST["places"][$i]["place"]["category_list"][$j]["name"].'" }), (m:Place {name: "'.$_POST["places"][$i]["place"]["name"].'"})
	CREATE UNIQUE (m)-[r:IS]->(u)
	RETURN u,r,m
	
	';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	}
	}
 }
	
    $response = new JsonResponse();
    $response->setData($result);

    return $response;
	
});





$app->post('/music', function() use ($neo4j) {


	
	for ($i=0; $i<count($_POST['mname']); $i++) {
	
	$q = 
	'MATCH (n {name: "'.$_POST["mname"][$i].'"})
	SET n:Music
	RETURN n
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();		
	
	$q = 
	'MERGE (m:Music{name: "'.$_POST["mname"][$i].'"})
	RETURN m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'
	MATCH (u:User { id: "'.$_POST["id"].'" }), (m:Music {name: "'.$_POST["mname"][$i].'"})
	CREATE UNIQUE (u)-[r:LIKES]->(m)
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
	'MATCH (n {name: "'.$_POST["mname"][$i].'"})
	SET n:Movie
	RETURN n
	';
	
	
	$q = 
	'MERGE (m:Movie{name: "'.$_POST["mname"][$i].'"})
	RETURN m
	';
    
	$result = $neo4j->sendCypherQuery($q)->getResult();
	
	$q = 
	'
	MATCH (u:User { id: "'.$_POST["id"].'" }), (m:Movie {name: "'.$_POST["mname"][$i].'"})
	CREATE UNIQUE (u)-[r:LIKES]->(m)
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

	
/////me	
	$q = 
	'MATCH (u:User {name: "'.$_POST["name"].'"})--(m:Page)--(g:Category)
	RETURN g.name AS genre, count(DISTINCT m.name) AS likes ORDER BY likes DESC';
	
	$genre = $neo4j->sendCypherQuery($q)->getResult();
	$me[] = $genre->getTableFormat();
	

	$q = 
	'MATCH (u:User {name: "'.$_POST["name"].'"})-[CHECKED_IN]-(m:Place)--(g:Place_Genre)
	RETURN g.name AS genre, count(DISTINCT m.name) AS likes ORDER BY likes DESC';
	
	$genre = $neo4j->sendCypherQuery($q)->getResult();
	$me[] = $genre->getTableFormat();
	
	
	$q = 
	'MATCH (u:User {name: "'.$_POST["name"].'"})--(m:Music)--(g:Music_Genre)
	RETURN g.name AS genre, count(DISTINCT m.name) AS likes ORDER BY genre ASC';
	
	$genre = $neo4j->sendCypherQuery($q)->getResult();
	$me[] = $genre->getTableFormat();
	
	
	$q = 
	'MATCH (u:User {name: "'.$_POST["name"].'"})--(m:Movie)--(g:Movie_Genre)
	RETURN g.name AS genre, count(DISTINCT m.name) AS likes ORDER BY genre ASC';
	
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
	
	
	//MATCH (u:User {name: "Eva Zografaki"})--(m)--(g:Category)
	//OPTIONAL MATCH (p:User {name: "Giwrgos Goutos"})--(k)--(u), (k)--(g)
	//RETURN g.name AS genre, count(DISTINCT m.name) AS likes, count(DISTINCT k.name) AS count ORDER BY likes DESC
	
	
	for ($i=0; $i<count($friends); $i++) {
	
	$q =
	'MATCH (u:User {name: "'.$friends[$i]["friend"]["name"].'"})--(m:Page)--(g:Category)
	OPTIONAL MATCH (p:User {name: "'.$_POST["name"].'"})--(k:Page)--(u), (k:Page)--(g)
	RETURN g.name AS genre, count(DISTINCT m.name) AS likes, count(DISTINCT k.name) AS common ORDER BY likes DESC';
	
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	$genres[$i+1][] = $result->getTableFormat();
	
	
	$q =
	'MATCH (u:User {name: "'.$friends[$i]["friend"]["name"].'"})-[CHECKED_IN]-(m:Place)--(g:Place_Genre)
	OPTIONAL MATCH (p:User {name: "'.$_POST["name"].'"})--(k:Place)--(u), (k:Place)--(g)
	RETURN g.name AS genre, count(DISTINCT m.name) AS likes, count(DISTINCT k.name) AS common ORDER BY likes DESC';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	$genres[$i+1][] = $result->getTableFormat();
	
	
	$q =
	'MATCH (u:User {name: "'.$friends[$i]["friend"]["name"].'"})--(m:Music)--(g:Music_Genre)
	OPTIONAL MATCH (p:User {name: "'.$_POST["name"].'"})--(k:Music)--(u), (k:Music)--(g)
	RETURN g.name AS genre, count(DISTINCT m.name) AS likes, count(DISTINCT k.name) AS common ORDER BY likes DESC';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	$genres[$i+1][] = $result->getTableFormat();
	
	
	
	$q =
	'MATCH (u:User {name: "'.$friends[$i]["friend"]["name"].'"})--(m:Movie)--(g:Movie_Genre)
	OPTIONAL MATCH (p:User {name: "'.$_POST["name"].'"})--(k:Movie)--(u), (k:Movie)--(g)
	RETURN g.name AS genre, count(DISTINCT m.name) AS likes, count(DISTINCT k.name) AS common ORDER BY likes DESC';
	
	$result = $neo4j->sendCypherQuery($q)->getResult();
	$genres[$i+1][] = $result->getTableFormat();
	$genres[$i+1][] = $friends[$i]["friend"];
	
	}
	
	
    $response = new JsonResponse();
    $response->setData($genres);
    return $response;
	
});


$app->post('/checkFriend', function(Request $request) use ($neo4j) {



$q =  'MATCH (u:User {name: "'.$_POST["me"].'"})--(g)--(p:User {name: "'.$_POST["friend"].'"}), (g)--({name: "'.$_POST["genre"].'"})
RETURN DISTINCT g.name AS name';

	
$genre = $neo4j->sendCypherQuery($q)->getResult();
$genres = $genre->getTableFormat();


$response = new JsonResponse();
$response->setData($genres);
return $response;
});


$app->run();