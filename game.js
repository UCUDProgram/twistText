var correctAnswers = [];
var tile = "";
var wordCount =0;
var correct = [];
var guess = "";
var tileRack = [];
// var gameOver = false;



var score = 0;
var loadData = function(){
  var URL = "https://twist-text-ucudprogram.c9users.io/rack.php";
  var xhr = new XMLHttpRequest();
  
  xhr.onload = function(){
    if (this.status == 200){
      parseData((this.response));
    }
  };
  
  xhr.open("GET", URL);
  xhr.send();
};

var parseData = function(chuckJSON){
  // console.log(chuckJSON);
  var data = JSON.parse(chuckJSON);
  tile = data.tile;
  initializeRack(tile);
  // console.log(tile);
  correctAnswers = data.words;
  console.log(correctAnswers);
  // console.log(wordCount);
  display_rack();
  display_words();
  display_status();
  display_left();
};

var updateString = function(charApp){
    guess = guess.concat(charApp);
    // console.log(guess);
    display_selection();
};

var initializeRack = function(aRack){
  for(var i = 0; i<aRack.length;i++){
      tileRack.push(aRack.charAt(i)); 
  }
};



var submitWord = function(){
  // wordCheck(document.getElementById('wordEntered').value.toUpperCase());
  // var input = document.getElementById('wordEntered');
  // input.value = '';
  
  wordCheck(guess);
  guess = '';
  display_rack();
  display_words();
  display_status();
  display_left();
  display_selection();
  if(wordCount == correctAnswers.length){
    // gameOver = true;
    alert("You Won the game.  Refresh the page to get a new Rack");
  }
};

var clearWord = function (){
  // var input = document.getElementById('wordEntered');
  // input.value = '';
  
  
  guess = '';
  display_rack();
  display_words();
  display_status();
  display_left();
  display_selection();
};

var wordCheck = function(aWord){
  var answers_index = correctAnswers.indexOf(aWord);
  var correct_index = correct.indexOf(aWord);
  if((answers_index >-1) && (correct_index == -1)){
    correct.push(aWord);
    wordCount++;
  }
};

// var scoreReset = function(){
//     score =0;  
// };


// Display the items in html document
var display_words = function(){
    var $div = document.getElementById('words');
    $div.innerHTML = '';
    correct.forEach(function (anAnswer){
        var $word = document.createElement('p');
        $word.innerHTML = anAnswer;
        $word.classList.add('anAnswer');
        $div.appendChild($word);
    });
};

var display_rack = function(){
    var $div = document.getElementById('rack');
    $div.innerHTML = '';
    tileRack.forEach(function (atile){
        var $letter = document.createElement('p');
        // var charPos = tile.charAt(index);
        $letter.innerHTML = atile;
        // $letter.classList.add('charPos');
        $letter.classList.add('letterBlock');
        $letter.addEventListener("click", function (ev){
        updateString(atile);
        });

        // var $letter = document.createElement('p');
        // $letter.innerHTML = tile;
        // $letter.classList.add('tile');
        $div.appendChild($letter);
    })
};
// };
// })};

var display_selection = function(){
    var $div = document.getElementById('selection');
    $div.innerHTML = '';
        var $guess = document.createElement('p');
         $guess.innerHTML = guess;
        $guess.classList.add('guess');
        $div.appendChild($guess);
};

var display_status = function(){
  var $div = document.getElementById('wordCounter');
  $div.innerHTML = '';
  var $p = document.createElement('p');
  var wordString = "Words Found: " + wordCount;
  $p.innerHTML = wordString;
  $p.classList.add('wordString');
  $div.appendChild($p);
};

var display_left = function(){
  var $div = document.getElementById('remaining');
  $div.innerHTML = '';
  var $p = document.createElement('p');
  // console.log(correctAnswers.length);
  var leftString = "Words left: " + (correctAnswers.length - wordCount);
  $p.innerHTML = leftString;
  $p.classList.add('leftString');
  $div.appendChild($p);
};

var appStart = function(){
    loadData();
    display_words();
    display_rack();
    display_left();
    display_status();
    display_selection();
    document.getElementById('Submit').addEventListener('click', submitWord);
    document.getElementById('Clear').addEventListener('click', clearWord);
};

document.addEventListener("DOMContentLoaded", appStart);