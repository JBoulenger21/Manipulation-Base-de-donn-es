<?php

include('connexion.php');

echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>";

class TableRows extends RecursiveIteratorIterator {
  function __construct($it) {
    parent::__construct($it, self::LEAVES_ONLY);
  }

  function current() {
    return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
  }

  function beginChildren() {
    echo "<tr>";
  }

  function endChildren() {
    echo "</tr>" . "\n";
  }
}
/*Afficher les gens dont le nom est Palmer*/

  $stmt = $conn->prepare("SELECT * FROM `datas` WHERE `last_name` = 'Palmer'");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }

echo "</table>";
echo "<br>";

/*Afficher les gens de sexe féminin*/

echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>";

  $stmt = $conn->prepare("SELECT * FROM `datas` WHERE `gender` = 'female'");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }

echo "</table>";
echo "<br>";

/*Afficher les gens dont le nom de l'état commence par N*/

echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>";

  $stmt = $conn->prepare("SELECT * FROM `datas` WHERE `country_code` LIKE 'N%'");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }

echo "</table>";
echo "<br>";
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>";

/*Afficher les gens dont l'adresse mail contient google'*/

  $stmt = $conn->prepare("SELECT * FROM `datas` WHERE `email` LIKE '%google%'");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }

/*
Pour rajouter une personne dans la base de donnée :
    $stmt = $conn->prepare("INSERT INTO `datas`(`first_name`, `last_name`, `email`, `gender`) VALUES ('Julie','Boulenger','boulenger.julie@gmail.com','female')");
    $stmt->execute();

Pour modifier l'adresse email :
    $stmt = $conn->prepare("UPDATE `datas` SET `email` = 'j.boulenger@codeur.online' WHERE `email`='boulenger.julie@gmail.com'");
    $stmt->execute();

Pour supprimer la nouvelle personne :
    $stmt = $conn->prepare("DELETE FROM `datas` WHERE `last_name` = 'Boulenger'");
    $stmt->execute();

*/

// Compter le nombre de femmes et d'hommes :

echo "</table>";
echo "<br>";
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Nombre de femmes</th></tr>";
/*Afficher le nombre de femmes*/

  $stmt = $conn->prepare("SELECT COUNT(gender) FROM `datas` WHERE `gender`='female'");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }

echo "</table>";
echo "<br>";
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Nombre d'hommes</th></tr>";

/*Afficher le nombre d'hommes*/

  $stmt = $conn->prepare("SELECT COUNT(gender) FROM `datas` WHERE `gender`='male'");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }


echo "</table>";
echo "<br>";
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Prenom</th><th>Nom</th><th>Age</th></tr>";

/*Afficher l'age des gens*/

  $stmt = $conn->prepare("SELECT `first_name`, `last_name`, TRUNCATE(DATEDIFF(date(now()), CONVERT(CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)), date))/365,0) as age from datas");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }

echo "</table>";
echo "<br>";
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Prenom</th><th>Nom</th><th>Age</th></tr>";
/*Afficher l'age des gens*/

  $stmt = $conn->prepare("SELECT `first_name`, `last_name`, TRUNCATE(DATEDIFF(date(now()), CONVERT(CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)), date))/365,0) as age from datas");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }

echo "</table>";
echo "<br>";
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Moyenne age homme</th><th>Moyenne age femme</th></tr>";
/*Afficher la moyenne d'age des gens en fonction de leur genre*/

  $stmt = $conn->prepare("SELECT (SELECT AVG(TRUNCATE(DATEDIFF(date(now()), CONVERT(CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)), date))/365,0)) from datas where `gender`='male') as age_homme, (SELECT AVG(TRUNCATE(DATEDIFF(date(now()), CONVERT(CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)), date))/365,0)) FROM datas WHERE `gender`='female') as age_femme");
  $stmt->execute();

  // set the resulting array to associative
  $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
  foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
  }


//afficher l'âge de chaque personne puis la moyenne d'âge des femmes et des hommes
// récupérer la date de naissance de tout le monde dans la base de données
//$date_naissance = $bdd->query("SELECT `birth_date` FROM 'datas'");

// Calcule l'âge à partir d'une date de naissance jj/mm/aaaa
/*function Age($date_naissance)
{
$am = explode('/', $date_naissance);
$an = explode('/', date('d/m/Y'));
if(($am[1] < $an[1]) || (($am[1] == $an[1]) && ($am[0] <= $an[0]))) return $an[2] - $am[2];
return $an[2] - $am[2] - 1;
}*/
$conn = null;
echo "</table>";
 ?>
