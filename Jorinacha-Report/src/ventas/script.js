let xhr = new XMLHttpRequest();
xhr.open('GET','serve.php')
xhr.onload = function () {

    if (xhr.status == 200) {
    

        let json = xhr.responseText
        console.log(json)
        
    }else{
        console.log(`ERROR ${xhr.status}`)
    }
    
}
xhr.send();