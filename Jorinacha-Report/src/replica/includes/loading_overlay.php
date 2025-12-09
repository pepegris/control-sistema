<div id="loadingOverlay" style="display: none;">
    <div class="spinner"></div>
    <div class="loading-text">Procesando Datos...</div>
    <div class="loading-subtext">Por favor espere, esta operación puede tardar unos minutos.</div>
</div>

<style>
    #loadingOverlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.9); /* Negro casi sólido */
        z-index: 9999;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(5px);
    }

    .spinner {
        width: 60px;
        height: 60px;
        border: 6px solid #333;
        border-top: 6px solid #ffd700; /* Amarillo Profit */
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-text {
        color: white;
        font-size: 1.5em;
        font-weight: bold;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-family: 'Segoe UI', sans-serif;
    }
    
    .loading-subtext {
        color: #aaa;
        margin-top: 10px;
        font-size: 0.9em;
        font-family: 'Segoe UI', sans-serif;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Busca cualquier formulario en la página
        var forms = document.querySelectorAll('form');
        
        forms.forEach(function(form) {
            form.addEventListener('submit', function() {
                // Muestra el overlay al enviar
                var overlay = document.getElementById('loadingOverlay');
                if(overlay) {
                    overlay.style.display = 'flex';
                }
            });
        });
    });
</script>