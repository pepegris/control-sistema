<link rel="stylesheet" href="../../assets/css/bootstrap-5.2.0-dist/css/bootstrap.min.css">
<style>
  body {
    min-height: 100vh; /* Usar min-height para permitir scroll */
    background-image: url('thumb-1920-12453.jpg'); /* Asegúrate que la ruta sea correcta */
    background-size: cover;
    background-color: #242943;
    background-attachment: fixed; /* El fondo se queda quieto al bajar */
    
    display: flex;
    flex-direction: column;
    align-items: center;
    /* Quitamos justify-content: center para que empiece arriba y vaya bajando */
    padding-top: 50px; 
    color: white;
    font-family: 'Segoe UI', sans-serif;
  }

  h1 { font-weight: bold; text-transform: uppercase; margin-bottom: 20px; text-shadow: 2px 2px 4px #000; }
  h3 { font-size: 1.1rem; margin: 5px 0; text-shadow: 1px 1px 2px #000; }

  /* Estilo del Preloader */
  .preloader {
    width: 80px;
    height: 80px;
    border: 8px solid rgba(255,255,255, 0.3);
    border-top: 8px solid #00ff99; /* Color verde neon para resaltar */
    border-radius: 50%;
    animation: girar 1s linear infinite;
    margin-bottom: 30px;
  }

  @keyframes girar {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  /* Contenedor para los mensajes de PHP */
  #log-container {
    width: 90%;
    max-width: 800px;
    background: rgba(0, 0, 0, 0.5); /* Fondo semitransparente para leer mejor */
    padding: 20px;
    border-radius: 10px;
    margin-top: 20px;
    text-align: center;
    border: 1px solid #444;
  }
</style>

<body>

  <h1>Importando Datos...</h1>
  
  <div class="preloader" id="spinner"></div>

  <div id="log-container">
     <p style="color:#aaa;">Procesando conexión con servidores...</p>