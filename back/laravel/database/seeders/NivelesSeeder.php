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
        // Ejemplos para HTML
        Nivel::create([
            'pregunta' => '¿Cuál es la etiqueta básica de HTML?',
            'resp_correcta' => '<html>',
            'language' => 'html'
        ]);
        Nivel::create([
            'pregunta' => '¿Cómo se define un comentario en HTML?',
            'resp_correcta' => '<!-- Comentario -->',
            'language' => 'html'
        ]);
        Nivel::create([
            'pregunta' => '¿Qué etiqueta se usa para insertar una imagen?',
            'resp_correcta' => '<img src="url" alt="descripcion">',
            'language' => 'html'
        ]);
        Nivel::create([
            'pregunta' => '¿Cómo se crea un enlace en HTML?',
            'resp_correcta' => '<a href="url">Texto del enlace</a>',
            'language' => 'html'
        ]);
        Nivel::create([
            'pregunta' => '¿Qué etiqueta se utiliza para crear un título?',
            'resp_correcta' => '<h1> Título </h1>',
            'language' => 'html'
        ]);
        Nivel::create([
            'pregunta' => '¿Qué etiqueta se utiliza para crear una lista desordenada?',
            'resp_correcta' => '<ul><li>Item 1</li></ul>',
            'language' => 'html'
        ]);
        Nivel::create([
            'pregunta' => '¿Cómo se hace un salto de línea en HTML?',
            'resp_correcta' => '<br>',
            'language' => 'html'
        ]);
        Nivel::create([
            'pregunta' => '¿Qué etiqueta se usa para definir una tabla en HTML?',
            'resp_correcta' => '<table>',
            'language' => 'html'
        ]);
        Nivel::create([
            'pregunta' => '¿Cómo se define un formulario en HTML?',
            'resp_correcta' => '<form action="url" method="post">...</form>',
            'language' => 'html'
        ]);
        Nivel::create([
            'pregunta' => '¿Qué atributo es utilizado para definir un campo de texto en HTML?',
            'resp_correcta' => '<input type="text" />',
            'language' => 'html'
        ]);

        // Ejemplos para CSS
        NivelCss::create([
            'pregunta' => '¿Cómo se cambia el color de texto?',
            'resp_correcta' => 'color: red;',
            'language' => 'css'
        ]);
        NivelCss::create([
            'pregunta' => '¿Cómo se establece el tamaño de una fuente?',
            'resp_correcta' => 'font-size: 16px;',
            'language' => 'css'
        ]);
        NivelCss::create([
            'pregunta' => '¿Qué propiedad se utiliza para cambiar el fondo de un elemento?',
            'resp_correcta' => 'background-color: blue;',
            'language' => 'css'
        ]);
        NivelCss::create([
            'pregunta' => '¿Cómo se alinean los elementos de una lista horizontalmente?',
            'resp_correcta' => 'list-style-type: none; display: flex;',
            'language' => 'css'
        ]);
        NivelCss::create([
            'pregunta' => '¿Cómo se establece el margen de un elemento?',
            'resp_correcta' => 'margin: 20px;',
            'language' => 'css'
        ]);
        NivelCss::create([
            'pregunta' => '¿Cómo se define un borde redondeado en un div?',
            'resp_correcta' => 'border-radius: 10px;',
            'language' => 'css'
        ]);
        NivelCss::create([
            'pregunta' => '¿Cómo se hace que el texto se ponga en negrita?',
            'resp_correcta' => 'font-weight: bold;',
            'language' => 'css'
        ]);
        NivelCss::create([
            'pregunta' => '¿Cómo se oculta un elemento?',
            'resp_correcta' => 'display: none;',
            'language' => 'css'
        ]);
        NivelCss::create([
            'pregunta' => '¿Cómo se cambia la fuente de un texto?',
            'resp_correcta' => 'font-family: Arial, sans-serif;',
            'language' => 'css'
        ]);
        NivelCss::create([
            'pregunta' => '¿Cómo se hace que el texto se alinee al centro?',
            'resp_correcta' => 'text-align: center;',
            'language' => 'css'
        ]);

        // Ejemplos para JS
        NivelJs::create([
            'pregunta' => '¿Cómo se declara una variable?',
            'resp_correcta' => 'let variable;',
            'language' => 'js'
        ]);
        NivelJs::create([
            'pregunta' => '¿Cómo se define una función en JS?',
            'resp_correcta' => 'function miFuncion() {}',
            'language' => 'js'
        ]);
        NivelJs::create([
            'pregunta' => '¿Cómo se llama a una función en JS?',
            'resp_correcta' => 'miFuncion();',
            'language' => 'js'
        ]);
        NivelJs::create([
            'pregunta' => '¿Cómo se crea un array en JS?',
            'resp_correcta' => 'let miArray = [1, 2, 3];',
            'language' => 'js'
        ]);
        NivelJs::create([
            'pregunta' => '¿Cómo se declara un objeto en JS?',
            'resp_correcta' => 'let persona = { nombre: "Juan", edad: 30 };',
            'language' => 'js'
        ]);
        NivelJs::create([
            'pregunta' => '¿Cómo se accede a un valor de un array?',
            'resp_correcta' => 'miArray[0];',
            'language' => 'js'
        ]);
        NivelJs::create([
            'pregunta' => '¿Cómo se hace un comentario en una sola línea en JS?',
            'resp_correcta' => '// Este es un comentario',
            'language' => 'js'
        ]);
        NivelJs::create([
            'pregunta' => '¿Cómo se escribe un bucle "for" en JS?',
            'resp_correcta' => 'for (let i = 0; i < 10; i++) {}',
            'language' => 'js'
        ]);
        NivelJs::create([
            'pregunta' => '¿Cómo se compara si dos valores son iguales en JS?',
            'resp_correcta' => 'if (a === b) {}',
            'language' => 'js'
        ]);
        NivelJs::create([
            'pregunta' => '¿Cómo se declara una constante en JS?',
            'resp_correcta' => 'const miConstante = 100;',
            'language' => 'js'
        ]);
    }
}
