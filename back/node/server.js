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

app.listen(PORT, () => console.log("Servidor corriendo en http://localhost:"+PORT));