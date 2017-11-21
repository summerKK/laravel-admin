<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Chart</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="statisticsBrand" style="width: 800px;height:600px;"></div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="statisticsCategory" style="width: 800px;height:600px;"></div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 100px;">

                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="statisticsWorld" style="width: 800px;height:600px;"></div>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="statisticsWorld1" style="width: 800px;height:600px;"></div>
                </div>

            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
</div>
<script src="/js/echarts/echarts.js"></script>
<!-- 引入 vintage 主题 -->
<script src="/js/echarts/roma.js"></script>
<script type="text/javascript" src="//echarts.baidu.com/gallery/vendors/echarts/map/js/world.js"></script>

<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('statisticsBrand'), 'roma');

    var data = {!! $data['brands'] !!};
    option = {
        title: {
            text: 'Brand Statistics',
            subtext: '',
            x: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            type: 'scroll',
            orient: 'vertical',
            right: 10,
            top: 20,
            bottom: 20,
            data: data.legendData
        },
        series: [
            {
                name: 'Brand',
                type: 'pie',
                radius: '55%',
                center: ['40%', '50%'],
                data: data.seriesData,
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>

<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('statisticsCategory'));

    // 指定图表的配置项和数据

    {!! $data['type'] !!}

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>

<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('statisticsWorld'));

    // 指定图表的配置项和数据
    var option = {
        title: {
            text: 'Product Locations Statistics',
            subtext: '',
            sublink: 'http://esa.un.org/wpp/Excel-Data/population.htm',
            left: 'center',
            top: 'top'
        },
        tooltip: {
            trigger: 'item',
        },
        toolbox: {
            show: true,
            orient: 'vertical',
            left: 'right',
            top: 'center',
            feature: {
                dataView: {readOnly: false},
                restore: {},
                saveAsImage: {}
            }
        },
        visualMap: {
            min: 0,
            max: 1000000,
            text: ['High', 'Low'],
            realtime: false,
            calculable: true,
            inRange: {
                color: ['lightskyblue', 'yellow', 'orangered']
            }
        },
        series: [
            {
                name: '',
                type: 'map',
                mapType: 'world',
                roam: true,
                itemStyle: {
                    emphasis: {label: {show: true}}
                },
                data: {!! $data['country'] !!}
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>

<script type="text/javascript">
    // 基于准备好的dom，初始化echarts实例
    var myChart = echarts.init(document.getElementById('statisticsWorld1'));

    // 指定图表的配置项和数据
    var option = {
        title: {
            text: 'Product Locations Statistics',
            subtext: '',
            x: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: {!! $data['country1']['legend'] !!}
        },
        series: [
            {
                name: '访问来源',
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                data: {!! $data['country1']['series'] !!},
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
</script>
