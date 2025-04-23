// import express from "express";
// import cors from "cors";
// import { GoogleGenerativeAI } from "@google/generative-ai";

// const app = express();
// const PORT = 5000;

// app.use(cors());
// app.use(express.json());

// const genAI = new GoogleGenerativeAI("AIzaSyBvVgFpTT270F8-TEZIy4zeWYnGr8c-PR0");
// const model = genAI.getGenerativeModel({ model: "gemini-pro" });

// app.post("/pregunta", async (req, res) => {
//     try {
//         const { pregunta } = req.body;
//         const { html } = req.body;
//         const { css } = req.body;
//         const { js } = req.body;
//         let preguntaIA = 'Ets un programador frontend expert, respon a la següent pregunta tenint en compte que la persona a la qual li parles és un desenvolupador novençà, '+pregunta;
//         if(html!=""){
//             preguntaIA += ' aquest es el meu html '+html;
//         }
//         if(css!=""){
//             preguntaIA += ' aquest es el meu css '+css;
//         }
//         if(js!=""){
//             preguntaIA += ' aquest es el meu js '+js;
//         }
//         console.log("Pregunta rebuda:", preguntaIA);
//         preguntaIA += ' Respon amb les mínimes paraules possibles encara que si fa falta pots allargar-te alguna cosa més però sense passar-te abastant tot el que està preguntant'
//         if (!preguntaIA) {
//             return res.status(400).send("La pregunta es requerida");
//         }

//         const result = await model.generateContent(preguntaIA);
//         const response = await result.response;
//         const text = response.text();

//         console.log('Respuesta:', text);

//         res.send(text);
//     } catch (error) {
//         console.error('Error:', error);
//         res.status(500).send("Error al procesar la solicitud: " + error.message);
//     }
// });

// app.listen(PORT, () => console.log("Servidor corriendo en http://localhost:"+PORT));


import express from 'express';
import http from 'http';
import { Server } from 'socket.io';
import cors from 'cors';

const app = express();
app.use(cors());

const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: '*', // En producción, limitar a tu dominio
        methods: ['GET', 'POST']
    }
});

// Almacenamiento temporal de las rooms
const rooms = new Map();
const projectRooms = new Map(); // Mapeo de proyectos a rooms

app.get('/', (req, res) => {
    res.send('Socket.IO Server para FrontUp Collaboration');
});

io.on('connection', (socket) => {
    console.log(`Usuario conectado: ${socket.id}`);

    // Crear una nueva room para colaboración
    socket.on('create-room', ({ roomId, projectId, initialData }) => {
        console.log(`Creando room: ${roomId} para proyecto: ${projectId}`);

        // Guardar la información de la room
        rooms.set(roomId, {
            projectId,
            html: initialData.html || '',
            css: initialData.css || '',
            js: initialData.js || '',
            users: [socket.id]
        });

        // Mapear el proyecto a esta room
        projectRooms.set(projectId, roomId);

        // Unir al socket a la room
        socket.join(roomId);

        // Notificar al creador
        socket.emit('room-created', { roomId });

        console.log(`Room ${roomId} creada con éxito`);
    });

    // Unirse a una room existente
    socket.on('join-room', ({ roomId, projectId }) => {
        const room = rooms.get(roomId);

        if (!room) {
            socket.emit('error', { message: 'La sala no existe' });
            return;
        }

        console.log(`Usuario ${socket.id} uniéndose a room: ${roomId}`);

        room.users.push(socket.id);
        socket.join(roomId);

        socket.emit('initial-state', {
            html: room.html,
            css: room.css,
            js: room.js
        });

        socket.to(roomId).emit('user-joined', { userId: socket.id });

        socket.emit('joinedRoom', { projectId }); // <-- esto es clave
    });


    // Manejar cambios en HTML
    socket.on('html-change', ({ code, roomId }) => {
        const room = rooms.get(roomId);
        if (!room) return;

        // Actualizar el estado en el servidor
        room.html = code;

        // Enviar a todos los demás en la room
        socket.to(roomId).emit('html-change', code);
    });

    // Manejar cambios en CSS
    socket.on('css-change', ({ code, roomId }) => {
        const room = rooms.get(roomId);
        if (!room) return;

        // Actualizar el estado en el servidor
        room.css = code;

        // Enviar a todos los demás en la room
        socket.to(roomId).emit('css-change', code);
    });

    // Manejar cambios en JS
    socket.on('js-change', ({ code, roomId }) => {
        const room = rooms.get(roomId);
        if (!room) return;

        // Actualizar el estado en el servidor
        room.js = code;

        // Enviar a todos los demás en la room
        socket.to(roomId).emit('js-change', code);
    });

    // Verificar si un código de room existe
    socket.on('check-room', ({ roomId }, callback) => {
        const roomExists = rooms.has(roomId);

        if (callback && typeof callback === 'function') {
            callback({
                exists: roomExists,
                projectId: roomExists ? rooms.get(roomId).projectId : null
            });
        } else {
            socket.emit('room-check-result', {
                roomId,
                exists: roomExists,
                projectId: roomExists ? rooms.get(roomId).projectId : null
            });
        }
    });

    // Manejar desconexión
    socket.on('disconnect', () => {
        console.log(`Usuario desconectado: ${socket.id}`);

        // Eliminar usuario de las rooms a las que pertenecía
        rooms.forEach((room, roomId) => {
            const userIndex = room.users.indexOf(socket.id);
            if (userIndex !== -1) {
                room.users.splice(userIndex, 1);

                // Si no quedan usuarios, eliminar la room
                if (room.users.length === 0) {
                    rooms.delete(roomId);
                    // También eliminar del mapeo de proyectos
                    if (projectRooms.get(room.projectId) === roomId) {
                        projectRooms.delete(room.projectId);
                    }
                    console.log(`Room ${roomId} eliminada por inactividad`);
                } else {
                    // Notificar a los demás usuarios que alguien se desconectó
                    io.to(roomId).emit('user-left', { userId: socket.id });
                }
            }
        });
    });
});

// Iniciar el servidor
const PORT = 5000;
server.listen(PORT, () => console.log("Servidor corriendo en http://localhost:" + PORT));