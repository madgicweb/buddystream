<script src="<?php echo plugins_url();?>/buddystream/extensions/default/highcharts/highcharts.js" type="text/javascript"></script>


<table class="buddystream_table" cellspacing="0">
      <tr class="header">
          <td><?php echo __('Imported items vs user generated items current year', 'buddystream_twitter'); ?> (<?php echo date('Y'); ?>)</td>
          <td></td>
      </tr>
</table>

<script type="text/javascript">
        var chart;
        var $ = jQuery.noConflict();
        $(document).ready(function() {
                chart = new Highcharts.Chart({
                        chart: {
                                renderTo: 'chartCurrentYear', 
                                defaultSeriesType: 'area'
                        },
                        title: {
                                text: ''
                        },

                        xAxis: {
                                labels: {
                                        formatter: function() {
                                                return this.value; // clean, unformatted number for year
                                        }
                                }							
                        },
                        yAxis: {
                                title: {
                                        text: 'number of items'
                                },
                                labels: {
                                        formatter: function() {
                                                return this.value / 1000 +'k';
                                        }
                                }
                        },
                        tooltip: {
                                formatter: function() {
                                        return this.series.name +' imported <b>'+
                                                Highcharts.numberFormat(this.y, 0) +'</b><br/> items'
                                }
                        },
                        plotOptions: {
                                area: {
                                        pointStart: 1,
                                        marker: {
                                                enabled: false,
                                                symbol: 'circle',
                                                radius: 2,
                                                states: {
                                                        hover: {
                                                                enabled: true
                                                        }
                                                }
                                        }
                                }
                        },
                        series: [{
                                name: '<?php echo ucfirst($component); ?>',
                                data: [
                                    <?php
                                        $totalMonth = 12;
                                        $countMonth = 0;

                                        while ($countMonth < $totalMonth) {
                                            $countMonth++;
                                            echo count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='".$component."' AND MONTH(date_recorded)='".$countMonth."' AND YEAR(date_recorded)='" . date('Y') . "';")).",";
                                        }
                                        
                                    ?>
                        ]
                        }, {
                                name: 'User generated',
                                data: [
                                    <?php
                                        $excludeString = "";
                                        foreach (BuddyStreamExtensions::getExtensionsConfigs() as $extension) {
                                           $excludeString .= "AND type !='".$extension["name"]."' ";
                                        }

                                        $totalMonth = 12;
                                        $countMonth = 0;

                                        while ($countMonth < $totalMonth) {
                                            $countMonth++;
                                            echo count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE MONTH(date_recorded)='".$countMonth."' AND YEAR(date_recorded)='" . date('Y') . "' ".$excludeString." ;")).",";
                                        }
                                    ?>
                                ]
                        }]
                });


        });

</script>
<div id="chartCurrentYear" style="height:200px; width:100%;"></div>



<table class="buddystream_table" cellspacing="0">
      <tr class="header">
          <td><?php echo __('Imported items vs user generated items last year', 'buddystream_lang'); ?> (<?php echo date('Y')-1; ?>)</td>
          <td></td>
      </tr>
</table>

<script type="text/javascript">
        var chart;
        var $ = jQuery.noConflict();
        $(document).ready(function() {
                chart = new Highcharts.Chart({
                        chart: {
                                renderTo: 'chartLastYear', 
                                defaultSeriesType: 'area'
                        },
                        title: {
                                text: ''
                        },

                        xAxis: {
                                labels: {
                                        formatter: function() {
                                                return this.value; // clean, unformatted number for year
                                        }
                                }							
                        },
                        yAxis: {
                                title: {
                                        text: 'number of items'
                                },
                                labels: {
                                        formatter: function() {
                                                return this.value / 1000 +'k';
                                        }
                                }
                        },
                        tooltip: {
                                formatter: function() {
                                        return this.series.name +' imported <b>'+
                                                Highcharts.numberFormat(this.y, 0) +'</b><br/> items'
                                }
                        },
                        plotOptions: {
                                area: {
                                        pointStart: 1,
                                        marker: {
                                                enabled: false,
                                                symbol: 'circle',
                                                radius: 2,
                                                states: {
                                                        hover: {
                                                                enabled: true
                                                        }
                                                }
                                        }
                                }
                        },
                        series: [{
                                name: '<?php echo ucfirst($component); ?>',
                                data: [
                                    <?php
                                        $totalMonth = 12;
                                        $countMonth = 0;

                                        while ($countMonth < $totalMonth) {
                                            $countMonth++;
                                            echo count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE type='".$component."' AND MONTH(date_recorded)='".$countMonth."' AND YEAR(date_recorded)='" . (date('Y')-1) . "';")).",";
                                        }
                                    ?>
                        ]
                        }, {
                                name: 'User generated',
                                data: [
                                    <?php
                                        $excludeString = "";
                                        foreach (BuddyStreamExtensions::getExtensionsConfigs() as $extension) {
                                           $excludeString .= "AND type !='".$extension["name"]."' ";
                                        }

                                        $totalMonth = 12;
                                        $countMonth = 0;

                                        while ($countMonth < $totalMonth) {
                                            $countMonth++;
                                            echo count($wpdb->get_results("SELECT type FROM " . $bp->activity->table_name . " WHERE MONTH(date_recorded)='".$countMonth."' AND YEAR(date_recorded)='" . (date('Y')-1) . "' ".$excludeString." ;")).",";
                                        }
                                    ?>
                                ]
                        }]
                });


        });

</script>
<div id="chartLastYear" style="height:200px; width:100%;"></div>