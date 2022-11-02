<html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Reporte de pedidos</title>
        <link rel="stylesheet" href="css/style.css">

        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

        <!-- jQuery Modal -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    </head>

    <body>
        <div style="display:flex; flex-direction:column;">
            <h1 style="margin-bottom:50px;">Reporte de pedidos</h1>

            <div class="filters-container">
                <input id="begin_date" type="date" text="Rango de inicio" class="date-picker" />
                <input id="end_date" type="date" text="Rango de fin" class="date-picker" />
                <button id="filter_button" class="filter-button">Filtrar</button>
            </div>
        </div>

        <table id="report_ordes_table" class="display" style="width:90%">
                <thead>
                    <tr>
                        <th>Número de pedido</th>
                        <th>Fecha de documento</th>
                        <th>Estatus de pedido</th>
                        <th>Guía de rastreo</th>
                        <th>Total de documento</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
        </table>

        <!-- Modal HTML embedded directly into document -->
        <div id="detail_modal" class="modal">
            <table id="detail_table_modal" class="display" style="width:100%;">
                <thead>
                    <tr>
                        <th>Clave de artículo</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Almacén</th>
                        <th>Precio por unidad</th>
                        <th>Precio total</th>
                        <th>Subtotal</th>
                        <th>Impuesto</th>
                        <th>Total</th>
                    </tr>
                </thead>
            </table>
            <a href="#" rel="modal:close">Close</a>
        </div>

  <script src="js/js.js"></script>
    </body>
</html>