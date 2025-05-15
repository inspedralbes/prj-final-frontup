# Frontend - FrontUp

## Descripción
Frontend de FrontUp construido con Nuxt.js 3, utilizando Vue 3 y TypeScript para una experiencia de desarrollo moderna y tipada.

## Estructura de Directorios

```
frontend/
├── assets/           # Recursos estáticos (imágenes, estilos, etc.)
├── components/       # Componentes Vue reutilizables
├── composables/     # Composables de Vue 3
├── layouts/         # Layouts de la aplicación
├── pages/           # Páginas de la aplicación (enrutamiento automático)
├── plugins/         # Plugins de Nuxt
├── public/          # Archivos públicos
├── server/          # Middleware y API routes
└── stores/          # Estados globales con Pinia
```

## Requisitos Previos
- Node.js 16.x o superior
- npm o yarn
- Docker (opcional, para desarrollo con contenedores)

## Configuración del Entorno

1. **Instalación de dependencias**:
```bash
npm install
# o
yarn install
```

2. **Variables de entorno**:
```bash
cp .env.example .env
```
Editar `.env` con las variables necesarias:
- `NUXT_PUBLIC_API_BASE`: URL base de la API
- `NUXT_PUBLIC_WS_URL`: URL del servidor WebSocket

## Comandos Disponibles

```bash
# Desarrollo
npm run dev

# Construcción para producción
npm run build

# Preview de producción
npm run preview

# Análisis de código
npm run lint
```

## Desarrollo

### Componentes
Los componentes se organizan en `components/` siguiendo estas convenciones:
- Un componente por archivo
- Nombres en PascalCase
- Uso de TypeScript y Composition API
- Props y emits tipados

Ejemplo:
```vue
<script setup lang="ts">
interface Props {
  title: string
  items: string[]
}

const props = defineProps<Props>()
const emit = defineEmits<{
  (e: 'update', value: string): void
}>()
</script>
```

### Estado Global
Utilizamos Pinia para la gestión del estado. Los stores se encuentran en `stores/`:

```typescript
// stores/user.ts
export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as User | null,
  }),
  actions: {
    async login(credentials: Credentials) {
      // ...
    }
  }
})
```

### Estilos
- SASS para estilos
- Variables globales en `assets/styles/`
- Tailwind CSS para utilidades

### API y TypeScript
- Types en `types/`
- Composables para lógica reutilizable
- Fetch wrapper para llamadas API

## Buenas Prácticas

1. **Componentes**:
   - Mantener componentes pequeños y enfocados
   - Usar props y emits tipados
   - Documentar componentes complejos

2. **Estado**:
   - Centralizar estado en Pinia
   - Evitar estado local innecesario
   - Usar composables para lógica reutilizable

3. **Performance**:
   - Lazy loading de componentes grandes
   - Optimización de imágenes
   - Caching apropiado

## Despliegue

1. Construir la aplicación:
```bash
npm run build
```

2. Con Docker:
```bash
docker build -t frontup-frontend .
docker run -p 3000:3000 frontup-frontend
```

## Solución de Problemas Comunes

1. **Error de tipos en build**:
   - Verificar que todos los tipos estén correctamente definidos
   - Actualizar dependencias de TypeScript

2. **Problemas de CORS**:
   - Verificar configuración de proxy en `nuxt.config.ts`
   - Confirmar configuración de CORS en backend

3. **Problemas de caché**:
   - Limpiar caché del navegador
   - Ejecutar `npm clean-cache`

## Recursos Adicionales
- [Documentación de Nuxt 3](https://nuxt.com/docs)
- [Vue 3 Composition API](https://vuejs.org/guide/introduction.html)
- [Pinia](https://pinia.vuejs.org/)
