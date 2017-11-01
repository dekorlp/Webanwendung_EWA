<?php	// UTF-8 marker äöüÄÖÜß€

abstract class Page
{
    // --- ATTRIBUTES ---

    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;
    
    // --- OPERATIONS ---
    
    /**
     * Connects to DB and stores 
     * the connection in member $_database.  
     * Needs name of DB, user, password.
     *
     * @return none
     */
    protected function __construct() 
    {
		try
		{
			$db = mysqli_connect("localhost", "root", "", "ewa");
			if(!$db)
			{
				exit("Verbindungsfehler: ".mysqli_connect_error());
			}
			
			$this->_database = $db;/* to do: create instance of class MySQLi */;
		}catch(Exception $e) {echo $e->getMessage();}
    }
    
    /**
     * Closes the DB connection and cleans up
     *
     * @return none
     */
    protected function __destruct()    
    {
        // to do: close database
		try {
		$this->_database->close();
		}catch(Exception $e) {echo $e->getMessage();}
    }
    
    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     *
     * @param $headline $headline is the text to be used as title of the page
     *
     * @return none
     */
    protected function generatePageHeader($headline = "") 
    {	
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");
        
		
		echo "<!DOCTYPE html>\n";
		echo "<html lang=\"de\">\n";
		echo "<head>\n";
		echo "<meta charset=\"UTF-8\" />\n";
		echo "<title> $headline </title>\n";
		echo "<link rel=\"stylesheet\" href=\"style/main.css\"/>\n";
		echo "<script type=\"text/javascript\" src=\"js/functions.js\"> </script>\n";
		echo "</head>\n";
		echo "<body>\n";
		echo "<header><h1>$headline</h1></header>\n";
        // to do: output common beginning of HTML code 
        // including the individual headline
    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     *
     * @return none
     */
    protected function generatePageFooter() 
    {
		
		echo "</body>\n";
		echo "</html>\n";
        // to do: output common end of HTML code
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If every page is supposed to do something with submitted
     * data do it here. E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
     *
     * @return none
     */
    protected function processReceivedData() 
    {
        if (get_magic_quotes_gpc()) {
            throw new Exception
                ("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }
} // end of class

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >