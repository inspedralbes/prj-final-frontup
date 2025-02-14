import express from "express";
import cors from "cors";
import { GoogleGenerativeAI } from "@google/generative-ai";

const app = express();
const PORT = 5000;

app.use(cors());
app.use(express.json());

const genAI = new GoogleGenerativeAI("AIzaSyBvVgFpTT270F8-TEZIy4zeWYnGr8c-PR0");
const model = genAI.getGenerativeModel({ model: "gemini-pro" });

app.post("/pregunta", async (req, res) => {
    try {
        const { pregunta } = req.body;
        const { html } = req.body;
        const { css } = req.body;
        const { js } = req.body;
        let preguntaIA = 'Eres un programador frontend experto, responde a la siguiente pregunta teniendo en cuenta que la persona a la que le hablas es un desarrollador novato, '+pregunta;
        if(html!=""){
            preguntaIA += ' este es mi html '+html;
        }
        if(css!=""){
            preguntaIA += ' este es mi css '+css;
        }
        if(js!=""){
            preguntaIA += ' este es mi js '+js;
        }
        console.log("Pregunta recibida:", preguntaIA);
        preguntaIA += ' responde con las minimas palabras posibles aunque si hace falta puedes alargarte algo mas pero sin pasarte abarcando todo lo que te este preguntando'
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

app.listen(PORT, () => console.log("Servidor corriendo en http://api.frontup.cat/node:"+PORT));