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

/**
 * Ruta existente para responder preguntas generales
 */
app.post("/pregunta", async (req, res) => {
  try {
    const { pregunta, html, css, js } = req.body;
    let prompt = 
      "Ets un programador frontend expert, respon a la següent pregunta tenint en compte que la persona a la qual li parles és un desenvolupador novençà: "
      + pregunta;
    if (html) prompt += `\nContexto HTML:\n${html}`;
    if (css)  prompt += `\nContexto CSS:\n${css}`;
    if (js)   prompt += `\nContexto JS:\n${js}`;
    prompt += "\nRespon amb les mínimes paraules necessàries.";

    const result = await model.generateContent(prompt);
    const response = await result.response;
    const text = await response.text();

    console.log("Respuesta /pregunta:", text);
    res.send(text);

  } catch (error) {
    console.error("Error en /pregunta:", error);
    res.status(500).send("Error al procesar la solicitud: " + error.message);
  }
});

/**
 * Nueva ruta para generar un snippet de código basado en contexto
 */
app.post("/generar-codigo", async (req, res) => {
  try {
    const { pregunta, html, css, js } = req.body;
    let prompt = 
      "Ets un programador frontend expert. " 
      + pregunta
      + "\n\n";
    if (html) prompt += `Contexto HTML:\n${html}\n\n`;
    if (css)  prompt += `Contexto CSS:\n${css}\n\n`;
    if (js)   prompt += `Contexto JS:\n${js}\n\n`;
    prompt += "Respon amb un snippet de codi clar i funcional.";

    const result = await model.generateContent(prompt);
    const response = await result.response;
    const snippet = await response.text();

    console.log("Snippet generado:", snippet);
    res.send(snippet);

  } catch (error) {
    console.error("Error en /generar-codigo:", error);
    res.status(500).send("No s'ha pogut generar el codi: " + error.message);
  }
});

// Almacenar información de los proyectos y usuarios conectados
const activeProjects = {};
const connectedUsers = {};

// Socket.IO para la edición colaborativa
io.on("connection", (socket) => {
  const userId    = socket.handshake.query.userId;
  const projectId = socket.handshake.query.projectId;
  const userName  = socket.handshake.query.userName || "Usuario anónimo";

  console.log(`Usuario conectado: ${userName} (${userId}) al proyecto ${projectId}`);

  if (!activeProjects[projectId]) {
    activeProjects[projectId] = {
      users: {},
      html: "",
      css: "",
      js: "",
      activeEditors: {}
    };
  }

  activeProjects[projectId].users[userId] = {
    id: userId,
    name: userName,
    socketId: socket.id
  };
  connectedUsers[socket.id] = { userId, projectId, userName };

  io.to(projectId).emit("users:update", Object.values(activeProjects[projectId].users));
  socket.join(projectId);

  socket.emit("project:init", {
    html: activeProjects[projectId].html,
    css: activeProjects[projectId].css,
    js: activeProjects[projectId].js,
    activeEditors: activeProjects[projectId].activeEditors
  });

  socket.on("code:html:change", data => {
    activeProjects[projectId].html = data.content;
    socket.to(projectId).emit("code:html:change", data);
  });
  socket.on("code:css:change", data => {
    activeProjects[projectId].css = data.content;
    socket.to(projectId).emit("code:css:change", data);
  });
  socket.on("code:js:change", data => {
    activeProjects[projectId].js = data.content;
    socket.to(projectId).emit("code:js:change", data);
  });

  socket.on("editor:focus", data => {
    activeProjects[projectId].activeEditors[data.editor] = data.user;
    socket.to(projectId).emit("editor:focus", data);
  });
  socket.on("editor:blur", data => {
    if (activeProjects[projectId].activeEditors[data.editor]?.id === data.userId) {
      delete activeProjects[projectId].activeEditors[data.editor];
      socket.to(projectId).emit("editor:blur", data);
    }
  });

  socket.on("project:update", data => {
    socket.to(projectId).emit("project:update", data);
  });

  socket.on("disconnect", () => {
    const userData = connectedUsers[socket.id];
    if (!userData) return;

    const { userId, projectId } = userData;
    delete connectedUsers[socket.id];

    delete activeProjects[projectId].users[userId];
    Object.keys(activeProjects[projectId].activeEditors).forEach(key => {
      if (activeProjects[projectId].activeEditors[key]?.id === userId) {
        delete activeProjects[projectId].activeEditors[key];
      }
    });
    io.to(projectId).emit("users:update", Object.values(activeProjects[projectId].users));

    if (Object.keys(activeProjects[projectId].users).length === 0) {
      delete activeProjects[projectId];
    }

    console.log(`Usuario desconectado: ${userName} (${userId}) del proyecto ${projectId}`);
  });
});

httpServer.listen(PORT, () => 
  console.log(`Servidor corriendo en http://localhost:${PORT}`)
);
