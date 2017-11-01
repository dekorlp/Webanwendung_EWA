<?php	// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Fahrer extends Page
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
		try
		{
			// to do: fetch data for this view from the database
			$db = $this->_database;
			
			$selectKunden = mysqli_query($db, "SELECT * FROM kunde");
			
			$i = 0;
			while ($rowKunden = mysqli_fetch_assoc($selectKunden)) {
				$this->kunden[$i]["id"] = $rowKunden["id"];
				$this->kunden[$i]["adresse"] = htmlspecialchars($rowKunden["adresse"]);
				$this->kunden[$i]["index"] = $i;
				
				
				$pizzen = "";
				$preis = "";
				//$j = 0;
				$fertig = 6;
				
				$selectPizzen = mysqli_query($db, "SELECT * FROM bestellung, pizzen where kunde_id = '{$this->kunden[$i]["id"]}' AND pizza_id = pizzen.id");
					while ($rowPizzen = mysqli_fetch_assoc($selectPizzen)) {
						$pizzen = $pizzen. $rowPizzen["name"].", " ;
						$preis = $preis +   str_replace(',', '.', $rowPizzen["preis"]) ;
						if($rowPizzen["status"] < $fertig)
						{
							$fertig = $rowPizzen["status"];
						}
						
		
					}
				$pizzen = substr($pizzen, 0, -2); // letztes , abschneiden
				
				$this->kunden[$i]["pizzen"] = $pizzen;
				$this->kunden[$i]["preis"] = str_replace('.', ',', $preis);
				$this->kunden[$i]["status"] = $fertig;
				//$preis = $preis + 
				//echo $this->kunden[$i]["pizzen"];
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
		header("refresh: 10;");
	
        $this->getViewData();
        $this->generatePageHeader('Fahrer');
		echo "<div>";
		
		for($i = 0; $i < count($this->kunden); $i++)
		{
			if( $this->kunden[$i]["status"] >= 2 && $this->kunden[$i]["status"] <= 3)
			{
				$this->createCustomer($this->kunden[$i]["adresse"]. " ".$this->kunden[$i]["preis"]. "€<br/>". $this->kunden[$i]["pizzen"], $this->kunden[$i]["id"],  $this->kunden[$i]["status"]);
			}
		}
		
		//$this->createCustomer("Köhler, Kettelersdivaße 4 13.50€<br /> Margherita, Salami, Tonno", "0");
		//$this->createCustomer("Müller, Kasinosdiv. 5 10,00€<br /> Salami, Prosciutto", "1");
		
		
		echo "</div>";
		
        // to do: call generateView() for all members
        // to do: output view of this page
        $this->generatePageFooter();
    }
    
	protected function createCustomer($nameAndAddressAndPrice = " ", $kundenId = " ", $status = " " )
	{
		
		echo "<article class =\"status\">\n";
		echo "<h3>$nameAndAddressAndPrice</h3>\n";
		echo "<div>\n";
		echo "<span>fertig</span>\n";
		echo "<span>unterwegs</span>\n";
		echo "<span>geliefert</span>\n";
		echo "</div>\n";

		echo "<div id=\"Kunde$kundenId\">\n";
		echo "<span><input type=\"radio\" id=\"$kundenId\" name=\"bestellung$kundenId\" onclick=\"selectNextOrder($kundenId);\" " . (($status == 2) ? "checked" : (($status == 1) ? "" : "disabled"  )) . "/> </span>\n";
		echo "<span><input type=\"radio\" id=\"$kundenId\" name=\"bestellung$kundenId\" onclick=\"selectNextOrder($kundenId);\" " . (($status == 3) ? "checked" : (($status == 2) ? "" : "disabled"  )) . " /> </span>\n";
		echo "<span><input type=\"radio\" id=\"$kundenId\" name=\"bestellung$kundenId\" onclick=\"selectNextOrder($kundenId);\" " . (($status == 4) ? "checked" : (($status == 3) ? "" : "disabled"  )) . " /> </span>\n";
		echo "</div>\n";
		
		
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
            $page = new Fahrer();
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
Fahrer::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >