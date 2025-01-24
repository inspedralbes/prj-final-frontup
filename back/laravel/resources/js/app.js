import './bootstrap';

const cors = require('cors');
app.use(cors({
    origin: 'http://localhost:3000' // Permitir solo este origen
}));
