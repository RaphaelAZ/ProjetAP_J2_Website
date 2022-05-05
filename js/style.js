function createPDF(Numinter){
 var redirection = "<?php PDF("+Numinter+") ?>";
 document.write(redirection);
}