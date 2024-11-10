<?php
	header('Content-type: text/javascript'); 
	/********************************************************************/
	/*  SOFTWARE DE GESTION COMERCIAL ACEA DOMINICANA       	        */
	/*  ACEA DOMINICANA - REPUBLICA DOMINICANA							*/
	/*  CREADO POR JESUS GUTIERREZ ORTIZ								*/
	/*  FECHA CREACION 23/09/2014										*/
	/********************************************************************/
  	session_start();
	$loguser = $_SESSION['usuario'];
	$passuser = $_SESSION['contrasena'];
	$coduser = $_SESSION['codigo']; 
	$nomuser = $_SESSION['nombre']; 
?>
//*****************************************************************************
// Do not remove this notice.
//
// Copyright 2005-2006 by Ahmet HAYRAN.
// See http://www.devcen.com for terms of use.
//*****************************************************************************

//----------------------------------------------------------------------------
// Code to determine the browser
//----------------------------------------------------------------------------
	

	function loadXMLDoc(root) {
		
	var response=null;
	//var url=root + "tree_db.xml?" + Math.random(1000) ;
    var url=root + "tree_db.php?usuario=<?php echo $coduser; ?>";
	var imgRoot=root + "images/"; 
	
		// code for IE
		if (window.ActiveXObject) {
			
			req=new ActiveXObject("Microsoft.XMLDOM");
			req.async=false;
			req.load(url);
			parseXML(req, imgRoot);
			
		}
		
		// code for Mozilla, Firefox, Opera, etc.
		else if (document.implementation && document.implementation.createDocument) {
		
			req=new XMLHttpRequest();
			req.onreadystatechange = proReqChange;
			req.open("GET", url, true);
			req.send("");
			
		}
		
		else {
				alert("Your browser cannot handle this script");
				
		}
		
		
		
		function proReqChange() {
		
			if(req.readyState == 4) {
			
				response=req.responseXML;
				parseXML(response, imgRoot);
				
			} else { }
		
		}
		
	
	}
	
	
//----------------------------------------------------------------------------
// Code to Hide and Show Folder of Tree Menu
//----------------------------------------------------------------------------

	function hideShow(id, imgRoot) {

		if(document.getElementById(id).style.display=="none") {
			document.getElementById(id).style.display="block";
			document.getElementById("pic" + id).src= imgRoot + "002-2.gif";
		} else { document.getElementById(id).style.display="none"; document.getElementById("pic" + id).src= imgRoot + "002-1.gif"; }
	
	}
	
//----------------------------------------------------------------------------
// Code to Open Link
//----------------------------------------------------------------------------
	
	function openLink(tarlink, target) {
	
		window.open(tarlink, target);
	
	}
	
//----------------------------------------------------------------------------
// Code to Parse XML File and Covert it To DHTML Menu
//----------------------------------------------------------------------------
	
	function parseXML(response, imgRoot) {
	
	var nodePre, nodenum;
		
	document.getElementById("tree").innerHTML="";
	
	nodePre=response.getElementsByTagName("node");
	nodenum=nodePre.length;
	
	linkPre=response.getElementsByTagName("link");
	linknum=linkPre.length;
	
	
		for(i=1;i<nodenum;i++) {
		
			id=nodePre[i].getAttribute('id');
			pid=nodePre[i].parentNode.getAttribute("id");
			label=nodePre[i].getAttribute("label");
			
			var newNode=document.createElement("div");
			newNode.setAttribute("id","styFolder")
			newNode.innerHTML += "<span onclick=hideShow('" + id + "','" + imgRoot +"')><img id='pic"+ id +"' src='" + imgRoot + "002-1.gif' with='12' height='12'/>&nbsp;" + label +"</span><div class='styNodeRegion' style='display:none' id='" + id + "'>";
			
			document.getElementById(pid).appendChild(newNode);
		
		}
		
		for(i=0;i<linknum;i++) {
		
			pid=linkPre[i].parentNode.getAttribute("id");
			label=linkPre[i].getAttribute("label");
			
			var newLink=document.createElement("div");
			newLink.style.cursor="pointer";
			newLink.setAttribute("id","styLink");
			/*newLink.setAttribute("onclick","openLink('" + linkPre[i].getAttribute("href") + "')");*/
			newLink.innerHTML +="<span onclick='openLink(\"" + linkPre[i].getAttribute("href") + "\",\"" + linkPre[i].getAttribute("target") + "\")'><img src='"+ imgRoot +"001.gif'>" + label + "</span>";
			
			document.getElementById(pid).appendChild(newLink);
		
		}
		
		
	}

