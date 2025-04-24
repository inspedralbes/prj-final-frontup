import express from "express";
import cors from "cors";
import { GoogleGenerativeAI } from "@google/generative-ai";
import http from 'http';
import { Server } from 'socket.io';

// Crear el servidor d'Express
const app = express();
const server = http.createServer(app);
const io = new Server(server, {
    cors: {
        origin: '*',
        methods: ['GET', 'POST']
    }
});

app.use(cors());
app.use(express.json());

// Configurar el model d'IA
const genAI = new GoogleGenerativeAI("AIzaSyBvVgFpTT270F8-TEZIy4zeWYnGr8c-PR0");
const model = genAI.getGenerativeModel({ model: "gemini-2.0-flash" });

// Rutes de l'API d'IA
app.post("/pregunta", async (req, res) => {
    try {
        const { pregunta, html, css, js } = req.body;
        let preguntaIA = 'Ets un programador frontend expert, respon a la següent pregunta tenint en compte que la persona a la qual li parles és un desenvolupador novençà, ' + pregunta;
        if (html) preguntaIA += ' aquest es el meu html ' + html;
        if (css) preguntaIA += ' aquest es el meu css ' + css;
        if (js) preguntaIA += ' aquest es el meu js ' + js;
        preguntaIA += ' Respon amb les mínimes paraules possibles encara que si fa falta pots allargar-te alguna cosa més però sense passar-te abastant tot el que està preguntant';

        if (!preguntaIA) return res.status(400).send("La pregunta es requerida");

        const result = await model.generateContent(preguntaIA);
        const response = await result.response;
        const text = response.text();

        console.log('Respuesta:', text);
        res.send(text);
    } catch (error) {
        console.error('Error:', error);
        res.status(500).send("Error al procesar la solicitud: " + error.message);
    }
});

// Crear la funcionalitat de rooms
const rooms = new Map();
const projectRooms = new Map();

app.get('/', (req, res) => {
    res.send('Socket.IO Server para FrontUp Collaboration');
});

io.on('connection', (socket) => {
    console.log(`Usuario conectado: ${socket.id}`);

    // Crear una nova room
    socket.on('create-room', ({ roomId, projectId, initialData }) => {
        console.log(`Creando room: ${roomId} para proyecto: ${projectId}`);
        rooms.set(roomId, {
            projectId,
            html: initialData.html || '',
            css: initialData.css || '',
            js: initialData.js || '',
            users: [socket.id]
        });
        projectRooms.set(projectId, roomId);

        socket.join(roomId);
        socket.emit('room-created', { roomId });
        console.log(`Room ${roomId} creada amb èxit`);
    });

    // Unir-se a una room existent
    socket.on('join-room', ({ roomId, projectId }) => {
        const room = rooms.get(roomId);
        if (!room) {
            socket.emit('error', { message: 'La sala no existeix' });
            return;
        }
        room.users.push(socket.id);
        socket.join(roomId);

        socket.emit('initial-state', {
            html: room.html,
            css: room.css,
            js: room.js
        });

        socket.to(roomId).emit('user-joined', { userId: socket.id });
        socket.emit('joinedRoom', { projectId });
    });

    // Manejar cambios en HTML
    socket.on('html-change', ({ code, roomId }) => {
        const room = rooms.get(roomId);
        if (!room) return;
        room.html = code;
        socket.to(roomId).emit('html-change', code);
    });

    // Manejar cambios en CSS
    socket.on('css-change', ({ code, roomId }) => {
        const room = rooms.get(roomId);
        if (!room) return;
        room.css = code;
        socket.to(roomId).emit('css-change', code);
    });

    // Manejar cambios en JS
    socket.on('js-change', ({ code, roomId }) => {
        const room = rooms.get(roomId);
        if (!room) return;
        room.js = code;
        socket.to(roomId).emit('js-change', code);
    });

    // Verificar si un código de room existeix
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

    // Manejar desconexió
    socket.on('disconnect', () => {
        console.log(`Usuario desconectado: ${socket.id}`);
        rooms.forEach((room, roomId) => {
            const userIndex = room.users.indexOf(socket.id);
            if (userIndex !== -1) {
                room.users.splice(userIndex, 1);
                if (room.users.length === 0) {
                    rooms.delete(roomId);
                    if (projectRooms.get(room.projectId) === roomId) {
                        projectRooms.delete(room.projectId);
                    }
                    console.log(`Room ${roomId} eliminada por inactividad`);
                } else {
                    io.to(roomId).emit('user-left', { userId: socket.id });
                }
            }
        });
    });
});

const PORT = 5000;
server.listen(PORT, () => console.log("Servidor corriendo en http://localhost:" + PORT));
