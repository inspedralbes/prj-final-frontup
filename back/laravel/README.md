# Backend Laravel - FrontUp

## Descripción
API REST del proyecto FrontUp construida con Laravel 10, proporcionando endpoints seguros y escalables para la gestión de proyectos.

## Estructura del Proyecto

```
laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Controladores de la aplicación
│   │   └── Middleware/     # Middleware personalizado
│   │   
│   ├── Models/            # Modelos Eloquent
│   └── Services/          # Servicios de la aplicación
├── config/               # Configuraciones
├── database/
│   ├── migrations/       # Migraciones de base de datos
│   └── seeders/         # Seeders para datos de prueba
├── routes/
│   ├── api.php          # Rutas de la API
│   └── web.php          # Rutas web (si aplica)
└── tests/               # Tests unitarios y de integración
```

## Requisitos Previos
- PHP 8.1 o superior
- Composer
- MySQL/PostgreSQL
- Redis (opcional, para caché)
- Docker (opcional)

## Configuración del Entorno

1. **Instalación de dependencias**:
```bash
composer install
```

2. **Variables de entorno**:
```bash
cp .env.example .env
```

3. **Configurar base de datos**:
Editar `.env` con las credenciales de la base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=frontup
DB_USERNAME=root
DB_PASSWORD=
```

4. **Generar clave de aplicación**:
```bash
php artisan key:generate
```

5. **Migraciones y seeders**:
```bash
php artisan migrate
php artisan db:seed
```

## API Endpoints

### Autenticación
- `POST /api/auth/login` - Iniciar sesión
- `POST /api/auth/register` - Registrar usuario
- `POST /api/auth/logout` - Cerrar sesión
- `GET /api/auth/user` - Obtener usuario actual

### Proyectos
- `GET /api/projects` - Listar proyectos
- `POST /api/projects` - Crear proyecto
- `GET /api/projects/{id}` - Obtener proyecto
- `PUT /api/projects/{id}` - Actualizar proyecto
- `DELETE /api/projects/{id}` - Eliminar proyecto

### Usuarios
- `GET /api/users` - Listar usuarios
- `GET /api/users/{id}` - Obtener usuario
- `PUT /api/users/{id}` - Actualizar usuario

## Desarrollo

### Comandos Útiles

```bash
# Servidor de desarrollo
php artisan serve

# Crear controlador
php artisan make:controller NombreController

# Crear modelo con migración
php artisan make:model Nombre -m

# Ejecutar tests
php artisan test
```

### Buenas Prácticas

1. **Controladores**:
   - Usar Resource Controllers cuando sea posible
   - Implementar Form Requests para validación
   - Mantener controladores delgados

2. **Modelos**:
   - Definir relaciones claramente
   - Usar scopes para consultas comunes
   - Implementar factories para testing

3. **Seguridad**:
   - Validar todas las entradas
   - Usar políticas de autorización
   - Implementar rate limiting

## Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter TestName

# Crear nueva clase de test
php artisan make:test NombreTest
```

## Despliegue

1. **Optimización**:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **Con Docker**:
```bash
docker build -t frontup-backend .
docker run -p 8000:8000 frontup-backend
```

## Mantenimiento

### Tareas Programadas
```bash
# Listar tareas programadas
php artisan schedule:list

# Ejecutar scheduler
php artisan schedule:work
```

### Caché
```bash
# Limpiar caché
php artisan cache:clear

# Limpiar configuración
php artisan config:clear
```

## Solución de Problemas Comunes

1. **Error de permisos**:
```bash
chmod -R 777 storage bootstrap/cache
```

2. **Problemas de migración**:
```bash
php artisan migrate:fresh --seed
```

3. **Errores de composer**:
```bash
composer dump-autoload
```

## Recursos Adicionales
- [Documentación de Laravel](https://laravel.com/docs)
- [Laravel API Resources](https://laravel.com/docs/10.x/eloquent-resources)
- [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum)
