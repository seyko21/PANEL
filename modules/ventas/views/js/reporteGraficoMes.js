/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 20-11-2014 17:11:16 
* Descripcion : reporteGraficoMes.js
* ---------------------------------------
*/
var reporteGraficoMes_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idReporteGraficoMes = 0;
    
    _private.config = {
        modulo: "ventas/reporteGraficoMes/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ReporteGraficoMes*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VRPT3,
            label: $(element).attr("title"),
            fnCallback: function(){
                reporteGraficoMes.getContenido();
            }
        });
    };
    
    /*contenido de tab: ReporteGraficoMes*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VRPT3+"_CONTAINER").html(data);
                reporteGraficoMes.getReporteGraficoMes();
            }
        });
    };
    
    this.publico.getReporteGraficoMes = function (){
        var mes = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];        
        var montoArray = {};
        var mesArray = {};
        
        
        var idMoneda = $("#"+diccionario.tabs.VRPT3+"lst_moneda").val();
        var periodo =  $("#"+diccionario.tabs.VRPT3+"lst_periodo").val();
        
        simpleAjax.send({            
            root: _private.config.modulo + 'getGrafico',  
            data: '&_idMoneda='+idMoneda+'&_periodo='+periodo,
            fnCallback: function(data) {
                for(var i in data){
                       montoArray[i] = data[i].monto;
                       mesArray[i] = data[i].mes;
                }
                var color = '',mees='',monto=0;
                var datos = '[';

                for(var m = 0;m<12;m++){
                    monto = 0;                             
                    mees = mes[m];
                    color = simpleScript.getRandomColor();  
                    for(var i in montoArray){
                        if((m+1) == mesArray[i]){
                            monto = montoArray[i];
                        }
                    }

                    datos += '{\n\
                        mes: "'+mees+'",\n\
                        monto: '+monto+',\n\
                        color: "'+color+'"\n\
                    },';
                }

                datos = datos.substring(0, datos.length-1);
                datos += ']';
                var chart;

                var chartData = eval(datos);
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "mes";
                chart.type = "serial";
                chart.theme = "light";
                // the following two lines makes chart 3D
                chart.depth3D = 20;
                chart.angle = 30;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 45;
                categoryAxis.dashLength = 5;
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.title = "Montos";
                valueAxis.dashLength = 5;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "monto";
                graph.colorField = "color";
                graph.balloonText = "<span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 1;
                chart.addGraph(graph);

                // CURSOR
                var chartCursor = new AmCharts.ChartCursor();
                chartCursor.cursorAlpha = 0;
                chartCursor.zoomable = false;
                chartCursor.categoryBalloonEnabled = false;
                chart.addChartCursor(chartCursor);

                chart.creditsPosition = "top-right";

                // WRITE
                chart.write(diccionario.tabs.VRPT3+"chartdiv");
            }
        });                        
    };
    
  
    
    return this.publico;
    
};
var reporteGraficoMes = new reporteGraficoMes_();