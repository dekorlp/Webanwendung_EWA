<?php	// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Baecker extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    	
	protected $kunden = Array(Array());
		
    /**
     * Instantiates members (to be defined above).   
     * Calls the constructor of the parent i.e. page class.
     * So the database connection is established.
     *
     * @return none
     */
    protected function __construct() 
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }
    
    /**
     * Cleans up what ever is needed.   
     * Calls the destructor of the parent i.e. page class.
     * So the database connection is closed.
     *
     * @return none
     */
    protected function __destruct() 
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData()
    {
        // to do: fetch data for this view from the database
		try
		{
			$db = $this->_database;
			
			$selectKunden = mysqli_query($db, "SELECT * FROM kunde");
			
			$i = 0;
			while ($rowKunden = mysqli_fetch_assoc($selectKunden)) {
				$this->kunden[$i]["id"] = $rowKunden["id"];
				$this->kunden[$i]["adresse"] = htmlspecialchars($rowKunden["adresse"]);
				$this->kunden[$i]["index"] = $i;
				$this->kunden[$i]["status"] = 6;
				$this->kunden[$i]["pizzen"] = Array(Array());
				
				$j = 0;
				$selectPizzen = mysqli_query($db, "SELECT * FROM pizzen, bestellung where kunde_id = '{$this->kunden[$i]["id"]}' AND pizza_id = pizzen.id");
					while ($rowPizzen = mysqli_fetch_assoc($selectPizzen)) {
						//echo $rowPizzen["id"];
						//$this->kunden[$i]["pizzen"][$j][0] = "Kunde".$i."Pizza".$j;
						//$this->kunden[$i]["pizzen"][$j][1] = $rowPizzen["name"];
						//$this->kunden[$i]["pizzen"][$j][2] = "bestellung".$i.$rowPizzen["name"].$j;
						//$this->kunden[$i]["pizzen"][$j][3] = $rowPizzen["status"];
						
						$this->kunden[$i]["pizzen"][$j][0] = "Kunde".$i."Pizza".$j;
						$this->kunden[$i]["pizzen"][$j][1] = $rowPizzen["name"];
						$this->kunden[$i]["pizzen"][$j][2] = "bestellung".$i.$rowPizzen["name"].$j;
						$this->kunden[$i]["pizzen"][$j][3] = $rowPizzen["status"];
						$this->kunden[$i]["pizzen"][$j][4] = "pizza=".$rowPizzen["id"];
						
						if($this->kunden[$i]["status"] > $rowPizzen["status"])
						{
							$this->kunden[$i]["status"] = $rowPizzen["status"];
						}
						
						//echo  $this->kunden[$i]["pizzen"][0];
						//echo $this->kunden[$i]["pizzen"][1];
						//echo $this->kunden[$i]["pizzen"][2];
						$j++;
					}
				
				$i++;
			}
		}catch(Exception $e) {echo $e->getMessage();}
    }
    
    /**
     * First the necessary data is fetched and then the HTML is 
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of 
     * all views contained is generated.
     * Finally the footer is added.
     *
     * @return none
     */
    protected function generateView() 
    {
		/*$pizzenKoehler = array(array("Kunde0Pizza0", "Margherita", "bestellung0Margherita0"),
								array("Kunde0Pizza1", "Tonno", "bestellung0Tonno1"),
								array("Kunde0Pizza2", "Prosciutto", "bestellung0Prosciutto2"),
								array("Kunde0Pizza3", "Prosciutto", "bestellung0Prosciutto3"));
								
		$pizzenMeier = array(array("Kunde1Pizza0", "Margherita", "bestellung1Margherita0"),
								array("Kunde1Pizza1", "Tonno", "bestellung1Tonno1"),
								array("Kunde1Pizza2", "Prosciutto", "bestellung1Prosciutto2"),
								array("Kunde1Pizza3", "Salami", "bestellung1Salami3"),
								array("Kunde1Pizza4", "Prosciutto", "bestellung1Prosciutto4"));*/
		
		header("refresh: 10;");
		
        $this->getViewData();
        $this->generatePageHeader('Backstatus');
		echo "<div>";
		//$this->createCustomer("Köhler, Kettelerstraße 4", "0", $pizzenKoehler);
		//$this->createCustomer("Meier, Hauptstr. 5", "1", $pizzenMeier);
		for($i = 0; $i < count($this->kunden); $i++)
		{
			if($this->kunden[$i]["status"] < 2)
				$this->createCustomer($this->kunden[$i]["adresse"], $this->kunden[$i]["index"], $this->kunden[$i]["pizzen"]);
		}
		
		
		
		echo "</div>";
        // to do: call generateView() for all members
        // to do: output view of this page
        $this->generatePageFooter();
    }
    
	protected function createCustomer($nameAndAddress = " ", $kundenId = " " , Array $array)
	{
		
		echo "<article class =\"status\">\n";
		echo "<h3>$nameAndAddress</h3>\n";
		echo "<div>\n";
		echo "<span></span>\n";
		echo "<span>bestellt</span>\n";
		echo "<span>im Ofen</span>\n";
		echo "<span>fertig</span>\n";
		echo "</div>\n";
		$iterator = 0;
		foreach($array AS $v)
		{			
			echo "<div id=\"$v[0]\">\n";
			echo "<span>$v[1]</span>\n";
			echo "<span><input type=\"radio\" id=\"$v[4]\" name=\"$v[2]\" onclick=\"selectNextPizza($kundenId, $iterator);\" " . (($v[3] == 0) ? "checked" : "disabled") . " /> </span>\n";
			echo "<span><input type=\"radio\" id=\"$v[4]\" name=\"$v[2]\" onclick=\"selectNextPizza($kundenId, $iterator);\" " . (($v[3] == 1) ? "checked" : (($v[3] == 0) ? "" : "disabled")  ) . " /> </span>\n";
			echo "<span><input type=\"radio\" id=\"$v[4]\" name=\"$v[2]\" onclick=\"selectNextPizza($kundenId, $iterator);\" " . (($v[3] == 2) ? "checked" : (($v[3] == 1) ? "" : "disabled")  ) . "/> </span>\n";
			echo "</div>\n";
			$iterator++;
		}	
		echo "</article>";
	}
	
    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this page is supposed to do something with submitted
     * data do it here. 
     * If the page contains blocks, delegate processing of the 
	 * respective subsets of data to them.
     *
     * @return none 
     */
    protected function processReceivedData() 
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }

    /**
     * This main-function has the only purpose to create an instance 
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
     *
     * @return none 
     */    
    public static function main() 
    {
        try {
            $page = new Baecker();
            $page->processReceivedData();
            $page->generateView();
        }
        catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page. 
// That is input is processed and output is created.
Baecker::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >