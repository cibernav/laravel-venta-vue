<template>
<main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        <li class="breadcrumb-item active">Usuario</li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Usuarios
                <button type="button" class="btn btn-secondary" @click="openModal('persona', 'registrar')">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" id="opcion" name="opcion" v-model="filtro.criterio">
                                <option value="nombre">Nombre</option>
                                <option value="num_documento">Documento</option>
                                <option value="email">Email</option>
                                <option value="telefono">Telefono</option>
                            </select>
                            <input type="text" id="texto" name="texto" class="form-control" v-model="filtro.buscar" @keyup.enter="listarData(1, filtro.buscar, filtro.criterio)" placeholder="Texto a buscar">
                            <button type="submit" class="btn btn-primary" @click.prevent="listarData(1, filtro.buscar, filtro.criterio)"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th style="width: 90px">Opciones</th>
                            <th>Nombre</th>
                            <th>T.Doc.</th>
                            <th>Numero</th>
                            <th>Direccion</th>
                            <th>Telefono</th>
                            <th>Email</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in arrayData" :key="item.id">
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" @click="openModal('persona', 'actualizar', item)">
                                    <i class="icon-pencil"></i>
                                </button> &nbsp;
                                <template v-if="item.condicion">
                                    <button type="button" class="btn btn-danger btn-sm" @click="desactiveModel(item.id)">
                                        <i class="icon-trash"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-info btn-sm" @click="activeModel(item.id)">
                                        <i class="icon-check"></i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="item.nombre"></td>
                            <td v-text="item.tipo_documento"></td>
                            <td v-text="item.num_documento"></td>
                            <td v-text="item.direccion"></td>
                            <td v-text="item.telefono"></td>
                            <td v-text="item.email"></td>
                            <td v-text="item.username"></td>
                            <td v-text="item.rol"></td>
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
                            <label class="col-md-3 form-control-label" for="nombre">Nombre (*)</label>
                            <div class="col-md-9">
                                <input type="text" id="nombre" v-model="form.nombre" class="form-control" :class="{'is-invalid': errorLista.nombre }" placeholder="Ingrese nombre de persona" autofocus>
                                <div class="invalid-feedback" v-show="errorLista.nombre">{{ errorLista.nombre ? errorLista.nombre[0] : '' }}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="tipo_documento">Tipo Documento</label>
                            <div class="col-md-9">
                                <select v-model="form.tipo_documento" class="form-control" :class="{'is-invalid': errorLista.tipo_documento }" id="tipo_documento">
                                    <option value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="PASS">PASS</option>
                                </select>
                                <div class="invalid-feedback" v-show="errorLista.tipo_documento">{{ errorLista.tipo_documento ? errorLista.tipo_documento[0] : '' }}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="num_documento">Numero</label>
                            <div class="col-md-9">
                                <input type="text" id="num_documento" v-model="form.num_documento" class="form-control" :class="{'is-invalid': errorLista.num_documento }" placeholder="Ingrese numero de documento">
                                <div class="invalid-feedback" v-show="errorLista.num_documento">{{ errorLista.num_documento ? errorLista.num_documento[0] : '' }}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="direccion">Direccion</label>
                            <div class="col-md-9">
                                <input type="text" id="direccion" v-model="form.direccion" class="form-control" :class="{'is-invalid': errorLista.direccion }" placeholder="Ingrese una direccion">
                                <div class="invalid-feedback" v-show="errorLista.direccion">{{ errorLista.direccion ? errorLista.direccion[0] : '' }}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="telefono">Telefono</label>
                            <div class="col-md-9">
                                <input type="text" id="telefono" v-model="form.telefono" class="form-control" :class="{'is-invalid': errorLista.telefono }" placeholder="Ingrese un telefono">
                                <div class="invalid-feedback" v-show="errorLista.telefono">{{ errorLista.telefono ? errorLista.telefono[0] : '' }}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email">Email</label>
                            <div class="col-md-9">
                                <input type="email" id="email" v-model="form.email" class="form-control" :class="{'is-invalid': errorLista.email }" placeholder="Ingrese un email">
                                <div class="invalid-feedback" v-show="errorLista.email">{{ errorLista.email ? errorLista.email[0] : '' }}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="idrol">Rol (*)</label>
                            <div class="col-md-9">
                                <select v-model="form.idrol" class="form-control" :class="{'is-invalid': errorLista.idrol }" id="idrol">
                                    <option value="0">Seleccione un rol</option>
                                    <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre" ></option>
                                </select>
                                <div class="invalid-feedback" v-show="errorLista.idrol">{{ errorLista.idrol ? errorLista.idrol[0] : '' }}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="username">Usuario (*)</label>
                            <div class="col-md-9">
                                <input type="text" id="username" v-model="form.username" class="form-control" :class="{'is-invalid': errorLista.username }" placeholder="Ingrese un nombre de usuario">
                                <div class="invalid-feedback" v-show="errorLista.username">{{ errorLista.username ? errorLista.username[0] : '' }}</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="password">Password (*)</label>
                            <div class="col-md-9">
                                <input type="password" id="password" v-model="form.password" class="form-control" :class="{'is-invalid': errorLista.password }" placeholder="Ingrese un password de usuario">
                                <div class="invalid-feedback" v-show="errorLista.password">{{ errorLista.password ? errorLista.password[0] : '' }}</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                    <button type="button" class="btn btn-primary" v-if="tipoAction==1" @click="registrarModel()">Guardar</button>
                    <button type="button" class="btn btn-primary" v-else @click="actualizarModel()">Actualizar</button>
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
                tipo_documento: 'DNI',
                num_documento: '',
                direccion: '',
                telefono: '',
                email: '',
                username: '',
                password: '',
                idrol: 0
            },
            filtro:{
                criterio: 'nombre',
                buscar: ''
            },
            arrayData: [],
            modal: 0,
            tituloModal: '',
            tipoAction: 0, //1 - registrar , 2 - actualizar
            errorModel: 0, //flag
            errorLista: [],
            pagination:{
                total : 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0
            },
            offset: 3, // numero por pagina
            arrayRol: []
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
        listarData: function(page = 1, buscar = '', criterio = ''){
            let self = this;
            axios.get('/api/user?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio).then(function (response){
                var respuesta = response.data;
                self.arrayData = respuesta.data.data;
                self.pagination = respuesta.pagination;
                console.log(response);
            })
            .catch(function(error){
                console.log(error);
            });
        },
        selectRol: function(){
            let self = this;
            axios.get('/api/rol/selectactivo').then(function (response){
                var respuesta = response.data;
                self.arrayRol = respuesta.data;
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
            self.listarData(page, buscar, criterio);
        },
        registrarModel: function(){
            let self = this;
            self.errorLista = [];
            axios.post('/api/user', {
                'nombre' : this.form.nombre,
                'tipo_documento' : this.form.tipo_documento,
                'num_documento' : this.form.num_documento,
                'direccion' : this.form.direccion,
                'telefono' : this.form.telefono,
                'email' : this.form.email,
                'username' : this.form.username,
                'password' : this.form.password,
                'idrol' : this.form.idrol
            })
            .then(function(response){
                self.closeModal();
                self.listarData();
            })
            .catch(function(error){
                self.errorLista = error.response.data.message;
                self.errorModel = 1;
            })
        },
        actualizarModel: function(){
            let self = this;
            self.errorLista = [];
            axios.put('/api/user/' + this.form.id, {
                'nombre' : this.form.nombre,
                'tipo_documento' : this.form.tipo_documento,
                'num_documento' : this.form.num_documento,
                'direccion' : this.form.direccion,
                'telefono' : this.form.telefono,
                'email' : this.form.email,
                'username' : this.form.username,
                'password' : this.form.password,
                'idrol' : this.form.idrol
            })
            .then(function(response){
                self.closeModal();
                self.listarData();
            })
            .catch(function(error){
                self.errorLista = error.response.data.message;
                self.errorModel = 1;
            })
        },
        desactiveModel: function(id){
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
                    axios.patch('/api/user/' + id + '/activar')
                    .then(function(response){
                        self.listarData();
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
        activeModel: function(id){
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
                    axios.patch('/api/user/' + id + '/activar')
                    .then(function(response){
                        self.listarData();
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
            this.errorModel = 0;
            this.errorLista= [];
            let error = false;

            if (!this.nombre){
                this.errorLista['nombre'] = 'Debe ingresar un nombre';
                this.errorModel = 1;
            }
            return this.errorModel;
        },
        openModal: function(model, accion, data=[]){
            this.selectRol();
            switch (model) {
                case 'persona':
                    switch (accion) {
                        case 'registrar':
                            this.modal = 1;
                            this.tituloModal = 'Registrar Usuario';
                            this.tipoAction = 1;
                            this.resetModel();
                            break;
                        case 'actualizar':
                            this.modal = 1;
                            this.tituloModal = 'Actualizar Usuario';
                            this.tipoAction = 2;
                            this.resetModel(data);
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
            this.errorLista = [];
            this.errorModel= 0;
            this.resetModel();
        },
        resetModel: function(data = null){
            if(data == null){
                this.form.nombre ='';
                this.form.tipo_documento ='DNI';
                this.form.num_documento ='';
                this.form.direccion ='';
                this.form.telefono ='';
                this.form.email ='';
                this.form.username = '';
                this.form.password = '';
                this.form.idrol = 0;
            }else{
                this.form.id = data.id;
                this.form.nombre = data.nombre;
                this.form.tipo_documento = data.tipo_documento;
                this.form.num_documento = data.num_documento;
                this.form.direccion = data.direccion;
                this.form.telefono = data.telefono;
                this.form.email = data.email;
                this.form.username = data.username;
                this.form.password = data.password;
                this.form.idrol = data.idrol;
            }

        }
    },
    mounted: function(){
        console.log('Componente');
        this.listarData(1);
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

