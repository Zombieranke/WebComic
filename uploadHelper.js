function addUpload(oldnode)
{
	oldnode.setAttribute("onChange","");
	
	var node = document.createElement("fieldset");
	node.setAttribute("class", "addedField");
	
	var input = document.createElement("input");
	input.classList.add("inputFile");
	input.setAttribute("type","file");
	input.setAttribute("name","upload[]");
	input.setAttribute("onChange","addUpload(this)");
	node.appendChild(input);
	
	input = document.createElement("br");
	node.appendChild(input);
	
	input = document.createElement("div");
	var text = document.createTextNode("Strip Name: ");
	input.appendChild(text);
	node.appendChild(input);
	
	input = document.createElement("input");
	input.classList.add("inputText");
	input.setAttribute("type","text");
	input.setAttribute("name","stripName[]");
	input.setAttribute("placeholder","Enter the name of the strip");
	node.appendChild(input);
	
	input = document.createElement("br");
	node.appendChild(input);
	
	input = document.createElement("div");
	var text = document.createTextNode("Release Date: ");
	input.appendChild(text);
	node.appendChild(input);
	
	input = document.createElement("input");
	input.classList.add("inputDate");
	input.setAttribute("type","datetime-local");
	input.setAttribute("name","releaseDate[]");
	input.setAttribute("placeholder","YYYY-MM-DD HH:DD:SS");
	node.appendChild(input);
	
	input = document.createElement("br");
	node.appendChild(input);
	
	input = document.createElement("div");
	text = document.createTextNode("Annotation: ");
	input.appendChild(text);
	node.appendChild(input);
	
	input = document.createElement("textarea");
	input.classList.add("uploadAnnotation");
	input.setAttribute("name","annotation[]");
	node.appendChild(input);
	
	/*var input = document.createElement("input");
	input.setAttribute("type","reset");
	input.setAttribute("name","resetForm");
	input.setAttribute("value","Reset");
	node.appendChild(input);*/
	
	var form = document.getElementById("containerFieldset");
	
	var sel = document.getElementById("comicSelection");
	
	form.insertBefore(node,sel);

}

function purgeForm()
{
	var form = document.getElementById("uploadForm");
	var nodesToPurge = document.getElementsByClassName("addedField");
	for(var i=nodesToPurge.length-1; i>=0; i--)
	{
		form.removeChild(nodesToPurge[i]);
	}

}