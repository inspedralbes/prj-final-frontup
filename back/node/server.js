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

        // 1) Context general en català
        let prompt =
            "Ets un programador frontend expert. Explica de forma clara a un desenvolupador novençà.\n\n" +
            "Petició:\n" + pregunta + "\n\n";

        // 2) Afegeix el codi existent
        if (html) prompt += "Aquest és el meu HTML:\n" + html + "\n\n";
        if (css) prompt += "Aquest és el meu CSS:\n" + css + "\n\n";
        if (js) prompt += "Aquest és el meu JS:\n" + js + "\n\n";

        // 3) Instruccions de format en català
        prompt += (
            "INSTRUCCIONS DE FORMAT:\n" +
            "- Si la petició és per **millorar** o donar suggeriments, respon **només en text**, sense blocs de codi.\n" +
            "- Si la petició és de **implementació**, primer fes una breu explicació en text i després mostra el codi llest per copiar en un bloc entre triple backticks.\n"
        );

        // 4) Crida al model
        const result = await model.generateContent(prompt);
        const response = await result.response;
        const text = await response.text();

        res.send(text);
    } catch (error) {
        console.error(error);
        res.status(500).send("Error: " + error.message);
    }
});


// Crear la funcionalitat de rooms
const rooms = new Map();
const projectRooms = new Map();

app.get('/', (req, res) => {
    res.send('Socket.IO Server para FrontUp Collaboration');
});

app.get('/debug-rooms', (req, res) => {
    const data = {};
    rooms.forEach((value, key) => {
        data[key] = {
            projectId: value.projectId,
            users: value.users
        };
    });
    res.json(data);
});


io.on('connection', (socket) => {
    console.log(`Usuario conectado: ${socket.id}`);

    // Crear una nova room
    socket.on('create-room', ({ roomId, projectId, initialData, userName, avatar }) => {
        console.log(`Creando room: ${roomId} para proyecto: ${projectId}`);
        rooms.set(roomId, {
            projectId,
            html: initialData.html || '',
            css: initialData.css || '',
            js: initialData.js || '',
            users: [{ id: socket.id, name: userName, avatar }]
        });
        projectRooms.set(projectId, roomId);

        socket.join(roomId);
        socket.emit('room-created', { roomId });
        updateActiveUsers(roomId);
    });

    //Unir-se a una room
    socket.on('join-room', ({ roomId, projectId, userName, avatar }) => {
        const room = rooms.get(roomId);
        if (!room) {
            socket.emit('error', { message: 'La sala no existeix' });
            return;
        }

        room.users = room.users.filter(u => u.id !== socket.id && u.name !== userName);

        room.users.push({ id: socket.id, name: userName, avatar });
        socket.join(roomId);

        socket.emit('initial-state', {
            html: room.html,
            css: room.css,
            js: room.js
        });

        socket.emit('joinedRoom', { projectId });
        updateActiveUsers(roomId);
    });


    function updateActiveUsers(roomId) {
        const room = rooms.get(roomId);
        if (!room) return;

        const usersInfo = room.users.map(user => ({
            name: user.name,
            avatar: user.avatar || ''
        }));

        io.to(roomId).emit('active-users', usersInfo);
    }

    //Desconecta-se d'una room
    socket.on('disconnect', () => {
        console.log(`Usuari desconnectat: ${socket.id}`);
        for (const [roomId, room] of rooms.entries()) {
            const index = room.users.indexOf(socket.id);
            if (index !== -1) {
                room.users.splice(index, 1);
                console.log(`Usuari ${socket.id} eliminat de la room ${roomId}`);
                console.log(`Queden ${room.users.length} usuaris`);

                updateActiveUsers(roomId);

                if (room.users.length === 0) {
                    rooms.delete(roomId);
                    console.log(`Room ${roomId} eliminada per estar buida`);
                }
                break;
            }
        }
    });


    // Canvis en el editor de codi del HTML
    socket.on('html-change', ({ code, roomId }) => {
        const room = rooms.get(roomId);
        if (!room) return;
        room.html = code;
        socket.to(roomId).emit('html-change', code);
    });

    // Canvis en el editor de codi del CSS
    socket.on('css-change', ({ code, roomId }) => {
        const room = rooms.get(roomId);
        if (!room) return;
        room.css = code;
        socket.to(roomId).emit('css-change', code);
    });

    // Canvis en el editor de codi del JS
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

});


const PORT = 5000;
server.listen(PORT, () => console.log("Servidor corriendo en http://localhost:" + PORT));