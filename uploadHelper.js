function addUpload(oldnode)
{
	oldnode.setAttribute("onChange","");
	
	var node = document.createElement("fieldset");
	node.setAttribute("class", "addedField");
	
	var input = document.createElement("input");
	input.setAttribute("type","file");
	input.setAttribute("name","upload[]");
	input.setAttribute("onChange","addUpload(this)");
	node.appendChild(input);
	
	input = document.createElement("input");
	input.setAttribute("type","text");
	input.setAttribute("name","releaseDate[]");
	node.appendChild(input);
	
	input = document.createElement("textarea");
	input.setAttribute("name","annotation[]");
	node.appendChild(input);
	
	/*var input = document.createElement("input");
	input.setAttribute("type","reset");
	input.setAttribute("name","resetForm");
	input.setAttribute("value","Reset");
	node.appendChild(input);*/
	
	var form = document.getElementById("uploadForm");
	
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