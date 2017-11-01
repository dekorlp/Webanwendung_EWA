<?php	// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Kunde extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    protected $pizzen = Array();
	
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
		session_start();
		
        // to do: fetch data for this view from the database
		try
		{
			if(isset($_SESSION["Userid"]))
			{
				$db = $this->_database ;
				
				//$id = $_REQUEST["id"];
				//echo $id;
				//$selectKunde = mysqli_query($db, "SELECT * FROM kunde where id = '{$_SESSION["Userid"]}' ");
				$selectKunde = mysqli_query($db, "SELECT adresse FROM kunde where id = '{$_SESSION["Userid"]}' ");
				$kundeId = mysqli_fetch_row($selectKunde);
				$this->pizzen["adresse"] = $kundeId[0]; // adresse
				$this->pizzen["status"] = 6; // adresse
				$this->pizzen["pizzen"] = Array();
				$selectPizzen = mysqli_query($db, "SELECT * FROM bestellung, pizzen where kunde_id = '{$_SESSION["Userid"]}' AND pizza_id = pizzen.id ");
				
				$i = 0;
				while ($rowPizzen = mysqli_fetch_assoc($selectPizzen)) {
					
					$this->pizzen["pizzen"][$i][0] = $rowPizzen["name"];
					$this->pizzen["pizzen"][$i][1] = strtolower($rowPizzen["name"]);
					$this->pizzen["pizzen"][$i][2] = $rowPizzen["status"];
					//echo $rowPizzen["status"];
					
					if($rowPizzen["status"] < $this->pizzen["status"])
					{
						$this->pizzen["status"] = $rowPizzen["status"];
					}
					
					$i++;
				}				
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
        $this->generatePageHeader('Kunde');
		
		if( isset($_SESSION["Userid"]) && $this->pizzen["status"] != 4)
		{
			$this->generateOrderstatusSection($this->pizzen["pizzen"]);		
		}
		
		if($this->pizzen["status"] == 4)
		{
			echo "<h2> Bestellung wurde zugestellt</h2>";
		}
		
		echo "<form action=\"Bestellung.php\">";
		echo "<input type=\"submit\" value=\"Neue bestellung\" class=\"bestellstatus_input\" />\n";
		echo "</form>\n";
		echo "</section>\n";
		
        // to do: call generateView() for all members
        // to do: output view of this page
        $this->generatePageFooter();
    }
    
	protected function generateOrderstatusSection($pizzen = " ")
	{
		echo "<section class=\"status\">\n";
		echo "<h2>Lieferstand:</h2>\n";
		echo "<div>\n";
		echo "<span></span>\n";
		echo "<span>bestellt</span>\n";
		echo "<span>im Ofen</span>\n";
		echo "<span>fertig</span>\n";
		echo "<span>unterwegs</span>\n";
		echo "</div>\n";		
		
		foreach($pizzen AS $v)
		{			
			echo "<div>\n";
			echo "<span>$v[0]</span>\n";
			echo "<span><input type=\"radio\"  disabled " . (($v[2] == 0) ? "checked" : "") . "  /> </span>\n";
			echo "<span><input type=\"radio\"  disabled " . (($v[2] == 1) ? "checked" : "") . " /> </span>\n";
			echo "<span><input type=\"radio\"  disabled " . (($v[2] == 2) ? "checked" : "") . " /> </span>\n";
			echo "<span><input type=\"radio\"  disabled " . (($v[2] == 3) ? "checked" : "") . " /> </span>\n";
			echo "</div>\n";
		}
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
            $page = new Kunde();
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
Kunde::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >