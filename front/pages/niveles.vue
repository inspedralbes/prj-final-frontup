<template>
  <div class="Selector">
    <h1>Niveles</h1>
    <div class="botones">
        <button class="HTML" @click="html_ejercicios">HTML</button>
        <button class="boton_CSS"  @click="css_ejercicios">CSS</button>
        <button class="JAVA_SCRIP"  @click="js_ejercicios">JS</button>
    </div>
    

  </div>
   
  </template>
  
  <script>
    export default {
  name: 'nivels',
  methods: {
    
    html_ejercicios() {
      this.$router.push('/nivel_html'); 
    },
    css_ejercicios(){
        this.$router.push('/nivel_css');
    },
    js_ejercicios(){
        this.$router.push('/nivel_js');
    },
    
  },
};

  </script>
  
  <style scoped>

h1 {
    color: white;
    background-color: gray;
    text-align: center;
    margin-bottom: 20px;
    border-radius: 50px;
}

.selector {
    height: 100vh;  
    display: flex;  
    flex-direction: column;  
    align-items: center;    
    justify-content: center; 
    background-color: rgb(161, 152, 152);
}


.botones {
    display: flex;  
    justify-content: center; 
    gap: 100px; 
}

button {
    border: solid 1px black;
    height: 500px;
    width: 35%;
    display: inline-block;
    font-size: 20px;
    border-radius: 25px;
    color: white;
    font-weight: bold;
    text-align: center;
    cursor: pointer;
}
button.ho


.HTML {
    background-image: url(data:image/jpeg;base64,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) ;color: black;
     
  }
.boton_CSS {
  background-image: url(/front/public/tuercas.png) ;color: black;
    }
.JAVA_SCRIP {
    background: yellow; color: black;
  }
    
     

  
  </style>