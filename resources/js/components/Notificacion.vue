<template>
<li class="nav-item d-md-down-none">
    <a class="nav-link" href="#" data-toggle="dropdown">
        <i class="icon-bell"></i>
        <span class="badge badge-pill badge-danger">{{ dataarray ? 1 : 0  }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-header text-center">
            <strong>Notificaciones</strong>
        </div>
        <li v-for="(data, index) in dataarray.datos" :key="index">
            <a v-if="(data.msj == 'Ventas')" class="dropdown-item" href="#">
                <i class="fa fa-envelope-o"></i> {{ data.msj }}
                <span class="badge badge-success">{{ data.total }}</span>
            </a>
            <a v-else-if="(data.msj == 'Ingresos')" class="dropdown-item" href="#">
                <i class="fa fa-tasks"></i> {{ data.msj }}
                <span class="badge badge-danger">{{ data.total }}</span>
            </a>
            <a v-else class="dropdown-item" href="#">
                <i class="fa fa-tasks"></i> {{ data.msj }}
                <span class="badge badge-danger">{{ data.total }}</span>
            </a>
        </li>
    </div>
</li>
</template>
<<script>
export default {
    props : ['dataarray'],
    data: function(){
        return{
            data : []
        }
    }
}
</script>
