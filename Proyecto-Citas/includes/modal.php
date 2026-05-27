<div id="miModal" class="modal">
        <div class="modal-contenido">
            <h3>Confirmación</h3>
            <p id="mensajeModal"></p>
            <div class="modal-botones">
                <button onclick="cerrarModal()"><a href="">Cancelar</a></button>
                <button class="red-button"><a id="confirmarAccion" href="#">Confirmar</a></button>
            </div>
        </div>
    </div>

    <script>
        function abrirModal(mensaje, enlace)
        {
            document.getElementById("mensajeModal").innerText = mensaje;
            document.getElementById("confirmarAccion").href = enlace;
            document.getElementById("miModal").style.display = "block";
        }

        function cerrarModal()
        {
            document.getElementById("miModal").style.display = "none";
        }
    </script>