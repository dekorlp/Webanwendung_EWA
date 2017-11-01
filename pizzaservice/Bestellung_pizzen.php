<?php	// UTF-8 marker äöüÄÖÜß€

 
class Bestellung_Pizzen       // to do: change name of class
{
    // --- ATTRIBUTES ---

    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;
    protected $pizzen = Array(Array());
	
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
    // --- OPERATIONS ---
    
    /**
     * Gets the reference to the DB from the calling page template.
     * Stores the connection in member $_database.
     *
     * @param $database $database is the reference to the DB to be used     
     *
     * @return none
     */
    public function __construct($database) 
    {
        $this->_database = $database;
        // to do: instantiate members representing substructures/blocks
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
			$selectPizzen = mysqli_query($db, "SELECT * FROM pizzen");
			
			$i = 0;
			while ($row = mysqli_fetch_assoc($selectPizzen)) {
				$this->pizzen[$i]['id'] = $row['id'];
				$this->pizzen[$i]['name'] = $row['name'];
				$this->pizzen[$i]['preis'] = $row['preis'];
				$this->pizzen[$i]['bild'] = $row['bild'];
				$this->pizzen[$i]['bildTyp'] = $row['bildTyp'];
				
				$i++;
			}
			$selectPizzen->free();
		
		} catch(Exception $e) {echo $e->getMessage();}
    }
    
    /**
     * Generates an HTML block embraced by a div-tag with the submitted id.
     * If the block contains other blocks, delegate the generation of their 
	 * parts of the view to them.
     *
     * @param $id $id is the unique (!!) id to be used as id in the div-tag     
     *
     * @return none
     */
    public function generateView($id = "") 
    {
        $this->getViewData();
        if ($id) {
            $id = "id=\"$id\"";
        }
        echo "<section $id class=\"pizzen\">\n";
		echo "<h2>Pizzen:</h2>\n";
		// pizzenAuswahl
		
		for($i = 0; $i < count($this->pizzen); $i++)
		{
			//echo $this->pizzen[$i]['id'];
			//echo $this->pizzen[$i]['name'];
			//echo $this->pizzen[$i]['preis'];
			//echo $this->pizzen[$i]['bild'];
			//echo $this->pizzen[$i]['bildTyp'];
			
			$this->generatePizza($this->pizzen[$i]['name'], $i, $this->pizzen[$i]['name'], strtolower($this->pizzen[$i]['name']), $this->pizzen[$i]['preis'] ." €", $this->pizzen[$i]['bild'], $this->pizzen[$i]['bildTyp']);
		}
		
		//$this->generatePizza("Marghaerita", "0", "Marghaerita", "margherita", "4,00 €");
		//$this->generatePizza("Salami", "1", "Salami", "salami", "4,50 €");
		//$this->generatePizza("Prosciutto", "2", "Prosciutto", "prosciutto", "5,50 €");
		//$this->generatePizza("Tonno", "3", "Tonno", "tonno", "5,00 €");
		echo "</section>\n";
    }
	
	public function generatePizza($id = "", $onclickParam = "", $name = "", $alt = "", $price = "", $bild = "", $bildtyp = "")
	{
		echo "<div class=\"pizzaItem\" id=\"$id\">\n";
		//header("Content-type: $bildtyp");
		//echo "<input type=\"image\" onclick=\"appendPizzaToCart($onclickParam)\"  alt=\"$alt\"   name=\"$name\" >$bild</input>\n";
		echo "<input type=\"image\" onclick=\"appendPizzaToCart($onclickParam)\"  alt=\"$alt\"   name=\"$name\" src=\"images/Pizza-icon.png\" />\n";
		echo "<span class=\"pizzaText\">$name</span>\n";
		echo "<span class=\"pizzaText\">$price</span>\n";
		echo "</div>\n";
    }
    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If this block is supposed to do something with submitted
     * data do it here. 
     * If the block contains other blocks, delegate processing of the 
	 * respective subsets of data to them.
     *
     * @return none 
     */
    public function processReceivedData()
    {
        // to do: call processData() for all members
    }
}
// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >