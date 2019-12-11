import './bootstrap';
import Vue from 'vue';
//import Vuetify from 'vuetify';

//Info file routes.js
import Routes from '@/js/routes.js';
//Info file app.js
//import App from '@/js/views/App';
import Notificacion from '@/js/components/Notificacion';
//Vue.use(Vuetify);

const app = new Vue({
    el: '#app',
    router: Routes,
    data: {
        menu: 1,
        notificacion : []
    },
    components:{
        'notificacion' : Notificacion
    },
    methods: {
        loginRol: function(){
            axios.get('/api/user/rol')
            .then(function (response){
                var respuesta = response.data;
                var datalogin = respuesta.data;
                window.localStorage.setItem('lbUser',JSON.stringify(datalogin));
                console.log(JSON.stringify(datalogin));
            })
            .catch(function(error){
                console.log(error);
            });
        },
        loadNotificacion: function(){
            let me = this;
            axios.get('/api/notificacion/get')
            .then(function (response){
                console.log(response.data.data);
                me.notificacion = (response.data.data);

            })
            .catch(function(error){
                console.log(error);
            });
        }
    },
    mounted: function(){
        this.loginRol();

    },
    created: function(){
        let me = this;
        me.loadNotificacion();
        var userid = document.getElementById('usrid').value;

        Echo.private('App.User.' + userid)
        .notification(function(notification){
            me.notificacion = notification;
            console.log(notification);
            console.log(me.notificacion);
        });

    }
});

export default app;
