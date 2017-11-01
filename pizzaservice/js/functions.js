function appendPizzaToCart(index)
{	
	"use strict";

	var knoten = document.getElementById("pizzen");
	//alert(knoten.childNodes[1].childElementCount);
	var pizzaTyp = knoten.children[index+1].children[1].innerHTML;
	
	
	var pizzaListKnoten = document.getElementById("pizzaList");
	var size = pizzaListKnoten.getElementsByTagName("div").length;
	var newPizzaDivision = document.createElement("div");
	
	newPizzaDivision.id = "pizza" + (pizzaListKnoten.childElementCount-1 ).toString();  
	
	var span = document.createElement("span");
	var imageSpan = document.createElement("span");
	var input = document.createElement("input");
	imageSpan.appendChild(input);
	span.innerHTML = " " + pizzaTyp ;
	input.type = "image";
	input.src = "images/Actions-edit-delete-icon-16px.png";
	input.name = "pizza" + size;
	input.alt = "löschen";
	input.addEventListener("click", function(){deletePizza(size);});
	newPizzaDivision.appendChild(imageSpan);
	newPizzaDivision.appendChild(span);
	
	// Gesamtpreis aktualisieren
	document.getElementById("gesamtPreis").innerHTML = parseFloat(document.getElementById("gesamtPreis").innerHTML.replace(",", ".")) + parseFloat(knoten.children[index+1].children[2].innerHTML.replace(",", "."));
	document.getElementById("gesamtPreis").innerHTML = document.getElementById("gesamtPreis").innerHTML.replace(".", ",");
	pizzaListKnoten.appendChild(newPizzaDivision);
	//cartPosition++;
}

String.prototype.trim = function () {
	"use strict";
    return this.replace(/^\s+/g, '').replace(/\s+$/g, '');
} 

function deletePizza(index)
{
	"use strict";	
	var knoten = document.getElementById("pizzaList");
	var element = document.getElementById('pizza' + index);
	knoten.removeChild(element);
	//cartPosition--;
	
	// Preis rausfinden
	var preis;
	var pizzaListKnoten = document.getElementById("pizzen");
	for(var i = 1; i < pizzaListKnoten.childElementCount; i++)
	{		
		if(pizzaListKnoten.children[i].children[1].innerHTML.trim() == element.children[1].innerHTML.trim())
		{
			preis = pizzaListKnoten.children[i].children[2].innerHTML;
			break;
		}
		
	}
	
	//Gesamtpreis aktualisieren
	document.getElementById("gesamtPreis").innerHTML = parseFloat(document.getElementById("gesamtPreis").innerHTML.replace(",", ".")) - parseFloat(preis.replace(",", "."));
	document.getElementById("gesamtPreis").innerHTML = document.getElementById("gesamtPreis").innerHTML.replace(".", ",");
	
	if(document.getElementById("gesamtPreis").innerHTML == "NaN")
	{
		document.getElementById("gesamtPreis").innerHTML = 0.0;
	}
	
}

function deleteAllPizzas()
{
	"use strict";
	
	var knoten = document.getElementById("pizzaList");
	
	while(knoten.lastChild)
	{
		if(knoten.childElementCount == 1) // if abfrage berücksichtigt "zu löschende Pizza"
		{
			break;
		}
		knoten.removeChild(knoten.lastChild);
	}
	
	// Gesamtpreis aktualisieren
	document.getElementById("gesamtPreis").innerHTML = parseFloat("0.0");

}

function selectNextPizza(kunde, step)
{
	"use strict";
	//document.print("test");
	
	var knoten = document.getElementById("Kunde" + kunde + "Pizza" + step);
	var idSelectedNode = "";
	for(var i = 0; i < knoten.childElementCount; i++)
	{
		
		if(knoten.children[i].childElementCount == 1) // bei Pizzabäcker ist die erste Node keine Option
		{
			
			if(knoten.children[i].children[0].checked == true)
			{
				
				idSelectedNode = knoten.children[i].children[0].id;
			
				if(idSelectedNode != "")
				{
					var xmlHttp = new XMLHttpRequest();
					xmlHttp.onreadystatechange = function()
					{
						if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
						{
							//document.getElementById("address").value = xmlHttp.responseText;
							document.write(xmlHttp.responseText);
						}
					}
					xmlHttp.open("GET", "BaeckerGETRequest.php?id="+idSelectedNode, true);
					//xmlhttp.setRequestHeader("Content-Type", "application/json; charset=utf-8");
					xmlHttp.send();
				}
				
				if(i != knoten.children[i].childElementCount)
				{
					knoten.children[i+1].children[0].disabled = false;
					i = i + 1; // das Element nach den checked Knoten klickbar machen
				}
			}
			else
			{
				knoten.children[i].children[0].disabled = true;
			}
			
		}	
	}
	
	//document.write(idSelectedNode);
	
}

function selectNextOrder(kunde)
{
	"use strict";
	
	
	var knoten = document.getElementById("Kunde" + kunde);
	var idSelectedNode = "";
	
	for(var i = 0; i < knoten.childElementCount; i++)
	{
			if(knoten.children[i].children[0].checked == true)
			{
				idSelectedNode = knoten.children[i].children[0].id;
				
				if(idSelectedNode != "")
				{
					var xmlHttp = new XMLHttpRequest();
					xmlHttp.onreadystatechange = function()
					{
						if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
						{
							//document.getElementById("address").value = xmlHttp.responseText;
							document.write(xmlHttp.responseText);
						}
					}
					xmlHttp.open("GET", "FahrerGETRequest.php?id="+idSelectedNode, true);
					//xmlhttp.setRequestHeader("Content-Type", "application/json; charset=utf-8");
					xmlHttp.send();
				}
				
				
				
				if(i != knoten.children[i].childElementCount-1)
				{
					knoten.children[i+1].children[0].disabled = false;
					i = i + 1; // das Element nach den checked Knoten klickbar machen
				}
			}
			else
			{
				knoten.children[i].children[0].disabled = true;
			}
	}
}

function sendPizzeRequest()
{
	if(document.getElementById("address").value != "")
	{
		var pizzaVektor = Array();
		var knoten = document.getElementById("pizzaList");
			
		for(var i = 1; i < knoten.childElementCount; i++)
		{	
				var pizza = knoten.children[i].children[1].innerHTML;
				pizzaVektor[i-1] = pizza;
		}
		
		var xmlHttp = new XMLHttpRequest();
		xmlHttp.onreadystatechange = function()
		{
			if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
			{
				//document.getElementById("address").value = xmlHttp.responseText;
				document.write(xmlHttp.responseText);
			}
		}
		deleteAllPizzas();
		var address = document.getElementById("address").value;
		xmlHttp.open("GET", "BestellungPostRequest.php?address="+address+"&pizzen=" + JSON.stringify(pizzaVektor), true);
		//xmlhttp.setRequestHeader("Content-Type", "application/json; charset=utf-8");
		xmlHttp.send();
		
		
	}
}