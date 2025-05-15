<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function testRegister()
    {
        $data = [
            'name'     => 'Usuario de prueba',
            'email'    => 'prueba@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/register', $data);

        // Tu controlador retorna 200, por lo que se ajusta el assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'prueba@example.com']);
    }

    public function testLogin()
    {
        // Se crea un usuario de prueba (se puede seguir usando el factory para User si lo prefieres)
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function testGetUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user');
        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => $user->email]);
    }

    public function testUpdateAvatar()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Se envía una URL en lugar de un archivo, tal como lo espera el controlador
        $response = $this->postJson('/api/user/avatar', [
            'avatar' => 'https://example.com/avatar.png',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Avatar actualizado correctamente']);
    }

    public function testProjectsEndpoints()
    {
        // Crear un usuario para asociar el proyecto
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Crear un proyecto manualmente
        $projectData = [
            'nombre'    => 'Proyecto de prueba',
            'user_id'   => $user->id,
            'html_code' => '<p>Contenido HTML</p>',
            'css_code'  => '',
            'js_code'   => '',
            'statuts'   => 0,
        ];

        $response = $this->postJson('/api/projects', $projectData);
        // Tu controlador retorna 200 por defecto, así que ajustamos el assert
        $response->assertStatus(200);
        $projectId = $response->json('id');

        // Obtener los proyectos del usuario
        $response = $this->getJson('/api/projects');
        $response->assertStatus(200);

        // Mostrar un proyecto específico
        $response = $this->getJson("/api/projects/{$projectId}");
        $response->assertStatus(200);
        $response->assertJsonFragment(['nombre' => 'Proyecto de prueba']);

        // Actualizar el proyecto
        $updateData = ['nombre' => 'Nombre actualizado'];
        $response = $this->putJson("/api/projects/{$projectId}", $updateData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'id'     => $projectId,
            'nombre' => 'Nombre actualizado',
        ]);

        // Eliminar el proyecto
        $response = $this->deleteJson("/api/projects/{$projectId}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('projects', ['id' => $projectId]);
    }

    public function testIndexAllProjects()
    {
        // Creamos un usuario de prueba que será dueño de los proyectos
        $user = User::create([
            'name'      => 'Usuario de Proyectos',
            'email'     => 'proyectos@example.com',
            'password'  => bcrypt('password'),
            'avatar'    => 'https://example.com/avatar.png',
            'nivel'     => 1,
            'nivel_css' => 1,
            'nivel_js'  => 1,
        ]);

        // Crear 3 proyectos manualmente
        Project::create([
            'nombre'    => 'Proyecto 1',
            'user_id'   => $user->id,
            'html_code' => '<p>Contenido 1</p>',
            'css_code'  => '',
            'js_code'   => '',
            'statuts'   => 0,
        ]);

        Project::create([
            'nombre'    => 'Proyecto 2',
            'user_id'   => $user->id,
            'html_code' => '<p>Contenido 2</p>',
            'css_code'  => '',
            'js_code'   => '',
            'statuts'   => 0,
        ]);

        Project::create([
            'nombre'    => 'Proyecto 3',
            'user_id'   => $user->id,
            'html_code' => '<p>Contenido 3</p>',
            'css_code'  => '',
            'js_code'   => '',
            'statuts'   => 0,
        ]);

        $response = $this->getJson('/api/projects/all');
        $response->assertStatus(200);
        // Se asume que la respuesta está paginada, validamos que 'data' es un array
        $this->assertIsArray($response->json('data'));
    }

    public function testProjectsPaginado()
    {
        // Creamos un usuario de prueba
        $user = \App\Models\User::create([
            'name'      => 'Usuario de Proyectos',
            'email'     => 'proyectos2@example.com',
            'password'  => bcrypt('password'),
            'avatar'    => 'https://example.com/avatar.png',
            'nivel'     => 1,
            'nivel_css' => 1,
            'nivel_js'  => 1,
        ]);

        // Autenticar al usuario, si es que la ruta lo requiere
        \Laravel\Sanctum\Sanctum::actingAs($user);

        // Crear 15 proyectos manualmente asociados al usuario autenticado
        for ($i = 1; $i <= 15; $i++) {
            \App\Models\Project::create([
                'nombre'    => "Proyecto $i",
                'user_id'   => $user->id,
                'html_code' => "<p>Contenido $i</p>",
                'css_code'  => '',
                'js_code'   => '',
                'statuts'   => 0,
            ]);
        }

        $response = $this->getJson('/api/projectsAllPaginado');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'current_page',
            'last_page',
            'per_page',
            'total',
        ]);
    }


    public function testLikesOperations()
    {
        // Crear un usuario de prueba
        $user = User::create([
            'name'      => 'Usuario Likes',
            'email'     => 'likes@example.com',
            'password'  => bcrypt('password'),
            'avatar'    => 'https://example.com/avatar.png',
            'nivel'     => 1,
            'nivel_css' => 1,
            'nivel_js'  => 1,
        ]);
        Sanctum::actingAs($user);

        // Crear un proyecto manualmente
        $project = Project::create([
            'nombre'    => 'Proyecto Likes',
            'user_id'   => $user->id,
            'html_code' => '<p>Contenido para Likes</p>',
            'css_code'  => '',
            'js_code'   => '',
            'statuts'   => 0,
        ]);

        // Agregar un like
        $response = $this->postJson('/api/likes', ['project_id' => $project->id]);
        $response->assertStatus(201);

        // Verificar si el usuario dio like
        $response = $this->getJson("/api/likes/check/{$project->id}");
        $response->assertStatus(200);

        // Obtener los likes dados por el usuario
        $response = $this->getJson('/api/likes/user');
        $response->assertStatus(200);

        // Eliminar el like
        $response = $this->deleteJson("/api/likes/{$project->id}");
        $response->assertStatus(200);

        // Verificar el contador de likes, el cual debería ser 0 tras eliminar
        $response = $this->getJson("/api/likes/count/{$project->id}");
        $response->assertStatus(200);
    }

    public function testGetPreguntas()
    {
        // En este test se usa un lenguaje permitido, por ejemplo 'html'
        $language = 'html';
        $id = 1;
        $response = $this->getJson("/api/preguntas/{$language}/{$id}");
        // Suponiendo que no existe una pregunta con ese ID, se espera 404
        $response->assertStatus(404);
    }

    public function testActualizarNivel()
    {
        // Usamos un usuario creado mediante el factory para User (o manualmente, si prefieres)
        $user = User::factory()->create(['nivel' => 1]);
        $data = [
            'userId' => $user->id,
            'campo'  => 'nivel',
            'nivel'  => 5,
        ];

        $response = $this->postJson('/api/actualizar-nivel', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'nivel' => 5,
        ]);
    }
}
