<?php
class PlayerStatistic1
{
   // Instance attributes
   private $playerId     = 0;
   private $name         = array('FIRST'=>"", 'LAST'=>null); 
   private $playingTime  = array('MINS' =>0,  'SECS'=>0);
   private $pointsScored = 0;
   private $assists      = 0;
   private $rebounds     = 0;
   private $gamesPlayed  = 0;
   private $gameId         = 0;
   
   function getPlayerId() {
     return $this->playerId;
   }
   
   function setPlayerId($val) {
     $this->playerId = $val;
   }

   // Operations
   
   // name() prototypes:
   //   string name()                          returns name in "Last, First" format.
   //                                          If no first name assigned, then return in "Last" format.
   //                                         
   //   void name(string $value)               set object's $name attribute in "Last, First" 
   //                                          or "Last" format.
   //                                         
   //   void name(array $value)                set object's $name attribute in [first, last] format
   //
   //   void name(string $first, string $last) set object's $name attribute
   function name() 
   {
     // string name()
     if( func_num_args() == 0 )
     {
       if( empty($this->name['FIRST']) ) return $this->name['LAST'];
       else                              return $this->name['LAST'].', '.$this->name['FIRST']; 
     }
     
     // void name($value)
     else if( func_num_args() == 1 )
     {
       $value = func_get_arg(0);
       
       if( is_string($value) ) 
       {
         $value = explode(',', $value); // convert string to array 
         
         if ( count($value) >= 2 ) $this->name['FIRST'] = htmlspecialchars(trim($value[1]));
         else                      $this->name['FIRST'] = '';
         
         $this->name['LAST']  = htmlspecialchars(trim($value[0]));          
       }
       
       else if( is_array ($value) )
       {
         if ( count($value) >= 2 ) $this->name['LAST'] = htmlspecialchars(trim($value[1]));
         else                      $this->name['LAST'] = '';
         
         $this->name['FIRST']  = htmlspecialchars(trim($value[0])); 
       }         
     }
     
     // void name($first_name, $last_name)
     else if( func_num_args() == 2 )
     {
         $this->name['FIRST'] = htmlspecialchars(trim(func_get_arg(0)));
         $this->name['LAST']  = htmlspecialchars(trim(func_get_arg(1))); 
     }
     
     return $this;
   }

   // playingTime() prototypes:
   //   string playingTime()                          returns playing time in "minutes:seconds" format.
   //                                         
   //   void playingTime(string $value)               set object's $playingTime attribute 
   //                                                 in "minutes:seconds" format.
   //                                         
   //   void playingTime(array $value)                set object's $playingTime attribute 
   //                                                 in [minutes, seconds] format
   //
   //   void playingTime(int $minutes, int $seconds)  set object's $playingTime attribute
   function playingTime() 
   {  
     // string playingTime()
     if( func_num_args() == 0 )
     {
       return $this->playingTime['MINS'].':'.$this->playingTime['SECS'];
     }
     
     // void playingTime($value)
     else if( func_num_args() == 1 )
     {
       $value = func_get_arg(0);
       
       if( is_string($value) ) $value = explode(':', $value); // convert string to array
       if( is_array ($value) )
       {
         if ( count($value) >= 2 ) $this->playingTime['SECS'] = (int)$value[1];
         else                      $this->playingTime['SECS'] = 0;
         $this->playingTime['MINS'] = (int)$value[0];
       }         
     }
     
     // void playingTime($first_name, $last_name)
     else if( func_num_args() == 2 )
     {
       $this->playingTime['MINS'] = (int)func_get_arg(0);
       $this->playingTime['SECS'] = (int)func_get_arg(1);
     }
     
     return $this;
   }

   function getPlayingTimeMin() {
     return $this->playingTime['MINS'];
   }

   function getPlayingTimeSec() {
     return $this->playingTime['SECS'];
   }

   // pointsScored() prototypes:
   //   int pointsScored()               returns the number of points scored.
   //                                         
   //   void pointsScored(int $value)    set object's $pointsScored attribute
   function pointsScored() 
   {  
     // int pointsScored()
     if( func_num_args() == 0 )
     {
       return $this->pointsScored;
     }
     
     // void pointsScored($value)
     else if( func_num_args() == 1 )
     {
       $this->pointsScored = (int)func_get_arg(0);
     }
     
     return $this;
   }

