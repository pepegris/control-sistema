<link rel="stylesheet" href="../assets/css/bootstrap-5.2.0-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/animations.css">
<style>
  body {
    height: 100vh;
    background-image: url('thumb-1920-12453.jpg');
    background-size: cover;
    background-color: #242943;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;





  }

  h1 {
    color: white;
  }

  img {
    width: 110px;
    border-radius: 100%;
  }

  .preloader {
    width: 100px;
    height: 100px;
    border: 10px solid #eee;
    border-top: 10px solid #666;
    border-radius: 50%;
    animation-name: girar;
    animation-duration: 2s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
  }

  @keyframes girar {
    from {
      transform: rotate(0deg);
    }

    to {
      transform: rotate(360deg);
    }
  }
</style>

<body>

  <h1>Revisando Datos</h1>
  <div class="preloader"></div>
  <img class="pulse" src="../assets/giphy.gif" alt="" srcset="">


</body>

<script type="text/javascript">
  const divs = document.querySelectorAll('.pulse');

  let i = 0;
  let interval = setInterval(() => {
    divs[i++].style.display = 'block';
    if (i == divs.length)
      clearInterval(interval);
  }, 60000);
</script>


<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>