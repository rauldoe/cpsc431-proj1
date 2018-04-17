<?php
class Address
{
  private $id = 0;
  public function getId() {
    return $this->id;
  }
  public function setId($val) {
    $this->id = $val;
  }

  private $firstName = "";
  public function getFirstName() {
    return $this->firstName;
  }
  public function setFirstName($val) {
    $this->firstName = $val;

    return $this;
  }

  private $lastName = "";
  public function getLastName() {
    return $this->lastName;
  }
  public function setLastName($val) {
    $this->lastName = $val;

    return $this;
  }

  private $street = "";
  public function getStreet() {
    return $this->street;
  }

  public function setStreet($val) {
    $this->street = $val;

    return $this;
  }

  private $city = "";
  public function getCity() {
    return $this->city;
  }

  public function setCity($val) {
    $this->city = $val;

    return $this;
  }

  private $state = "";
  public function getState() {
    return $this->state;
  }

  public function setState($val) {
    $this->state = $val;

    return $this;
  }

  private $zipCode = "";
  public function getZipCode() {
    return $this->zipCode;
  }

  public function setZipCode($val) {
    $this->zipCode = $val;

    return $this;
  }

  private $country = "";
  public function getCountry() {
    return $this->country;
  }

  public function setCountry($val) {
    $this->country = $val;

    return $this;
  }

  public function getName() {
    return $this->getLastName() . ', ' . $this->getFirstName();
  }

  public function getAddress() {
    return $this->getStreet() 
      . ' <br/>' . $this->getCity() 
      . ', ' . $this->getState() 
      . ' ' . $this->getZipCode() 
      . ' </br>' . $this->getCountry();
  }

  function __construct($id=0, $firstName="", $lastName="", $street="", $city="", $state="", $zipCode="", $country="")
  {
    // delegate setting attributes so validation logic is applied
    $this->setId($id);
    $this->setFirstName($firstName);
    $this->setLastName($lastName);
    $this->setStreet($street);
    $this->setCity($city);
    $this->setState($state);
    $this->setZipCode($zipCode);
    $this->setCountry($country);
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

   public static function getAddressItem($id, $addressList) {

      $found = NULL;
      $len = count($addressList);

      for ($i=0; $i < $len; ++$i ) {
        $item = $addressList[$i];

        if ($item->getId() == $id) {
          $found = $item;
          break;
        }
      }

      return $found;
   }
} // end class Address

?>
