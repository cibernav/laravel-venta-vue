<template>
<main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        <li class="breadcrumb-item active">Venta</li>
    </ol>
    <div class="container-fluid">
        <!-- Ejemplo de tabla Listado -->
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Ventas
                <button type="button" class="btn btn-secondary" @click="showRegistro()">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <!-- Listado -->
            <div id="dvbandeja" v-show="viewBandeja == 1">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" id="opcion" name="opcion" v-model="filtro.criterio">
                                    <option value="tipo_comprobante">Tipo Comprobante</option>
                                    <option value="num_comprobante">Numero Comprobante</option>
                                    <option value="fecha_hora">Fecha Hora</option>
                                </select>
                                <input type="text" id="texto" name="texto" class="form-control" v-model="filtro.buscar" @keyup.enter="listarData(1, filtro.buscar, filtro.criterio)" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click.prevent="listarData(1, filtro.buscar, filtro.criterio)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="width: 90px">Opciones</th>
                                <th>Usuario</th>
                                <th>Cliente</th>
                                <th>Tipo Doc.</th>
                                <th>Serie</th>
                                <th>Numero</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Impuesto</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in arrayData" :key="item.id">
                                <td class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm" @click="showDetalle(item.id)">
                                        <i class="icon-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" @click="showRpt(item.id)">
                                        <i class="icon-doc"></i>
                                    </button>
                                    <template v-if="item.estado== 'Registrado'">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactiveModel(item.id)">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </template>
                                </td>
                                <td v-text="item.usuario"></td>
                                <td v-text="item.cliente"></td>
                                <td v-text="item.tipo_comprobante"></td>
                                <td v-text="item.serie_comprobante"></td>
                                <td v-text="item.num_comprobante"></td>
                                <td v-text="item.fecha_hora"></td>
                                <td v-text="item.total"></td>
                                <td v-text="item.impuesto"></td>
                                <td>
                                    <div v-if="item.estado == 'Registrado'">
                                        <span class="badge badge-success">Registrado</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Anulado</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
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
            <!-- Detalle -->
            <div id="dvform" v-show="viewBandeja == 0">
                <div class="card-body">
                    <div class="form-group row border">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="">Cliente(*)</label>
                                <!--
                                    seach, metodo a buscar
                                    label, nombre del campo json
                                    options, cual son las opciones a mostrar
                                    input, evento para obtener datos del prove
                                -->
                                <v-select
                                    @search="selectCliente"
                                    label="nombre"
                                    :options="arrayCliente"
                                    placeholder="Buscar cliente..."
                                    @input="getDatosCliente"
                                    v-model= form.cliente>
                                </v-select>
                                <em class="invalid-feedback error-text" v-show="errorLista.idcliente">{{ errorLista.idcliente ? errorLista.idcliente[0] : '' }}</em>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="">Impuesto(*)</label>
                            <input type="text" class="form-control" :class="{'is-invalid': errorLista.impuesto }" v-model="form.impuesto">
                            <em class="invalid-feedback" v-show="errorLista.impuesto">{{ errorLista.impuesto ? errorLista.impuesto[0] : '' }}</em>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Tipo Comprobante(*)</label>
                                <select class="form-control" :class="{'is-invalid': errorLista.tipo_comprobante }" v-model="form.tipo_comprobante">
                                    <option value="0">Seleccione</option>
                                    <option value="BOLETA">Boleta</option>
                                    <option value="FACTURA">Factura</option>
                                    <option value="TICKET">Ticket</option>
                                </select>
                                <em class="invalid-feedback" v-show="errorLista.tipo_comprobante">{{ errorLista.tipo_comprobante ? errorLista.tipo_comprobante[0] : '' }}</em>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Serie Comprobante</label>
                                <input type="text" class="form-control" :class="{'is-invalid': errorLista.serie_comprobante }" v-model="form.serie_comprobante" placeholder="0000">
                                <em class="invalid-feedback" v-show="errorLista.serie_comprobante">{{ errorLista.serie_comprobante ? errorLista.serie_comprobante[0] : '' }}</em>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Numero Comprobante(*)</label>
                                <input type="text" class="form-control" :class="{'is-invalid': errorLista.num_comprobante }" v-model="form.num_comprobante" placeholder="0000">
                                <em class="invalid-feedback" v-show="errorLista.num_comprobante">{{ errorLista.num_comprobante ? errorLista.num_comprobante[0] : '' }}</em>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="col-md-5">
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label>Articulo <span style="color:red" v-show="detail.idarticulo == 0">(*Seleccione)</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" v-model="detail.codigo" @keyup.enter="buscarArticulo" placeholder="Ingrese articulo">
                                        <button type="button" class="btn btn-primary" @click="openModal">...</button>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <label>Descripcion</label>
                                    <input type="text" readonly class="form-control" v-model="detail.articulo" />
                                </div>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Precio <span style="color:red" v-show="detail.precio == 0">(*Ingrese)</span></label>
                                <input type="number" step="any" value="0" class="form-control" v-model="detail.precio">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cantidad <span style="color:red" v-show="detail.cantidad == 0">(*Ingrese)</span></label>
                                <input type="number" class="form-control" value="0" v-model="detail.cantidad">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Descuento </label>
                                <input type="number" class="form-control" value="0" v-model="detail.descuento">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <button type="button" class="btn btn-success form-control btnagregar" @click="addItemDetalle"><i class="icon-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border" v-show="errorLista.items">
                        <div class="col-md-12">
                            <div class="form-group">
                                <ul class="invalid-feedback error-text mt-3">
                                    <li v-for="(item, index) in errorLista.items" :key="index">
                                        {{ item }}
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="form-group row border">
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 80px">Opciones</th>
                                        <th style="">Articulo</th>
                                        <th style="width: 10%">Precio</th>
                                        <th style="width: 10%">Cantidad</th>
                                        <th style="width: 10%">Descuento</th>
                                        <th style="width: 15%">Subtotal</th>
                                    </tr>

                                </thead>
                                <tbody v-if="form.arrayItems.length">
                                    <tr v-for="(item,index) in form.arrayItems" :key="item.idarticulo">
                                        <td  class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm" @click="removeItemDetalle(index)">
                                                <i class="icon-close"></i>
                                            </button>
                                        </td>
                                        <td v-text="item.articulo">
                                            Articulo
                                        </td>
                                        <td>
                                            <input type="number" v-model="item.precio" class="form-control">
                                        </td>
                                        <td>
                                            <span style="color:red" v-show="item.cantidad>item.stock">Stock(*): {{ item.stock }}</span>
                                            <input type="number" step="any" v-model="item.cantidad" class="form-control">
                                        </td>
                                        <td>
                                            <span style="color:red" v-show="item.descuento>(item.precio * item.cantidad)">Descuento(*)</span>
                                            <input type="number" step="any" v-model="item.descuento" class="form-control">
                                        </td>
                                        <td class="text-right">
                                            $ {{ (item.precio * item.cantidad - item.descuento).toFixed(2) }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr class="text-center">
                                        <td colspan="6" >
                                            No hay articulos existentes.
                                            <em class="invalid-feedback error-text" v-show="errorLista.items">{{ errorLista.items ? errorLista.items[0] : '' }}</em>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="5" align="right"><strong>Total Parcial :</strong></td>
                                        <td class="text-right">$ {{ form.total_parcial = (form.total - form.total_impuesto).toFixed(2) }}</td>
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="5" align="right"><strong>Total Impuesto :</strong></td>
                                        <td class="text-right">$ {{ form.total_impuesto = ((form.total * form.impuesto)/(1 + form.impuesto)).toFixed(2) }}</td>
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="4" align="right">
                                            <em class="invalid-feedback error-text" v-show="errorLista.total">{{ errorLista.total ? errorLista.total[0] : '' }}</em>
                                        </td>
                                        <td align="right"><strong>Total Neto :</strong></td>
                                        <td class="text-right">$ {{ form.total = calcularTotal.toFixed(2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-secundary" @click="hideRegistro()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="registrarModel()">Registrar venta</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Ver Ingreso -->
            <div id="dvdetalle" v-show="viewBandeja == 2">
                <div class="card-body">
                    <div class="form-group row border">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="">Cliente</label>
                                <p v-text="form.cliente"></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="">Impuesto</label>
                            <p v-text="form.impuesto"></p>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Tipo Comprobante</label>
                                <p v-text="form.tipo_comprobante"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Serie Comprobante</label>
                                <p v-text="form.serie_comprobante"></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Numero Comprobante</label>
                                <p v-text="form.num_comprobante"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row border">
                        <div class="table-responsive col-md-12">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th style="">Articulo</th>
                                        <th style="width: 13%">Precio</th>
                                        <th style="width: 13%">Cantidad</th>
                                        <th style="width: 13%">Descuento</th>
                                        <th style="width: 15%">Subtotal</th>
                                    </tr>

                                </thead>
                                <tbody v-if="form.arrayItems.length">
                                    <tr v-for="(item) in form.arrayItems" :key="item.idarticulo">
                                        <td v-text="item.articulo">
                                            Articulo
                                        </td>
                                        <td class="text-right" v-text="item.precio">
                                        </td>
                                        <td class="text-right" v-text="item.cantidad">
                                        </td>
                                        <td class="text-right" v-text="item.descuento">
                                        </td>
                                        <td class="text-right">
                                            $ {{ ((item.precio * item.cantidad) - item.descuento).toFixed(2) }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr class="text-center">
                                        <td colspan="6" >
                                            No hay articulos existentes.
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="4" align="right"><strong>Total Parcial :</strong></td>
                                        <td class="text-right">$ {{ form.total_parcial }}</td>
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="4" align="right"><strong>Total Impuesto :</strong></td>
                                        <td class="text-right">$ {{ form.total_impuesto }}</td>
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="4" align="right"><strong>Total Neto :</strong></td>
                                        <td class="text-right">$ {{ form.total }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-secundary" @click="hideRegistro()">Cerrar</button>
                        </div>
                    </div>
                </div>
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
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-4" id="opcion" name="opcion" v-model="filtro.criterioArticulo">
                                    <option value="codigo">Codigo</option>
                                    <option value="nombre">Nombre</option>
                                    <option value="descripcion">Descripción</option>
                                </select>
                                <input type="text" id="texto" name="texto" class="form-control" v-model="filtro.buscarArticulo" @keyup.enter="listarArticuloModal(filtro.buscarArticulo, filtro.criterioArticulo)" placeholder="Texto a buscar">
                                <button type="submit" class="btn btn-primary" @click.prevent="listarArticuloModal(filtro.buscarArticulo, filtro.criterioArticulo)"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 70px">Opciones</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>P.Venta</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in arrayArticulo" :key="item.id">
                                    <td>
                                        <button type="button" class="btn btn-success btn-sm" @click="addDetalleModal(item)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </td>
                                    <td v-text="item.codigo"></td>
                                    <td v-text="item.nombre"></td>
                                    <td v-text="item.nombre_categoria"></td>
                                    <td v-text="item.precio_venta"></td>
                                    <td v-text="item.stock"></td>
                                    <td>
                                        <div v-if="item.condicion">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
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
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    data: function(){
        return {
            form:{
                id : 0,
                idcliente : 0,
                cliente: null,
                tipo_comprobante: 'BOLETA',
                serie_comprobante: '',
                num_comprobante: '',
                impuesto: 0.18,
                total: 0.0,
                total_impuesto : 0, //igv
                total_parcial: 0, //subtotal
                arrayItems: [
                    /*{
                        idarticulo: 0,
                        articulo: '',
                        precio: 0,
                        cantidad: 0,
                        descuento:0,
                        stock:0
                    }*/
                ]
            },
            filtro:{
                criterio: 'num_comprobante',
                buscar: '',
                criterioArticulo: 'nombre',
                buscarArticulo: ''
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
            arrayCliente: [],
            viewBandeja: 1, // 0 - 1
            arrayArticulo: [],
            detail:{
                idarticulo: 0,
                codigo: '',
                articulo: '',
                precio: 0,
                cantidad:0,
                descuento:0,
                stock:0
            }
        }
    },
    components:{
        'v-select' : vSelect
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
        },
        calcularTotal: function(){
            var resultado = 0.0;
            for (let i = 0; i < this.form.arrayItems.length; i++) {
                resultado += (this.form.arrayItems[i].precio * this.form.arrayItems[i].cantidad - this.form.arrayItems[i].descuento);
            }
            return resultado;
        }
    },
    methods: {
        listarData: function(page = 1, buscar = '', criterio = ''){
            let self = this;
            axios.get('/api/venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio)
            .then(function (response){
                var respuesta = response.data;
                self.arrayData = respuesta.data.data;
                self.pagination = respuesta.pagination;
                console.log(response);
            })
            .catch(function(error){
                console.log(error);
            });
        },
        selectCliente: function(search, loading){
            let self = this;
            loading(true);
            axios.get('/api/cliente/selectactivo?filtro=' + search)
            .then(function (response){
                var respuesta = response.data;
                //q:search
                self.arrayCliente = respuesta.data;
                loading(false);
            })
            .catch(function(error){
                console.log(error);
            });
        },
        getDatosCliente: function(valselect){
            let self = this;
            self.loading = true;
            self.form.idcliente = valselect != null ? valselect.id : 0;
        },
        buscarArticulo:function(){
            let me = this;
            axios.get('/api/articulo/buscarventa?filtro=' + this.detail.codigo)
            .then(function (response){
                var respuesta = response.data;
                me.arrayArticulo = respuesta.data;

                if(me.arrayArticulo.length > 0){
                    me.detail.articulo = me.arrayArticulo[0]['nombre'];
                    me.detail.idarticulo = me.arrayArticulo[0]['id'];
                    me.detail.precio = me.arrayArticulo[0]['precio_venta'];
                    me.detail.stock = me.arrayArticulo[0]['stock'];
                }else{
                    me.detail.articulo = "No articulo";
                    me.detail.idarticulo = 0;
                    me.detail.precio = 0;
                    me.detail.stock = 0;
                }
            })
            .catch(function(error){
                console.log(error);
            });
        },
        addItemDetalle: function(){
            var item = {
                idarticulo: this.detail.idarticulo,
                articulo: this.detail.articulo,
                precio: this.detail.precio,
                cantidad: this.detail.cantidad,
                descuento: this.detail.descuento,
                stock: this.detail.stock
            }

            if (item.idarticulo == 0 || item.precio == 0 || item.cantidad == 0)
                return;
            else{
                if (this.buscarItemExist(item.idarticulo)){
                    swal.fire({
                        type: 'warning',
                        title: 'Ya existe ...',
                        text: 'El articulo ya se encuentra agregado.'
                    });
                    return;
                }else{
                    if(item.cantidad > item.stock){
                        swal.fire({
                            type: 'warning',
                            title: 'Stock disponible (' + item.stock + ') ...',
                            text: 'No hay stock suficiente!.'
                        });
                        return;
                    }
                }
            }

            this.form.arrayItems.push(item);
            this.detail.idarticulo = 0;
            this.detail.codigo = '';
            this.detail.articulo = '';
            this.detail.precio = 0;
            this.detail.cantidad = 0;
            this.detail.descuento = 0;
            this.detail.stock = 0;
        },
        addDetalleModal: function(data = []){
            //Additem desde el modal
            if (this.buscarItemExist(data.id)){
                    swal.fire({
                        type: 'warning',
                        title: 'Ya existe ...',
                        text: 'El articulo ya se encuentra agregado.'
                    });
                    return;
            }
            this.detail.idarticulo = data.id;
            this.detail.articulo = data.nombre;
            this.detail.codigo = data.codigo;
            this.detail.precio = data.precio_venta;
            this.detail.cantidad = 0;
            this.detail.descuento = 0,
            this.detail.stock = data.stock;
            this.closeModal();
            //console.log(data);
        },
        listarArticuloModal: function(buscar = '', criterio = ''){
            let me = this;
            axios.get('/api/articulo/listarventa?buscar=' + buscar + '&criterio=' + criterio)
            .then(function (response){
                var respuesta = response.data;
                me.arrayArticulo = respuesta.data.data;
                console.log(response);
            })
            .catch(function(error){
                console.log(error);
            });
        },
        buscarItemExist: function(id){

            var item = this.form.arrayItems.find(x => x.idarticulo == id);
            return item != null;
        },
        removeItemDetalle: function(index){
            this.form.arrayItems.splice(index, 1);

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
            axios.post('/api/venta', {
                'idcliente' : this.form.idcliente,
                'idusuario' : this.form.idusuario,
                'tipo_comprobante' : this.form.tipo_comprobante,
                'serie_comprobante' : this.form.serie_comprobante,
                'num_comprobante' : this.form.num_comprobante,
                'impuesto' : this.form.impuesto,
                'subtotal' : this.form.total_parcial,
                'igv' : this.form.total_impuesto,
                'total' : this.form.total,
                'items' : this.form.arrayItems
            })
            .then(function(response){
                self.viewBandeja = 1;
                self.resetModel();
                self.listarData();
                console.log(response.data);
                self.showRpt(response.data.id);

            })
            .catch(function(error){
                self.errorLista = error.response.data.message;
                self.errorModel = 1;
            });
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
            title: 'Esta seguro de anular esta venta ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Aceptar!',
            cancelButtonText: 'Cancelar!',
            reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let self = this;
                    axios.patch('/api/venta/' + id + '/desactive')
                    .then(function(response){
                        self.listarData();
                        swal.fire(
                        'Anulado!',
                        'El registro ha sido anulado.',
                        'success'
                        )
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            });
        },
        openModal: function(model, accion, data=[]){
            this.arrayArticulo = [];
            this.modal = 1;
            this.tituloModal = 'Seleccione un articulo';

        },
        closeModal: function(){
            this.modal = 0;
            this.tituloModal = '';
        },
        resetModel: function(data = null, items = null){
            if(data == null){
                this.form.id = 0,
                this.form.cliente = null,
                this.form.idcliente = 0,
                this.form.tipo_comprobante = 'BOLETA',
                this.form.serie_comprobante = '',
                this.form.num_comprobante = '',
                this.form.impuesto = 0.18,
                this.form.total = 0.0,
                this.form.total_impuesto = 0, //igv
                this.form.total_parcial = 0,
                this.form.arrayItems = [];

                //detalle prev articulo
                this.detail.idarticulo = 0;
                this.detail.codigo = '';
                this.detail.articulo = '';
                this.detail.precio = 0;
                this.detail.cantidad = 0;
                this.detail.descuento = 0;
                this.detail.stock = 0;
            }else{
                this.form.id = data.id,
                this.form.cliente = data.cliente,
                //this.form.idcliente = data.idcliente,
                this.form.tipo_comprobante = data.tipo_comprobante,
                this.form.serie_comprobante = data.serie_comprobante,
                this.form.num_comprobante = data.num_comprobante,
                this.form.impuesto = data.impuesto,
                this.form.total = data.total,
                this.form.total_impuesto = data.igv, //igv
                this.form.total_parcial = data.subtotal,
                this.form.arrayItems = items;
            }

        },
        showRegistro: function(){
            this.viewBandeja = 0;
            this.resetModel();
        },
        hideRegistro: function() {
            this.viewBandeja = 1;
        },
        showDetalle: function(id){
            this.viewBandeja = 2;
            this.resetModel();
            let me = this;
            axios.get('/api/venta/show/' + id)
            .then(function (response){
                var respuesta = response.data;
                var master = respuesta.data.master;
                var items = respuesta.data.items;
                me.resetModel(master, items);

                console.log(master);
                console.log(items);
            })
            .catch(function(error){
                console.log(error);
            });
        },
        showRpt: function(id){
            window.open('/api/venta/comprobante/' + id, '_blank');
        }
    },
    mounted: function(){
        console.log('Componente');
        this.listarData();
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

@media (min-width: 600px) {
    .btnagregar{
        margin-top: 2rem;
    }
}

.error-text{
    display: block;
}

</style>

