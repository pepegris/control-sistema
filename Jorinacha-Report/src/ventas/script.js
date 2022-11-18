let xhr = new XMLHttpRequest();
xhr.open('GET','serve.php')
xhr.onload = function () {

    if (xhr.status == 200) {
    

        let json = JSON.parse(xhr.responseText);

        let template = ``;
        json.map(function(data){
            template += `

            <h2>${data.id}</h2>
            <h3>${data.nombre}</h3>
            <h4>${data.edad}</h4>

            `

            return template

        })


        console.log(template);
        document.getElementsByClassName('lista').innerHtml=template
        
    }else{
        console.log(`ERROR ${xhr.status}`)
    }
    
}
xhr.send();