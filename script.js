var menu=document.getElementsByClassName("menu")[0];
var site=document.getElementsByClassName("l-site")[0];
menu.onclick = function yeet(){
	if (site.classList.contains("is-open")) {
		menu.classList.remove("is-active");
		site.classList.remove("is-open");
	} else {
	    menu.classList.add("is-active");
	  	site.classList.add("is-open");
	}
};