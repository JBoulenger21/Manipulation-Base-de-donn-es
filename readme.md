#Requete SQL pour calculer l'age quand la base de donnée ne suit pas

##Problématique
La problématique était de calculer l’âge des 1000 personnes dans la base de données importée dans phpMyAdmin. Il faut donc pouvoir comparer la date actuelle et la date de naissance des personnes de la base de données comprise dans la table 'birth_date'. Cependant, la table ‘birth_date’ était en format « varchar » et à 10 charactères, donc pour calculer l’âge, il fallait passer par plusieurs étapes que je vais expliciter ci-dessous

##Récupérer les bonnes valeurs et les remettre dans l'ordre
Pour transformer les dates de naissance en date utilisable, il faut en premier temps récupérer les valeurs des années, mois et jours et les remettre dans le bon ordre. En effet, les dates sont en format : dd/mm/YYYY, là où le format date est le suivant : YYYY-mm-dd. Pour ce faire, il faut d'abord récupérer les valeurs avec ```RIGHT```, ```LEFT``` et ```SUBSTRING``` comme suit :
```
RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)
```
Ensuite, il faut concatener les valeurs avec ```CONCAT``` :
```
CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2))
```

##Faire lire le résultat en format date valide
Il faut pour ce faire convertir le format obtenu en date avec ```CONVERT```, en précisant le type voulu :
```
CONVERT(CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)), date)
```

##Comparer les dates du jour et de naissance
Il faut comparer les dates du jour et de naissance pour dégager l'âge de la personne avec ```DATEDIFF```. Il faut savoir qu'avec MySQL, ```DATEDIFF``` ressort une valeur en jour uniquement. Pour récupérer la date du jour et la lire en format date, on utilise ```DATE(NOW())``` .
```
DATEDIFF(DATE(NOW()), CONVERT(CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)), date))/365,0)
```

##Calculer l'âge véritable de la personne
Une fois qu'on a l'âge en jour, il faut diviser par 365 afin de récupérer le nombre d'année. Le soucis, c'est qu'il y a forcément des décimales, et on ne donne pas les décimales dans son âge. Pour ça, on utilise la fonction ```TRUNCATE``` qui permet de déterminer le nombre de décimales que l'on veut après la virgule. Il suffira de lui dire qu'on n'en veut aucune, avec 0 en deuxième paramètre.
```
TRUNCATE(DATEDIFF(date(now()), CONVERT(CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)), date))/365,0)
```

##La requête finale
Donc, pour calculer l'âge des personnes dans la base de données appelée 'datas', en ne sortant un tableau composé que des prénoms, noms et d'une nouvelle colonne age, on peut écrire ça ainsi :
```
SELECT `first_name`, `last_name`, TRUNCATE(DATEDIFF(date(now()), CONVERT(CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)), date))/365,0) as age from datas
```

##Solution plus simple ?
Le problème c'est que la requête est très lourde. Le principe serait de modifier directement la base de données avec un ```UPDATE``` et de modifier le type de données dans la structure afin d'avoir directement un champ 'birth_date' utilisable. On reprendrait les premières étapes jusqu'à la conversion en date et on modifie directement la base de données comme ceci :
```
UPDATE `datas` SET `birth_date`= CONVERT(CONCAT(RIGHT(birth_date,4),SUBSTRING(birth_date,4,2),LEFT(birth_date,2)), date)
```

Une fois modifiée, on peut alors alléger notre requête comme suit :
```
SELECT `first_name`, `last_name`, TRUNCATE(DATEDIFF(date(now()), `birth_date`)/365,0) as age from datas
```
