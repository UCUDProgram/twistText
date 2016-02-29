<?php
function generate_rack($n){
  $tileBag = "AAAAAAAAABBCCDDDDEEEEEEEEEEEEFFGGGHHIIIIIIIIIJKLLLLMMNNNNNNOOOOOOOOPPQRRRRRRSSSSTTTTTTUUUUVVWWXYYZ";
  $rack_letters = substr(str_shuffle($tileBag), 0, $n);
  
  $temp = str_split($rack_letters);
  sort($temp);
  return implode($temp);
};

// function generate_words($word){
//     $dbhandle = new PDO("sqlite:scrabble.sqlite") or die("Failed to open DB");
//      if (!$dbhandle) die ($error);
//     $query = "SELECT words FROM racks WHERE rack=$word";
//      $statement = $dbhandle->prepare($query);
//      $statement->execute();
//      $temp_results = $statement->fetchAll(PDO::FETCH_ASSOC);
//     $temp_results = str_replace("@@",' ', $temp_results);
//     return $temp_results;
// }


// function set_valid_words(){



// 	//this is the basic way of getting a database handler from PDO, PHP's built in quasi-ORM
    // $dbhandle = new PDO("sqlite:scrabble.sqlite") or die("Failed to open DB");
    // if (!$dbhandle) die ($error);

//     //this is a sample query which gets some data, the order by part shuffles the results
//     //the limit 0, 10 takes the first 10 results.
//     // you might want to consider taking more results, implementing "pagination", 
//     // ordering by rank, etc.
    
//     // $query = "SELECT words FROM racks WHERE length =< 7 order by random() limit 0, 1";
    // $query = "SELECT words FROM racks WHERE length <=7";


    
//     //this next line could actually be used to provide user_given input to the query to 
//     //avoid SQL injection attacks
//     // $query
    // $statement = $dbhandle->prepare("SELECT words FROM racks WHERE length <=7");
    // $statement->execute();
    
    // $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    // print_r($results);
    
//     return $result;
// };




$myrack = generate_rack(7);
//  echo $myrack, "<br/>";


// $dictRacks = [];
// $playerWords = [];
// $dictWords = [];
// $dbhandle = new PDO("sqlite:scrabble.sqlite") or die("Failed to open DB");
//     if (!$dbhandle) die ($error);
// $query = "SELECT words FROM racks WHERE length <=7";
// $statement = $dbhandle->prepare("SELECT words FROM racks WHERE length <=7");
//     $statement->execute();
//     $results = $statement->fetchAll(PDO::FETCH_ASSOC);
//     // print_r($results);
//     echo sizeof($results), "<br/>";


$racks = [];
for($i = 0; $i < pow(2, strlen($myrack)); $i++){
	$ans = "";
	for($j = 0; $j < strlen($myrack); $j++){
		//if the jth digit of i is 1 then include letter
		if (($i >> $j) % 2) {
		  $ans .= $myrack[$j];
		}
	}
	if (strlen($ans) > 1){
  	    $racks[] = $ans;	
	}
}
$racks = array_unique($racks);

 //foreach($racks as $rack){
   // echo $rack, "<br/>";
// }

$realWords = [];
$pspell = pspell_new("en");


$dbhandle = new PDO("sqlite:scrabble.sqlite") or die("Failed to open DB");
     if (!$dbhandle) die ($error);
    
    // return $temp_results;
    
    // $exa = "Hello World";
    // echo strlen($exa);
    
    
 
foreach($racks as $rack){

    $query = "SELECT words FROM racks WHERE rack='$rack'";
    // echo $query;
    $statement = $dbhandle->prepare($query);
    $statement->execute();
    $temp_results = $statement->fetchAll(PDO::FETCH_ASSOC);
    // $temp_results = str_replace("@@",' ', $temp_results);
    // for($index = 0; $index < $temp_results.length; $index++)
     if(sizeof($temp_results) > 0){
        //   print_r($temp_results);
        $temp_results = $temp_results[0][words];
        $temp_results = explode("@@", $temp_results);
        //   $temp_results = str_replace("@@",' ', $temp_results);
        // $temp_results = $temp_results + " ";
        // print(sizeof($temp_results));
        //   print_r($temp_results);
        //   print "\n";
        // print($temp_results[0][words]);
     }
      
//   if(sizeof($temp_results) > 0){
//     foreach($temp_results as $word){
//         echo $word;
//     }
//   }

    // $valid_words = generate_words($rack);

    foreach($temp_results as $word)
        $realWords[] = $word;

//   if (pspell_check($pspell,$rack)){
//      $realWords[] = $rack;
//     }
    
    // $realWords[] = $rack;
}

// foreach($realWords as $aWord){
  //   echo $aWord, "<br/>";
// }
$realWords = array_unique($realWords);
$results = [];

$results = array("tile" => $myrack, "words" => $realWords);

// $results [] = ("tiles" => $myrack);
// $results[] = ("words" => $realWords);

// echo $results[0];
// echo $results[1];

 //this part is perhaps overkill but I wanted to set the HTTP headers and status code
    //making to this line means everything was great with this request
    header('HTTP/1.1 200 OK');
    //this lets the browser know to expect json
    header('Content-Type: application/json');
    //this creates json and gives it back to the browser
    echo json_encode($results);



?>