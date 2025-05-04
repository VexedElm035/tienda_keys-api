//id = app | siempre debe de ir para vuejs
//necesitamos un script principal
//la impresion de texto de una variable se hace con {{texto}}
//v-on:click='increment' | evento onclick con directivas de vuejs

const {createApp} = Vue
const app1 = createApp({ //creacion de variaoble app1
    data(){ //configuracion
        return {
            title: 'hola',
            subtitle: 'probando',
            counter: 0, //variables reactivas, si las modifico, tambien el usuario ve el cambio
        }
    },
    methods: {
        increment(){ //funciones 
            this.counter++;
        }
    }
});
app1.mount('#app');

//creacion de proyecto vue= npm create vue@latest, se seleccionan router y pinia

//jueves diseno del frontend pagina home(principal), una de login, y registro, con html, css-tailwind en app.vue
//usar archivo layout