<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ingresos</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="cvingresos">

                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Compras de los ultimos meses.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ventas</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="cvventas">

                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Ventas de los ultimos meses.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
export default {
    data (){
        return{
            varIngreso : null, //control canvas
            charIngreso: null, //instancia de chartjs
            dataIngresos : [], //data ingresos
            varTotalIngreso : [], // data totales
            varMesIngreso: [], //data meses

            varVenta : null, //control canvas
            charVenta: null, //instancia de chartjs
            dataVentas : [], //data ingresos
            varTotalVenta : [], // data totales
            varMesVenta: [] //data meses
        }
    },
    methods: {
        getData: function(){
            let me = this;
            axios.get('/api/dashboard')
            .then(function (response){
                var respuesta = response.data;
                me.dataIngresos = respuesta.data.ingresos;
                me.dataVentas = respuesta.data.ventas;
                //cargamos los datos del chart
                me.loadIngresos();
                me.loadVentas();
            })
            .catch(function(error){
                console.log(error);
            });
        },
        loadIngresos: function(){
            let me = this;
            me.dataIngresos.map(function(x){
                me.varMesIngreso.push(x.MES);
                me.varTotalIngreso.push(x.TOTAL);
            });
            me.varIngreso = document.getElementById('cvingresos').getContext('2d');

            me.charIngreso = new Chart(me.varIngreso, {
                type: 'bar',
                data: {
                    labels: me.varMesIngreso,
                    datasets: [{
                        label: 'Ingresos',
                        data: me.varTotalIngreso,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        },
        loadVentas: function(){
            let me = this;
            me.dataVentas.map(function(x){
                me.varMesVenta.push(x.MES);
                me.varTotalVenta.push(x.TOTAL);
            });
            me.varVenta = document.getElementById('cvventas').getContext('2d');

            me.charVenta = new Chart(me.varVenta, {
                type: 'bar',
                data: {
                    labels: me.varMesVenta,
                    datasets: [{
                        label: 'Ventas',
                        data: me.varTotalVenta,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    },
    mounted: function(){
        this.getData();
    }
}
</script>