   // assists() prototypes:
   //   int assists()               returns the number of scoring assists.
   //                                         
   //   void assists(int $value)    set object's $assists attribute
   function assists() 
   {  
     // int assists()
     if( func_num_args() == 0 )
     {
       return $this->assists;
     }
     
     // void assists($value)
     else if( func_num_args() == 1 )
     {
       $this->assists = (int)func_get_arg(0);
     }
     
     return $this;
   }
   
   // rebounds() prototypes:
   //   int rebounds()               returns the number of rebounds taken.
   //                                         
   //   void rebounds(int $value)    set object's $rebounds attribute
   function rebounds() 
   {  
     // int rebounds()
     if( func_num_args() == 0 )
     {
       return $this->rebounds;
     }
     
     // void rebounds($value)
     else if( func_num_args() == 1 )
     {
       $this->rebounds = (int)func_get_arg(0);
     }
     
     return $this;
   }

   function getGamesPlayed() {
     return $this->gamesPlayed;
   }
   function setGamesPlayed($val) {
     $this->gamesPlayed = $val;
   }

function getGameId() {
    return $this->gameId;
  }
  function setGameId($val) {
    $this->gameId = $val;
  }

   function __construct($playerId=0, $name="", $time="0:0", $points=0, $assists=0, $rebounds=0, $gamesPlayed=0)
   {
     // if $name contains at least one tab character, assume all attributes are provided in 
     // a tab separated list.  Otherwise assume $name is just the player's name.
     
     if( strpos($playerId, "\t") !== false) // Note, can't check for "true" because strpos() only returns the boolean value "false", never "true"
     {
       // assign each argument a value from the tab delineated string respecting relative positions
       list($playerId, $name, $time, $points, $assists, $rebounds, $gamesPlayed) = explode("\t", $playerId);
     }
     
     // delegate setting attributes so validation logic is applied
     $this->setPlayerId($playerId);
     $this->name($name);
     $this->playingTime($time);
     $this->pointsScored($points);
     $this->assists($assists);
     $this->rebounds($rebounds);
     $this->setGamesPlayed($gamesPlayed);
   }

   function hasStat() {
     return $this->getGamesPlayed() > 0;
   }

   function __toString()
   {
     return (var_export($this, true));
   }
   
   // Returns a tab separated value (TSV) string containing the contents of all instance attributes   
   function toTSV()
   {
       return implode("\t", [$this->name(), $this->playingTime(), $this->pointsScored(), $this->assists(), $this->rebounds()]);
   }
   
   // Sets instance attributes to the contents of a string containing ordered, tab separated values 
   function fromTSV(string $tsvString)
   {
     // assign each argument a value from the tab delineated string respecting relative positions
     list($name, $time, $points, $assists, $rebounds) = explode("\t", $tsvString);
     $this->name($name);
     $this->playingTime($time);
     $this->pointsScored($points);
     $this->assists($assists);
     $this->rebounds($rebounds);
   }

   function generate()
   {
       $min = 0;
       $max = 60;
       $comp = 'MINS';
       $this->playingTime[$comp] = rand($min, $max);

       $min = 0;
       $max = 60;
       $comp = 'SECS';
       $this->playingTime[$comp] = rand($min, $max);

       
       $min = 0;
       $max = 120;
       $this->pointsScored(rand($min, $max));

       $min = 0;
       $max = 20;
       $this->assists(rand($min, $max));

       $min = 0;
       $max = 20;
       $this->rebounds(rand($min, $max));
   }

   function save()
   {
        $query = 'INSERT INTO Statistics(Player, PlayingTimeMin, PlayTimeSec, Points, Assists, Rebounds)'
        . ' VALUES(?, ?, ?, ?, ?, ?);';

        $db = new mysqli('127.0.0.1', 'hw3user', 'password', 'hw3');

        $stmt = $db->prepare($query); 
        $stmt->bind_param('dddddd', $newStat->getPlayerId(), $newStat->getPlayingTimeMin(), $newStat->getPlayingTimeSec(), $newStat->pointsScored(), $newStat->assists(), $newStat->rebounds()); 
        $stmt->execute();

        $db->close();  
   }
} // end class PlayerStatistic

?>

