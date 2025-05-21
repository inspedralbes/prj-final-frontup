# Portal de Jocs de Matemàtiques

## Descripció del Projecte
Crear una aplicació tipus CodePen on es puguin desenvolupar projectes amb HTML, CSS i JavaScript, permetent editar-los col·laborativament en temps real i publicar-los. A més, inclourà una secció dedicada a l'aprenentatge de programació.

---

## Tecnologies Utilitzades

### Frontend:
- **Vue.js**: Framework per construir interfícies d'usuari interactives.
- **Nuxt.js**: Framework per al desenvolupament d'aplicacions Vue.js amb SSR i SPA.
- **Pinia**: Gestió d'estat a Vue.js.
- **TypeScript**: Llenguatge de programació.
- **CSS**: CSS és un llenguatge d'estil que defineix l'aparença visual de pàgines web en HTML.

### Backend:
- **Laravel**: Framework PHP per construir APIs robustes.
- **Node.js** i **Socket.io**: Gestió de comunicació en temps real entre clients.
- **MySQL**: Base de dades relacional per emmagatzemar les dades del projecte.

### Contenidor i desplegament:
- **Docker**: Contenidorització dels serveis per a un desplegament eficient.
- **GitHub**: Control de versions i col·laboració.
- **GitHub Actions**: Integració i desplegament continu.

## Estructura del Projecte

```
/back
  /laravel          # API REST i lògica principal
  /node             # Servidor WebSocket i tasques en temps real
/front              # Aplicació frontend amb Nuxt.js
  /components       # Components Vue reutilitzables
  /pages            # Pàgines de l'aplicació
  /stores           # Gestió d'estat amb Pinia
```

---

## Participants del Projecte

| Nom              |
|------------------|
| [Gerard Arias]   |
| [Ayoub Boudhafri]|
| [Marc Ciurans]   |
| [Gabriel Montaño]|
| [Eduard Renau]   |

---

## Mentor de l'Equip

| Nom           | Contacte          |
|---------------|-------------------|
| [Ermengol]    | [ebota@inspedralbes.cat]  |

---

## Enllaços del Projecte

- **Enllaç a l'aplicació web:** [https://app.ejemplo.com](https://app.ejemplo.com)
- **Repositori a GitHub:** [https://github.com/inspedralbes/prj-final-frontup](https://github.com/inspedralbes/prj-final-frontup)
- **Disseny inicial Penpot:** [https://design.penpot.app/#/view?file-id=96c4bd8e-df43-800f-8005-9c1fc7430a25&page-id=96c4bd8e-df43-800f-8005-9c1fc7430a26&section=interactions&index=0&share-id=790b4dba-cade-8121-8005-9c28a8bb9552](Enllaç pempot)

## Configuració i Desplegament

### Requisits Previs
- Docker i Docker Compose
- Node.js 16.x o superior
- PHP 8.1 o superior
- Composer

### Passos per Iniciar el Projecte

1. **Clonar el repositori**:
```bash
git clone https://github.com/inspedralbes/prj-final-frontup.git
cd prj-final-frontup
```

2. **Configurar variables d'entorn**:
```bash
# Frontend
cp front/.env.example front/.env

# Backend Laravel
cp back/laravel/.env.example back/laravel/.env

# Backend Node
cp back/node/.env.example back/node/.env
```

3. **Iniciar amb Docker**:
```bash
docker-compose up 
```

4. **Accedir a l'aplicació**:
- Frontend: http://localhost:3000
- Laravel: http://localhost:8000
- Node: http://localhost:5000
