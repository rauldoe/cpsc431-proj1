<?php
class TeamStatistic
{
   // Instance attributes
   private $teamID = 0;
   private $league = 0;
   private $teamManager  = 0;
   private $teamName = "";

   function getTeamID() {
     return $this->teamID;
   }
   function setTeamID($val) {
     $this->teamID = $val;
   }

   function getLeague(){
     return $this->league;
   }

   function setLeague($val){
     $this->league = $val;
   }

   function getTeamManager(){
     return $this->teamManager;
   }

   function setTeamManager($val){
     $this->teamManager = $val;
   }

   function getTeamName(){
     return $this->teamName;
   }

   function setTeamName($val){
     $this->teamName = $val;
   }

   function __toString()
   {
     return (var_export($this, true));
   }

   // Returns a tab separated value (TSV) string containing the contents of all instance attributes
   function toTSV()
   {
       return implode("\t", [$this->setTeamID(), $this->setLeague(), $this->setTeamManager(), $this->setTeamName()]);
   }

   // Sets instance attributes to the contents of a string containing ordered, tab separated values
   function fromTSV(string $tsvString)
   {
     // assign each argument a value from the tab delineated string respecting relative positions
     list($league, $league, $teamManager, $teamName) = explode("\t", $tsvString);
     $this->setTeamID($teamID);
     $this->setLeague($league);
     $this->setTeamManager($teamManager);
     $this->setTeamName($teamName);
   }

   public static function getTeamItem($teamID, $teamList) {

      $found = NULL;
      $len = count($teamList);

      for ($i=0; $i < $len; ++$i ) {
        $item = $teamList[$i];

        if ($item->getTeamID() == $teamID) {
          $found = $item;
          break;
        }
      }

      return $found;
   }
} // end class PlayerStatistic

?>
