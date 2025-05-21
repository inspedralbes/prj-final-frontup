# Documentació
Llistat d'alguns dels punts que han de quedar explicats en aquesta carpeta. Poden ser tots en aquest fitxer o en diversos fitxers enllaçats.

És obligatori modificar aquest document!!

## Documentació bàsica MÍNIMA
 * Objectius
    L'objectiu principal del nostre projecte és fer que la gent millori la seva capacitat per programar en els tres llenguatges bàsics de programació: HTML, CSS i JavaScript. Dins del projecte, oferim un xat per a resoldre dubtes de l'usuari en temps real, un sistema de nivells bàsics per ajudar-lo a progressar gradualment, una seccio per veure els teus projectes i una altre per veure el projectes dels altres usuaris.

     * Arquitectura bàsica
    L'aplicació segueix una arquitectura basada en microserveis:
    Frontend: desenvolupat amb Nuxt.js per proporcionar una experiència d'usuari ràpida i reactiva.
    Backend: utilitzem Laravel i Node.js per gestionar la lògica de negoci, autenticació, i les API.
    Base de dades: MySQL per a l'emmagatzematge d'usuaris, nivells, projectes, etc.
    Contenidors: Docker per facilitar l'entorn de desenvolupament i desplegament.

   * Tecnologies utilitzades
    Frontend: Nuxt
    Backend: Laravel i Node.js
    Base de dades: MySQL
    Entorn de desenvolupament/desplegament: Docker
    Altres: WebSockets (per al xat).

   * Interrelació entre els diversos components
    El frontend consumeix l’API del backend mitjançant crides HTTP.
    El xat utilitza WebSockets per comunicar-se en temps real amb el servidor.
    Laravel s’encarrega de la gestió d’usuaris, autenticació i lògica de progrés.
    Node.js s’encarrega de les connexions WebSocket i de la part més dinàmica en temps real.
    Tots els serveis estan orquestrats amb Docker.

 * Com crees l'entorn de desenvolupament
    Clonar el repositori.
    Executar docker-compose up --build per aixecar els serveis.
    Accedir al frontend a través de http://localhost:3000.
    El backend Laravel estarà disponible a http://localhost:8000.
    El node estara disponible a http://localhost:5000.
    Crear i omplir els fitxers .env per al frontend i backend amb les variables de configuració.
    Executar les migracions i seeders amb php artisan migrate --seed.

 * Com desplegues l'aplicació a producció
 * Llistat d'endpoints de l'API de backend
    * Rutes
    Endpoints d’Autenticació: 
    POST	/register	                        Registre d’un nou usuari
    POST	/login                              Autenticació (login) d’un usuari
    Endpoints d'Usuari (autenticats):  
    GET	/user	                              Obté informació de l’usuari actual
    POST	/user/avatar                        Actualitza l’avatar de l’usuari
    Endpoints de Projectes:
    GET	/projects	                        Llista de projectes de l’usuari actual
    GET	/projects/all	                     Llista de tots els projectes (sense login)
    GET	/projectsAllPaginado	               Llista paginada de projectes
    POST	/projects	                        Crear un nou projecte
    GET	/projects/{id}	                     Obtenir un projecte pel seu ID
    GET	/projects/{id}/preview	            Obtenir la vista prèvia d’un projecte
    PUT	/projects/{id}	                     Actualitzar projecte
    DELETE	/projects/{id}	                  Esborrar projecte
    Endpoints de Nivells (Aprenentatge):
    HTML:
    GET	/niveles/html/pregunta/{id}	      Obté una pregunta HTML pel nivell
    POST	/niveles/html/verificar/{id}	      Verifica la resposta
    POST	/niveles/html/actualizar	         Actualitza el progrés del nivell

    CSS:
    GET	/niveles/css/pregunta/{id}	         Obté una pregunta CSS pel nivell
    POST	/niveles/css/verificar/{id}	      Verifica la resposta
    POST	/niveles/css/actualizar	            Actualitza el progrés del nivell

    JavaScript:
    GET	/niveles/js/pregunta/{id}	         Obté una pregunta JS pel nivell
    POST	/niveles/js/verificar/{id}	         Verifica la resposta
    POST	/niveles/js/actualizar	            Actualitza el progrés del nivell

    Nivell d’Usuari:
    GET	/nivells_usuaris	                  Llista de nivells d’usuari
    POST	/nivells_usuaris	                  Crear nou registre de nivell
    GET	/nivells_usuaris/{id}	            Obté nivell usuari pel seu ID
    PUT	/nivells_usuaris/{id}	            Actualitza nivell d’un usuari
    DELETE	/nivells_usuaris/{id}	         Elimina nivell d’usuari
   * Exemples de JSON de peticó
   POST /register
    {
      "name": "Joan Pérez",
      "email": "joan@example.com",
      "password": "12345678",
      "password_confirmation": "12345678"
    }

   POST /projectes
   {
    "nombre": "Calculadora Web",
    "user_id": 1,
    "html_code": "<!DOCTYPE html><html><head><title>Calculadora</title></head><body>...</body></html>",
    "css_code": "body { background-color: #f0f0f0; }",
    "js_code": "document.addEventListener('DOMContentLoaded', function() { /* código JS */ });",
    "statuts": 0
  }

   * Exemples de JSON de resposta i els seus codis d'estat 200? 404?
   GET /projects/{id}
   Response – 200 OK
   {
    "id": 1,
    "nombre": "Projecte HTML Bàsic",
    "user_id": 1,
    "html_code": "<h1>Hola, món!</h1>",
    "css_code": "h1 { color: blue; }",
    "js_code": "console.log('Hola, món!');",
    "statuts": 0
   }

 * Aplicació Android
 * Altres elements importants.
 * ...
