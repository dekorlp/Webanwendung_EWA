<?php	// UTF-8 marker äöüÄÖÜß€

class Bestellung_Warenkorb        // to do: change name of class
{
    // --- ATTRIBUTES ---

    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;
    
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
        // to do: fetch data for this view from the database
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
        echo "<section $id class=\"warenkorb\">\n";
		echo "<h2>Warenkorb</h2>\n";
		//echo "<form method=\"get\">\n";
		echo "<section id=\"pizzaList\">\n";
		echo "<h3>zu löschende Pizza</h3>";
		// pizzen sections
		//$this->generatePizza("pizza0", "0", "Margherita");
		//$this->generatePizza("pizza1", "1", "Tonno");
		//$this->generatePizza("pizza2", "2", "Prosciutto");
		echo "</section>\n";
		echo "<p>Preis: <span id=\"gesamtPreis\">0,00</span> Euro</p>\n";
		echo "<label>Adresse: <br /><input name=\"address\" id=\"address\" required type=\"text\"/></label>\n";
		echo "<br />\n";
		echo "<input class =\"button\" onclick=\"sendPizzeRequest()\" type=\"submit\" name=\"order\" value=\"Bestellen\"/>\n";
        echo "<input class =\"button\" onClick=\"deleteAllPizzas()\" type=\"button\" name=\"deleteAll\" value=\"Alle löschen\"/>\n";
		//echo "</form>\n";
		echo "</section>\n";
    }
	
	public function generatePizza($id = "", $onclickParam = "", $name = "")
	{
		echo "<div id=\"$id\">\n";
		echo "<span><input type=\"image\" onClick=\"deletePizza($onclickParam)\" title=\"alle löschen\" alt=\"löschen\" src=\"images/Actions-edit-delete-icon-16px.png\" /></span>\n";
		echo "<span>$name</span>\n";
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