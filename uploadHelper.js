function addUpload()
{
	var node = document.createElement("fieldset");
	node.setAttribute("class", "addedField");
	
	var input = document.createElement("input");
	input.setAttribute("type","file");
	input.setAttribute("name","upload[]");
	input.setAttribute("onChange","addUpload()");
	node.appendChild(input);
	
	var input = document.createElement("input");
	input.setAttribute("type","text");
	input.setAttribute("name","releaseDate[]");
	node.appendChild(input);
	
	/*var input = document.createElement("input");
	input.setAttribute("type","reset");
	input.setAttribute("name","resetForm");
	input.setAttribute("value","Reset");
	node.appendChild(input);*/
	
	var form = document.getElementById("uploadForm");
	
	var btn = document.getElementById("resetButton");
	
	form.insertBefore(node,btn);

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