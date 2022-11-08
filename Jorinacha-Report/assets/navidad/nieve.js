const body = document.querySelector('body')


const crear_nieve = () => {

    let copo = document.createElement('i')
    let x = innerWidth * Math.random()
    let size = Math.random() * 8
    let z = Math.round(Math.random()) * 100
    let delay = Math.random() * 5
    let anima = (Math.random() * 10) + 5

    copo.style.left = x + 'px'
    copo.style.width = size + 'px'
    copo.style.height = size + 'px'
    copo.style.zIndex = z
    copo.style.animationDelay = delay + 's'
    copo.style.animationDuration = anima + 's'
    

    body.appendChild(copo)

    setTimeout(()=> {

        copo.remove()

    },anima * 1000)

}

setInterval(crear_nieve,100)
