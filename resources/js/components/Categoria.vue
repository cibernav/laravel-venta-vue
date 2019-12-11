<template>
<main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Categorías
                <button type="button" class="btn btn-secondary" @click="openModal('categoria', 'registrar')">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" id="opcion" name="opcion" v-model="filtro.criterio">
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripción</option>
                            </select>
                            <input type="text" id="texto" name="texto" class="form-control" v-model="filtro.buscar" @keyup.enter="listarCategoria(1, filtro.buscar, filtro.criterio)" placeholder="Texto a buscar">
                            <button type="submit" class="btn btn-primary" @click.prevent="listarCategoria(1, filtro.buscar, filtro.criterio)"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="categoria in arrayCategoria" :key="categoria.id">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" @click="openModal('categoria', 'actualizar', categoria)">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <template v-if="categoria.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactiveCategoria(categoria.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" @click="activeCategoria(categoria.id)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="categoria.nombre"></td>
                            <td v-text="categoria.descripcion"></td>
                            <td>
                                <div v-if="categoria.condicion">
                                    <span class="badge badge-success">Activo</span>
                                </div>
                                <div v-else>
                                    <span class="badge badge-danger">Desactivado</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-show="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1, filtro.buscar, filtro.criterio)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="changePage(page, filtro.buscar, filtro.criterio)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-show="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1, filtro.buscar, filtro.criterio)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" id="modalNuevo" tabindex="-1" :class="{'showModal' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="closeModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="nombre">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" id="nombre" v-model="form.nombre" class="form-control" :class="{'is-invalid': errorLista.nombre }" placeholder="Ingrese nombre de categoría" autofocus>
                                <div class="invalid-feedback" v-show="errorLista.nombre">{{ errorLista.nombre ? errorLista.nombre[0] : '' }}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="descripcion">Descripción</label>
                            <div class="col-md-9">
                                <input type="text" id="descripcion" v-model="form.descripcion" class="form-control" :class="{'is-invalid': errorLista.descripcion }" placeholder="Ingrese descripcion">
                                <div class="invalid-feedback" v-show="errorLista.descripcion">{{ errorLista.descripcion ? errorLista.descripcion[0] : '' }}</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                    <button type="button" class="btn btn-primary" v-if="tipoAction==1" @click="registrarCategoria()">Guardar</button>
                    <button type="button" class="btn btn-primary" v-else @click="actualizarCategoria()">Actualizar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
</main>
</template>
<script>
export default {
    data: function(){
        return {
            form:{
                id : 0,
                nombre : '',
                descripcion : '',
            },
            arrayCategoria: [],
            modal: 0,
            tituloModal: '',
            tipoAction: 0,
            errorCategoria: 0,
            errorLista: [],
            pagination:{
                total : 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0
            },
            offset: 3,
            filtro:{
                criterio: 'nombre',
                buscar: ''
            }
        }
    },
    computed: {
        isActived: function(){
            return this.pagination.current_page;
        },
        //Calcula los elementos de la paginacion
        pagesNumber: function(){
            if(!this.pagination.to){
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if(from < 1){
                from = 1;
            }

            var to = from + (this.offset * 2);
            if(to >= this.pagination.last_page){
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while(from <= to){
                pagesArray.push(from);
                from++;
            }

            return pagesArray;
        }
    },
    methods: {
        listarCategoria: function(page = 1, buscar = '', criterio = ''){
            let self = this;
            axios.get('/api/categoria?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio).then(function (response){
                var respuesta = response.data;
                self.arrayCategoria = respuesta.data.data;
                self.pagination = respuesta.pagination;
                console.log(response);
            })
            .catch(function(error){
                console.log(error);
            });
        },
        changePage: function(page, buscar, criterio){
            let self = this;
            //Actualiza la pagina actual
            self.pagination.current_page = page;
            //Envia la peticion para visualizar la data de esta pagina
            self.listarCategoria(page, buscar, criterio);
        },
        registrarCategoria: function(){
            let self = this;
            self.errorLista = [];
            axios.post('/api/categoria', {
                'nombre' : this.form.nombre,
                'descripcion' : this.form.descripcion
            })
            .then(function(response){
                self.closeModal();
                self.listarCategoria();
            })
            .catch(function(error){
                self.errorLista = error.response.data.message;
                self.errorCategoria = 1;
            })
        },
        actualizarCategoria: function(){
            let self = this;
            self.errorLista = [];
            axios.put('/api/categoria/' + this.form.id, {
                'nombre' : this.form.nombre,
                'descripcion' : this.form.descripcion
            })
            .then(function(response){
                self.closeModal();
                self.listarCategoria();
            })
            .catch(function(error){
                self.errorLista = error.response.data.message;
                self.errorCategoria = 1;
            })
        },
        desactiveCategoria: function(id){
            const swal = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false,
            })

            swal.fire({
            title: 'Esta seguro de desactivar este registro ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Aceptar!',
            cancelButtonText: 'Cancelar!',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let self = this;
                    axios.patch('/api/categoria/' + id + '/activar')
                    .then(function(response){
                        self.listarCategoria();
                        swal.fire(
                        'Desactivado!',
                        'El registro ha sido desactivado.',
                        'success'
                        )
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            });
        },
        activeCategoria: function(id){
            const swal = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false,
            })

            swal.fire({
            title: 'Esta seguro de activar este registro ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Aceptar!',
            cancelButtonText: 'Cancelar!',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let self = this;
                    axios.patch('/api/categoria/' + id + '/activar')
                    .then(function(response){
                        self.listarCategoria();
                        swal.fire(
                        'Activado!',
                        'El registro ha sido activado.',
                        'success'
                        )
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            });
        },
        validateCategoria: function(){
            this.errorCategoria = 0;
            this.errorLista= [];
            let error = false;

            if (!this.nombre){
                this.errorLista['nombre'] = 'Debe ingresar un nombre';
                this.errorCategoria = 1;
            }
            return this.errorCategoria;
        },
        openModal: function(model, accion, data=[]){
            switch (model) {
                case 'categoria':
                    switch (accion) {
                        case 'registrar':
                            this.modal = 1;
                            this.tituloModal = 'Registrar Categoria';
                            this.form.nombre = '';
                            this.form.descripcion = '';
                            this.tipoAction = 1;
                            break;
                        case 'actualizar':
                            this.modal = 1;
                            this.tituloModal = 'Actualizar Categoria';
                            this.tipoAction = 2;
                            this.form.id = data.id;
                            this.form.nombre = data.nombre;
                            this.form.descripcion = data.descripcion;
                            break;
                        default:
                            break;
                    }
                    break;
                default:
                    break;
            }
        },
        closeModal: function(){
            this.modal = 0;
            this.tituloModal = '';
            this.nombre ='';
            this.descripcion = '';
            this.errorLista = [];
            this.errorCategoria = 0;
        }
    },
    mounted: function(){
        console.log('Componente categoria');
        this.listarCategoria(1);
    }
}
</script>
<style>
.showModal{
    display: list-item !important;
    opacity: 1 !important;
    position: absolute !important;
    background-color:  #423f3fa6 !important;
}
.modal-content{
    width: 100% !important;
    position: absolute !important;
}

</style>

