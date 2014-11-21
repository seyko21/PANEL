/*
* ---------------------------------------
* --------- CREATED BY CREATOR ----------
* fecha: 19-11-2014 22:11:59 
* Descripcion : reporteVentaDia.js
* ---------------------------------------
*/
var reporteVentaDia_ = function(){
    
    /*metodos privados*/
    var _private = {};
    
    _private.idReporteVentaDia = 0;
    _private.dataServer = {};
    _private.config = {
        modulo: "ventas/reporteVentaDia/"
    };

    /*metodos publicos*/
    this.publico = {};
    
    /*crea tab : ReporteVentaDia*/
    this.publico.main = function(element){
        simpleScript.addTab({
            id : diccionario.tabs.VRPT1,
            label: $(element).attr("title"),
            fnCallback: function(){
                reporteVentaDia.getContenido();
            }
        });
    };
    
    /*contenido de tab: ReporteVentaDia*/
    this.publico.getContenido = function(){
        simpleAjax.send({
            dataType: "html",
            root: _private.config.modulo,
            fnCallback: function(data){
                $("#"+diccionario.tabs.VRPT1+"_CONTAINER").html(data);
                reporteVentaDia.getGraficoReporteVentaDia();
            }
        });
    };
    
    this.publico.getGraficoReporteVentaDia = function (){
        var moned = ['SO','DO'];
        var montoArray = {};
        var monedaArray = {};
        
        simpleAjax.send({            
            root: _private.config.modulo + 'getGraficoVentaDia',            
            fnCallback: function(data) {
                for(var i in data){
                       montoArray[i] = data[i].monto;
                       monedaArray[i] = data[i].id_moneda;
                }
                var color = '',moneda='',monto=0,monedaD='';
                var datos = '[';

                for(var m = 0;m<2;m++){
                    monto = 0;
                    color = simpleScript.getRandomColor();                   
                    moneda = moned[m];
                    if(moneda == 'SO'){
                        monedaD = "S/";
                    }else if(moneda == 'DO'){
                        monedaD = "$USD";
                    }
                    for(var i in montoArray){                
                        if(moneda == monedaArray[i]){
                            monto = montoArray[i];
                        }
                    }

                    datos += '{\n\
                        moneda: "'+monedaD+'",\n\
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
                chart.categoryField = "moneda";
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
                chart.write(diccionario.tabs.VRPT1+"chartdiv");
            }
        });                        
    };
    
    this.publico.postPDF = function(btn,f){
           simpleAjax.send({
               element: btn,
               root: _private.config.modulo + 'postPDF',
               data: '&_fecha='+f,
               fnCallback: function(data) {
                   if(parseInt(data.result) === 1){
                       $('#'+diccionario.tabs.VRPT1+'btnDowPDF').off('click');
                       $('#'+diccionario.tabs.VRPT1+'btnDowPDF').attr("onclick","window.open('public/files/"+data.archivo+"','_blank');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                       $('#'+diccionario.tabs.VRPT1+'btnDowPDF').click();
                   }
               }
           });
       };
    
    this.publico.postExcel = function(btn,f){
        simpleAjax.send({
            element: btn,
            root: _private.config.modulo + 'postExcel',
            data: '&_fecha='+f,
            fnCallback: function(data) {
                if(parseInt(data.result) === 1){
                    $('#'+diccionario.tabs.VRPT1+'btnDowExcel').off('click');
                    $('#'+diccionario.tabs.VRPT1+'btnDowExcel').attr("onclick","window.open('public/files/"+data.archivo+"','_self');generarCotizacion.deleteArchivo('"+data.archivo+"');");
                    $('#'+diccionario.tabs.VRPT1+'btnDowExcel').click();
                }
                if(!isNaN(data.result) && parseInt(data.result) === 2){
                    simpleScript.notify.error({
                        content: 'Ocurrió un error al exportar Venta.'
                    });
                }
            }
        });
    };      
    
    return this.publico;
    
};
var reporteVentaDia = new reporteVentaDia_();