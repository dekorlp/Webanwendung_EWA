<?php	// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';
require_once './Bestellung_Warenkorb.php';
require_once './Bestellung_pizzen.php';
require_once './Bestellung_Beschreibung.php';

class Bestellung extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
	protected $warenkorb = null;
	protected $pizzen = null;
	protected $beschreibung = null;
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
		$this->warenkorb = new Bestellung_Warenkorb('');
		$this->pizzen = new Bestellung_Pizzen($this->_database );
		$this->beschreibung = new Bestellung_Beschreibung('');
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
        $this->generatePageHeader('Bestellung');
		$this->warenkorb->generateView('warenkorb');
		$this->pizzen->generateView('pizzen');
		$this->beschreibung->generateView('Willkommen', 'Zur Zubereitung wird zuerst ein einfacher Hefeteig aus Mehl, Wasser, wenig Hefe, Salz und eventuell etwas Olivenöl hergestellt, gründlich durchgeknetet und nach einer Gehzeit von mindestens einer Stunde bei Zimmertemperatur (bzw. über Nacht im oberen Fach des Kühlschranks) ausgerollt oder mit den bemehlten Händen dünn ausgezogen. Geübte Pizzabäcker ziehen den Teig über den Handrücken und weiten ihn durch Kreisenlassen in der Luft.
Pizza-Ofen mit Gasbefeuerung

Dann wird der Teig mit den Zutaten je nach Rezept nicht zu üppig belegt, üblicherweise zuerst mit Tomatenscheiben oder häufiger mit passierten Dosentomaten oder Salsa pizzaiola (einer vorher gekochten, sämigen Tomatensauce, die mit Oregano, Basilikum, Knoblauch und anderem kräftig gewürzt ist). Es folgen der Käse (z. B. Mozzarella, Parmesan oder Pecorino) und die übrigen Zutaten, zum Abschluss etwas Olivenöl.

Schließlich wird die Pizza bei einer möglichst hohen Temperatur von 400 bis 500 °C für wenige Minuten kurz gebacken. Dies geschieht in einer möglichst niedrigen Kammer. Ein Stapeln in Einschüben oder separat schaltbare Unter- und Oberhitze ist daher nicht üblich. Der traditionelle Kuppelofen ist gemauert und die Hitze wird über ein Feuer direkt im Backraum erzeugt. Moderne Pizzaöfen werden mit Gas oder Strom beheizt.

In Haushaltsbacköfen sind meist nur Temperaturen bis 250 °C möglich, wodurch sich die Backzeit verlängert und kein optimales Ergebnis erzielt wird. Durch die Verwendung eines vorgeheizten, meist aus Schamotte bestehenden Pizzasteins anstelle eines Backblechs lassen sich bessere Resultate erzielen, weil dieser die Hitze gleichmäßiger hält und Schwitzwasserbildung verhindert. Ein ähnlicher Effekt lässt sich jedoch auch erreichen, indem man die auf der Unterseite ausreichend bemehlte (Festkleben verhindern!) Pizza direkt auf ein bereits im Ofen vorgeheiztes Backblech gibt und im unteren Ofenbereich/auf der untersten Schiene bei (Ober- und) Unterhitze bäckt. Hierbei sind ggf. in der jeweiligen Gebrauchsanweisung angegebene Temperatureinschränkungen von Blechen (manche nur bis 220 °C verwendbar und nicht für Vorheizen ohne Backgut geeignet) und Backpapier (meist nur bis 220 °C) zu beachten.');
        $this->generatePageFooter();
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