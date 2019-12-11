import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/components/Home';
import Categoria from '@/js/components/Categoria';
import Articulo from '@/js/components/Articulo';
import Cliente from '@/js/components/Cliente';
import Proveedor from '@/js/components/Proveedor';
import Rol from '@/js/components/Rol';
import User from '@/js/components/User';
import Ingreso from '@/js/components/Ingreso';
import Venta from '@/js/components/Venta';
import DashBoard from '@/js/components/DashBoard';
import ConsultaIngreso from '@/js/components/ConsultaIngreso';
import ConsultaVenta from '@/js/components/ConsultaVenta';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: DashBoard,
        meta:{
            requiresAuth: true,
            almaceneroAuth: true,
            vendedorAuth: true
        }
    },
    {
        path: '/categoria',
        name: 'categoria',
        component: Categoria,
        meta : {
            requiresAuth: true,
            almaceneroAuth: true
        }
    },
    {
        path: '/articulo',
        name: 'articulo',
        component: Articulo,
        meta : {
            requiresAuth: true,
            almaceneroAuth: true
        }
    },
    {
        path: '/cliente',
        name: 'cliente',
        component: Cliente,
        meta : {
            requiresAuth: true,
            vendedorAuth: true
        }
    },
    {
        path: '/proveedor',
        name: 'proveedor',
        component: Proveedor,
        meta : {
            requiresAuth: true,
            almaceneroAuth: true
        }
    },
    {
        path: '/rol',
        name: 'rol',
        component: Rol,
        meta : {
            requiresAuth: true,
            adminAuth: true
        }
    },
    {
        path: '/user',
        name: 'user',
        component: User,
        meta : {
            requiresAuth: true,
            adminAuth: true
        }
    },
    {
        path: '/ingreso',
        name: 'ingreso',
        component: Ingreso,
        meta : {
            requiresAuth: true,
            almaceneroAuth: true
        }
    },
    {
        path: '/venta',
        name: 'venta',
        component: Venta,
        meta : {
            requiresAuth: true,
            vendedorAuth: true
        }
    },
    {
        path: '/consulta',
        redirect: { name : 'dashboard'}
    },
    {
        path: '/consulta/ingreso',
        name: 'consultaIngreso',
        component: ConsultaIngreso,
        meta : {
            requiresAuth: true,
            almaceneroAuth: true
        }
    },
    {
        path: '/consulta/venta',
        name: 'consultaVenta',
        component: ConsultaVenta,
        meta : {
            requiresAuth: true,
            vendedorAuth: true
        }
    }
];

const router = new VueRouter({
    mode: 'history',
    base: '/',
    routes: routes
});


router.beforeEach((to, from, next) => {
    if(to.meta.requiresAuth) {
        const authUser = JSON.parse(window.localStorage.getItem('lbUser'))
        if(!authUser || !authUser.token) {
            next({name:'login'});
            console.log('login');
        }
        else if(to.meta.adminAuth) {
            const authUser = JSON.parse(window.localStorage.getItem('lbUser'))
            if(authUser.rol === 'Administrador') {
                next()
            }else {
                next('/');
                console.log('no admin|' + authUser);
            }
        } else if(to.meta.vendedorAuth) {
            const authUser = JSON.parse(window.localStorage.getItem('lbUser'))
            if(authUser.rol === 'Vendedor' || authUser.rol === 'Administrador') {
                next()
            }else {
                next('/');
                console.log('no venta');
            }
        } else if(to.meta.almaceneroAuth) {
            const authUser = JSON.parse(window.localStorage.getItem('lbUser'))
            if(authUser.rol === 'Almacenero' || authUser.rol === 'Administrador') {
                next()
            }else {
                next('/');
                console.log('no almacen');
            }
        }

    }else {
        next()
    }
});

export default router;
