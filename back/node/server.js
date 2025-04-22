import express from "express";
import cors from "cors";
import { GoogleGenerativeAI } from "@google/generative-ai";
import { createServer } from "http";
import { Server } from "socket.io";

const app = express();
const PORT = 5000;
const httpServer = createServer(app);

// Configurar Socket.IO con CORS habilitado
const io = new Server(httpServer, {
  cors: {
    origin: "*", // En producción, especificar el origen exacto
    methods: ["GET", "POST"],
    credentials: true
  }
});

app.use(cors());
app.use(express.json());

const genAI = new GoogleGenerativeAI("AIzaSyBvVgFpTT270F8-TEZIy4zeWYnGr8c-PR0");
const model = genAI.getGenerativeModel({ model: "gemini-pro" });

// Ruta existente para la IA
app.post("/pregunta", async (req, res) => {
    try {
        const { pregunta } = req.body;
        const { html } = req.body;
        const { css } = req.body;
        const { js } = req.body;
        let preguntaIA = 'Ets un programador frontend expert, respon a la següent pregunta tenint en compte que la persona a la qual li parles és un desenvolupador novençà, '+pregunta;
        if(html!=""){
            preguntaIA += ' aquest es el meu html '+html;
        }
        if(css!=""){
            preguntaIA += ' aquest es el meu css '+css;
        }
        if(js!=""){
            preguntaIA += ' aquest es el meu js '+js;
        }
        console.log("Pregunta rebuda:", preguntaIA);
        preguntaIA += ' Respon amb les mínimes paraules possibles encara que si fa falta pots allargar-te alguna cosa més però sense passar-te abastant tot el que està preguntant'
        if (!preguntaIA) {
            return res.status(400).send("La pregunta es requerida");
        }
        
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

// Almacenar información de los proyectos y usuarios conectados
const activeProjects = {};
const connectedUsers = {};

// Socket.IO para la edición colaborativa
io.on("connection", (socket) => {
    // Recuperar información del usuario y proyecto
    const userId = socket.handshake.query.userId;
    const projectId = socket.handshake.query.projectId;
    const userName = socket.handshake.query.userName || "Usuario anónimo";
    
    console.log(`Usuario conectado: ${userName} (${userId}) al proyecto ${projectId}`);
    
    // Inicializar estructuras de datos para el seguimiento
    if (!activeProjects[projectId]) {
        activeProjects[projectId] = {
            users: {},
            html: "",
            css: "",
            js: "",
            activeEditors: {}
        };
    }
    
    // Registrar al usuario en el proyecto
    activeProjects[projectId].users[userId] = {
        id: userId,
        name: userName,
        socketId: socket.id
    };
    
    // Guardar referencia del usuario conectado
    connectedUsers[socket.id] = {
        userId,
        projectId,
        userName
    };
    
    // Emitir actualización de usuarios a todos los clientes en el proyecto
    io.to(projectId).emit("users:update", Object.values(activeProjects[projectId].users));
    
    // Unir al socket a la sala del proyecto
    socket.join(projectId);
    
    // Enviar los datos actuales del proyecto al usuario que acaba de conectarse
    socket.emit("project:init", {
        html: activeProjects[projectId].html,
        css: activeProjects[projectId].css,
        js: activeProjects[projectId].js,
        activeEditors: activeProjects[projectId].activeEditors
    });
    
    // Manejar cambios en el código HTML
    socket.on("code:html:change", (data) => {
        activeProjects[projectId].html = data.content;
        socket.to(projectId).emit("code:html:change", data);
    });
    
    // Manejar cambios en el código CSS
    socket.on("code:css:change", (data) => {
        activeProjects[projectId].css = data.content;
        socket.to(projectId).emit("code:css:change", data);
    });
    
    // Manejar cambios en el código JavaScript
    socket.on("code:js:change", (data) => {
        activeProjects[projectId].js = data.content;
        socket.to(projectId).emit("code:js:change", data);
    });
    
    // Registrar cuando un usuario comienza a editar
    socket.on("editor:focus", (data) => {
        activeProjects[projectId].activeEditors[data.editor] = data.user;
        socket.to(projectId).emit("editor:focus", data);
    });
    
    // Registrar cuando un usuario deja de editar
    socket.on("editor:blur", (data) => {
        if (activeProjects[projectId].activeEditors[data.editor]?.id === data.userId) {
            delete activeProjects[projectId].activeEditors[data.editor];
            socket.to(projectId).emit("editor:blur", data);
        }
    });
    
    // Manejar actualizaciones de metadatos del proyecto
    socket.on("project:update", (data) => {
        socket.to(projectId).emit("project:update", data);
    });
    
    // Manejar desconexiones
    socket.on("disconnect", () => {
        const userData = connectedUsers[socket.id];
        if (userData) {
            const { userId, projectId } = userData;
            console.log(`Usuario desconectado: ${userData.userName} (${userId}) del proyecto ${projectId}`);
            
            // Eliminar al usuario del proyecto
            if (activeProjects[projectId] && activeProjects[projectId].users[userId]) {
                delete activeProjects[projectId].users[userId];
                
                // Eliminar editores activos de este usuario
                Object.keys(activeProjects[projectId].activeEditors).forEach(editorKey => {
                    if (activeProjects[projectId].activeEditors[editorKey]?.id === userId) {
                        delete activeProjects[projectId].activeEditors[editorKey];
                    }
                });
                
                io.to(projectId).emit("users:update", Object.values(activeProjects[projectId].users));
                
                if (Object.keys(activeProjects[projectId].users).length === 0) {
                    delete activeProjects[projectId];
                }
            }
            
            delete connectedUsers[socket.id];
        }
    });
});

httpServer.listen(PORT, () => console.log(`Servidor corriendo en http://localhost:${PORT}`));