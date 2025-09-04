from http.server import BaseHTTPRequestHandler, HTTPServer
import urllib.parse

class MiServidor(BaseHTTPRequestHandler):
    def do_GET(self):
        if self.path == "/":
            try:
                with open("index.html", "r", encoding="utf-8") as f:
                    contenido = f.read()
                self.send_response(200)
                self.send_header("Content-type", "text/html")
                self.end_headers()
                self.wfile.write(contenido.encode("utf-8"))
            except FileNotFoundError:
                self.send_error(404, "Archivo HTML no encontrado")
        else:
            self.send_error(404, "Ruta no encontrada")

    def do_POST(self):
        if self.path == "/":
            longitud = int(self.headers.get("Content-Length", 0))
            datos = self.rfile.read(longitud)
            campos = urllib.parse.parse_qs(datos.decode("utf-8"))

            nombre = campos.get("nombre", [""])[0]
            email = campos.get("email", [""])[0]

            respuesta = f"""
            <html><body>
            <h2>Datos recibidos:</h2>
            <p>Nombre: {nombre}</p>
            <p>Email: {email}</p>
            <a href="/">Volver</a>
            </body></html>
            """

            self.send_response(200)
            self.send_header("Content-type", "text/html")
            self.end_headers()
            self.wfile.write(respuesta.encode("utf-8"))
        else:
            self.send_error(405, "Método POST no permitido en esta ruta")

if __name__ == "__main__":
    puerto = 5500
    servidor = HTTPServer(("localhost", puerto), MiServidor)
    print(f"Servidor corriendo en http://localhost:{puerto}")
    servidor.serve_forever()