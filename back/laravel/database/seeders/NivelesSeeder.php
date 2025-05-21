<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nivel;
use App\Models\NivelCss;
use App\Models\NivelJs;

class NivelesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Niveles HTML detallados
        Nivel::create([
            'pregunta' => 'Crea un documento HTML5 completo que incluya: declaracion <!DOCTYPE html>, <html lang="es">, sección <head> con <meta charset="UTF-8">, un <title>Mi primera página</title> y <link rel="stylesheet" href="styles.css">, y un <body> vacío.',
            'resp_correcta' => "<!DOCTYPE html>\n<html lang=\"es\">\n<head>\n  <meta charset=\"UTF-8\">\n  <title>Mi primera página</title>\n  <link rel=\"stylesheet\" href=\"styles.css\">\n</head>\n<body>\n\n</body>\n</html>",
            'language' => 'html'
        ]);

        Nivel::create([
            'pregunta' => 'Escribe el HTML necesario dentro del <body> para un <h1>Bienvenido a mi sitio</h1>, un <h2>Introducción</h2> y dos párrafos: el primero de al menos 20 palabras, el segundo de al menos 10 palabras.',
            'resp_correcta' => "<h1>Bienvenido a mi sitio</h1>\n<h2>Introducción</h2>\n<p>En este primer párrafo escribo al menos veinte palabras para asegurarme de que cumplo con el requisito establecido en el enunciado de la pregunta, describiendo brevemente el contenido.</p>\n<p>Este párrafo tiene al menos diez palabras para que también sea válido.</p>",
            'language' => 'html'
        ]);

        Nivel::create([
            'pregunta' => 'Dentro del <body>, inserta una imagen con src="logo.png", alt descriptivo, width="200", height="100" y centrada horizontalmente.',
            'resp_correcta' => "<div style=\"text-align:center;\">\n  <img src=\"logo.png\" alt=\"Logotipo de la empresa\" width=\"200\" height=\"100\">\n</div>",
            'language' => 'html'
        ]);

        Nivel::create([
            'pregunta' => 'Crea un <nav> con una <ul> que tenga tres <li> con enlaces: "Inicio" a index.html, "Acerca de" a about.html y "Contacto" a contact.html.',
            'resp_correcta' => "<nav>\n  <ul>\n    <li><a href=\"index.html\">Inicio</a></li>\n    <li><a href=\"about.html\">Acerca de</a></li>\n    <li><a href=\"contact.html\">Contacto</a></li>\n  </ul>\n</nav>",
            'language' => 'html'
        ]);

        Nivel::create([
            'pregunta' => 'En el <body>, añade una lista ordenada con tres elementos de texto libre y, debajo, una lista desordenada con otros tres elementos.',
            'resp_correcta' => "<ol>\n  <li>Primer elemento ordenado</li>\n  <li>Segundo elemento ordenado</li>\n  <li>Tercer elemento ordenado</li>\n</ol>\n<ul>\n  <li>Primer elemento desordenado</li>\n  <li>Segundo elemento desordenado</li>\n  <li>Tercer elemento desordenado</li>\n</ul>",
            'language' => 'html'
        ]);

        Nivel::create([
            'pregunta' => 'Diseña una tabla (<table>) con <thead> y <tbody>, encabezado con "Nombre", "Edad", "Ciudad" y dos filas de datos ficticios.',
            'resp_correcta' => "<table>\n  <thead>\n    <tr>\n      <th>Nombre</th>\n      <th>Edad</th>\n      <th>Ciudad</th>\n    </tr>\n  </thead>\n  <tbody>\n    <tr>\n      <td>Ana Pérez</td>\n      <td>28</td>\n      <td>Madrid</td>\n    </tr>\n    <tr>\n      <td>Carlos Ruiz</td>\n      <td>34</td>\n      <td>Barcelona</td>\n    </tr>\n  </tbody>\n</table>",
            'language' => 'html'
        ]);

        Nivel::create([
            'pregunta' => 'Crea un formulario POST a registro.php con inputs: texto para usuario (name="usuario"), email (name="email"), password (name="contrasena") y un botón Registrar.',
            'resp_correcta' => "<form action=\"registro.php\" method=\"post\">\n  <label for=\"usuario\">Usuario:</label>\n  <input type=\"text\" id=\"usuario\" name=\"usuario\">\n  <label for=\"email\">Email:</label>\n  <input type=\"email\" id=\"email\" name=\"email\">\n  <label for=\"contrasena\">Contraseña:</label>\n  <input type=\"password\" id=\"contrasena\" name=\"contrasena\">\n  <button type=\"submit\">Registrar</button>\n</form>",
            'language' => 'html'
        ]);

        Nivel::create([
            'pregunta' => 'Inserta un <video> que apunte a video.mp4, tenga controls y texto alternativo para navegadores que no lo soporten.',
            'resp_correcta' => "<video src=\"video.mp4\" controls>\n  Tu navegador no soporta la reproducción de vídeo.\n</video>",
            'language' => 'html'
        ]);

        Nivel::create([
            'pregunta' => 'Dentro de <body>, crea un <article> con <header><h2>...</h2></header>, un párrafo de al menos 15 palabras y <footer> con texto de copyright.',
            'resp_correcta' => "<article>\n  <header>\n    <h2>Titulo del artículo</h2>\n  </header>\n  <p>Este párrafo dentro del artículo contiene al menos quince palabras para demostrar la correcta aplicación de los requisitos del ejercicio planteado.</p>\n  <footer>\n    &copy; 2025 Mi Sitio Web\n  </footer>\n</article>",
            'language' => 'html'
        ]);

        Nivel::create([
            'pregunta' => 'Crea un documento HTML5 completo con comentario <!-- Aquí empieza el contenido principal --> antes de un <div class="main-content"> que contenga un párrafo de ejemplo.',
            'resp_correcta' => "<!DOCTYPE html>\n<html lang=\"es\">\n<head>\n  <meta charset=\"UTF-8\">\n  <title>Documento con comentario</title>\n</head>\n<body>\n  <!-- Aquí empieza el contenido principal -->\n  <div class=\"main-content\">\n    <p>Este es un párrafo de ejemplo dentro de main-content.</p>\n  </div>\n</body>\n</html>",
            'language' => 'html'
        ]);

        // Niveles CSS básicos
        NivelCss::create([
            'pregunta' => '¿Cómo se cambia el color de texto a rojo?',
            'resp_correcta' => 'color: red;',
            'language' => 'css'
        ]);

        NivelCss::create([
            'pregunta' => '¿Cómo se establece el tamaño de fuente a 16px?',
            'resp_correcta' => 'font-size: 16px;',
            'language' => 'css'
        ]);

        NivelCss::create([
            'pregunta' => '¿Qué propiedad modifica el fondo de un elemento?',
            'resp_correcta' => 'background-color: blue;',
            'language' => 'css'
        ]);

        NivelCss::create([
            'pregunta' => '¿Cómo ocultas un elemento del flujo de la página?',
            'resp_correcta' => 'display: none;',
            'language' => 'css'
        ]);

        NivelCss::create([
            'pregunta' => '¿Cómo centras el texto dentro de un elemento?',
            'resp_correcta' => 'text-align: center;',
            'language' => 'css'
        ]);

        NivelCss::create([
            'pregunta' => '¿Cómo añades un margen de 20px alrededor de un elemento?',
            'resp_correcta' => 'margin: 20px;',
            'language' => 'css'
        ]);

        NivelCss::create([
            'pregunta' => '¿Cómo redondeas las esquinas de un div con 10px?',
            'resp_correcta' => 'border-radius: 10px;',
            'language' => 'css'
        ]);

        NivelCss::create([
            'pregunta' => '¿Cómo haces que el texto esté en negrita?',
            'resp_correcta' => 'font-weight: bold;',
            'language' => 'css'
        ]);

        NivelCss::create([
            'pregunta' => '¿Cómo cambias la fuente a Arial sans-serif?',
            'resp_correcta' => 'font-family: Arial, sans-serif;',
            'language' => 'css'
        ]);

        NivelCss::create([
            'pregunta' => '¿Cómo conviertes una lista en flex horizontal?','resp_correcta' => 'list-style-type: none; display: flex;','language' => 'css'
        ]);

        // Niveles JS básicos
        NivelJs::create([
            'pregunta' => '¿Cómo declaras una variable con let?',
            'resp_correcta' => 'let variable;','language' => 'js'
        ]);

        NivelJs::create([
            'pregunta' => '¿Cómo defines una función llamada miFuncion?','resp_correcta' => 'function miFuncion() {}','language' => 'js'
        ]);

        NivelJs::create([
            'pregunta' => '¿Cómo llamas a la función miFuncion?','resp_correcta' => 'miFuncion();','language' => 'js'
        ]);

        NivelJs::create([
            'pregunta' => '¿Cómo creas un array con tres números?','resp_correcta' => 'let miArray = [1, 2, 3];','language' => 'js'
        ]);

        NivelJs::create([
            'pregunta' => '¿Cómo declaras un objeto persona con nombre y edad?','resp_correcta' => 'let persona = { nombre: "Juan", edad: 30 };','language' => 'js'
        ]);

        NivelJs::create([
            'pregunta' => '¿Cómo accedes al primer elemento de miArray?','resp_correcta' => 'miArray[0];','language' => 'js'
        ]);

        NivelJs::create([
            'pregunta' => '¿Cómo escribes un comentario de una sola línea?','resp_correcta' => '// Este es un comentario','language' => 'js'
        ]);

        NivelJs::create([
            'pregunta' => '¿Cómo escribes un bucle for que itere 10 veces?','resp_correcta' => 'for (let i = 0; i < 10; i++) {}','language' => 'js'
        ]);

        NivelJs::create([
            'pregunta' => '¿Cómo comparas si a es estrictamente igual a b?','resp_correcta' => 'if (a === b) {}','language' => 'js'
        ]);

        NivelJs::create([
            'pregunta' => '¿Cómo declaras una constante miConstante con valor 100?','resp_correcta' => 'const miConstante = 100;','language' => 'js'
        ]);
    }
}
