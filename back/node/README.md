# Servicio Node.js - FrontUp

## Descripción
Servicio de Node.js para FrontUp que maneja la comunicación en tiempo real y tareas asíncronas del proyecto.

## Estructura del Proyecto

```
node/
├── src/
│   ├── config/           # Configuraciones
│   ├── controllers/      # Controladores de WebSocket
│   ├── services/         # Servicios de la aplicación
│   └── utils/           # Utilidades
├── tests/               # Tests unitarios
└── websocket/          # Implementación de WebSocket
```

## Requisitos Previos
- Node.js 16.x o superior
- npm o yarn
- Redis (para gestión de sesiones)
- Docker (opcional)

## Configuración del Entorno

1. **Instalación de dependencias**:
```bash
npm install
```

2. **Variables de entorno**:
```bash
cp .env.example .env
```

Configurar las siguientes variables:
```env
PORT=3001
REDIS_URL=redis://localhost:6379
JWT_SECRET=your_jwt_secret
```

## Características Principales

### WebSocket
- Conexión en tiempo real para actualizaciones
- Manejo de salas para proyectos
- Autenticación mediante JWT

### Tareas en Segundo Plano
- Procesamiento de colas
- Notificaciones en tiempo real
- Sincronización de datos

## Desarrollo

### Comandos Disponibles

```bash
# Iniciar en desarrollo
npm run dev

# Construir para producción
npm run build

# Iniciar en producción
npm start

# Ejecutar tests
npm test
```

### WebSocket Events

1. **Conexión**:
```javascript
socket.on('connect', () => {
  console.log('Connected to WebSocket server');
});
```

2. **Unirse a sala**:
```javascript
socket.emit('join-room', { projectId: 'project-123' });
```

3. **Actualización de proyecto**:
```javascript
socket.on('project-update', (data) => {
  console.log('Project updated:', data);
});
```

## Despliegue

1. **Construcción**:
```bash
npm run build
```

2. **Con Docker**:
```bash
docker build -t frontup-node .
docker run -p 3001:3001 frontup-node
```

## Monitoreo y Mantenimiento

### Logs
- Winston para logging
- Rotación de logs configurada
- Niveles de log: error, warn, info, debug

### Métricas
- Prometheus para métricas
- Grafana para visualización
- Healthchecks configurados

## Buenas Prácticas

1. **Código**:
   - Usar TypeScript para tipo seguro
   - Seguir principios SOLID
   - Documentar funciones y métodos

2. **Testing**:
   - Tests unitarios con Jest
   - Tests de integración
   - Cobertura de código > 80%

3. **Seguridad**:
   - Validación de JWT
   - Rate limiting
   - Sanitización de datos

## Solución de Problemas Comunes

1. **Error de conexión WebSocket**:
   - Verificar configuración de CORS
   - Validar token JWT
   - Comprobar puertos

2. **Problemas de memoria**:
   - Monitorear uso de memoria
   - Implementar limpieza de conexiones
   - Usar PM2 para gestión de procesos

3. **Errores de Redis**:
   - Verificar conexión Redis
   - Comprobar memoria disponible
   - Limpiar caché si necesario

## Recursos Adicionales
- [Socket.IO Docs](https://socket.io/docs/v4/)
- [Node.js Best Practices](https://github.com/goldbergyoni/nodebestpractices)
- [PM2 Documentation](https://pm2.keymetrics.io/docs/usage/quick-start/) 