let xhr = new XMLHttpRequest();
xhr.open('GET','serve.php')
xhr.onload = function () {

    if (xhr.status == 200) {
    

        let json = JSON.parse(xhr.responseText);
        let prueba = `andres`;
        let template = `<h1>${prueba}</h1>`;

        console.log(json);
        console.log(template);
        
    }else{
        console.log(`ERROR ${xhr.status}`)
    }
    
}
xhr.send();