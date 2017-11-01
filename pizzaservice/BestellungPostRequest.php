<?php	// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Bestellung extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
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
        $this->getViewData();
        
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
		session_start();
        // to do: call processReceivedData() for all members
		try
		{
			if(isset($_REQUEST["address"]))
			{
				echo $_REQUEST["address"];
			}
		
			if(isset($_REQUEST["pizzen"]))
			{
				$arr = $_REQUEST["pizzen"];
				$arr = json_decode($arr);
				for($i = 0; $i < count($arr); $i++)
				{
					echo $arr[$i];
				}
			}
			
			
		
			if(isset($_REQUEST["address"]) &&isset($_REQUEST["pizzen"]))
			{
				//$db = mysqli_connect("localhost", "root", "", "ewa");
				//if(!$db)
				//{
				//	exit("Verbindungsfehler: ".mysqli_connect_error());
				//}
				
				$db = $this->_database ;
				
				$address = $_REQUEST["address"];
				$date = date("Y-m-d H:i:s");
				$insertAddress = mysqli_query($db, "INSERT INTO kunde (adresse, bestellzeitpunkt) VALUES ($db->real_escape_string('$address'), '$date')");
				
				$arr = $_REQUEST["pizzen"];
				$arr = json_decode($arr);
				for($i = 0; $i < count($arr); $i++)
				{
					$trimmedPizza = trim($arr[$i]);
					$selectPizzaId = mysqli_query($db, "SELECT id FROM pizzen WHERE name = '$trimmedPizza'");
					
					$selectKundeId = mysqli_query($db, "SELECT id FROM kunde WHERE adresse = '$address'");
					$pizzaId = mysqli_fetch_row($selectPizzaId);
					$kundeId = mysqli_fetch_row($selectKundeId);
					echo $pizzaId[0];
					echo $kundeId[0];
					$_SESSION["Userid"] = $kundeId[0];
					
					$insertBestellung = mysqli_query($db, "INSERT INTO bestellung (pizza_id, kunde_id) VALUES ('$pizzaId[0]', '$kundeId[0]')");
				}
				
			}
			
			
			
			echo "<script>";
				echo "window.location = 'Kunde.php';";
			echo"</script>";
		}catch(Exception $e) {echo $e->getMessage();}
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
            $page = new Bestellung();
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
Bestellung::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >