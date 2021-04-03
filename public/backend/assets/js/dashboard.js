if ($('.ct-chart-sale').length) {
	var label_str = $('#visitor-labels').val();
	var labels = $.parseJSON(label_str);
	var value_str = $('#visitor-values').val();
	var values = $.parseJSON(value_str);
	console.log(labels);
	console.log(values);
  new Chartist.Line('.ct-chart-sale', {
    labels: labels, //["10:20", "10:30", "10:40", "10:50", "11:00", "11:10", "11:20", "11:30", "11:40", "11:50", "12:00", "12:10", "12:20", "12:30", "12:40", "12:50", "13:00", "13:10", "13:20", "13:30"]
    series: [values]
  }, {
    axisX: {
      position: 'center'
    },
    axisY: {
      offset: 0,
      showLabel: false,
      labelInterpolationFnc: function labelInterpolationFnc(value) {
        //return value / 1000 + 'k';
        return value;
      }
    },
    chartPadding: {
      top: 0,
      right: 0,
      bottom: 0,
      left: 0
    },
    height: 250,
    high: 5000,
    showArea: true,
    stackBars: true,
    fullWidth: true,
    lineSmooth: false,
    plugins: [Chartist.plugins.ctPointLabels({
      textAnchor: 'left',
      labelInterpolationFnc: function labelInterpolationFnc(value) {
        //return '$' + parseInt(value / 1000) + 'k';
        return value;
      }
    })]
  }, [['screen and (max-width: 768px)', {
    axisX: {
      offset: 0,
      showLabel: false
    },
    height: 180
  }]]);
}