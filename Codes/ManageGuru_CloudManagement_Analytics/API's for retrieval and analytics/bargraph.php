<html>
<head>
	<title>Learning D3</title>
	<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
</head>
<body>
<?php
$con = mysqli_connect("127.0.0.1:49985","azure","6#vWHD_$","manageguru");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  echo "lollll";

$sql = "SELECT * FROM menu";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "sish_id: " . $row["dish_id"]. " - dish_name: " . $row["dish_name"]. " " . $row["price"]. "<br>";
    }
} else {
    echo "0 results";
}
$con->close();
?>
	<div id="chart"></div>

	<script>
	/*
		var myData = [100, 125, 320, 440, 500, 250, 320, 720, 50, 300, 400, 500];
	*/
		var myData = [];
		var dataCount = 50;

		for(var i = 0; i < dataCount; i++){
			myData.push(Math.round(Math.random() * 500));
		}

		myData.sort(function(a, b){
			return a - b;
		});

		var margin = {
			top: 30,
			right: 30,
			bottom: 40,
			left: 50
		}

		var height = 500 - margin.top - margin.bottom;
		var width = 500 - margin.right - margin.left;
		var animateDuration = 700;
		var animateDelay = 30;

		var tooltip = d3.select('body').append('div')
			.style('position', 'absolute')
			.style('background', '#f4f4f4')
			.style('padding', '5 15px')
			.style('border', '1px #333 solid')
			.style('border-radius', '5px')
			.style('opacity', '0')

		var yScale = d3.scale.linear()
			.domain([0, d3.max(myData)])
			.range([0, height]);

		var xScale = d3.scale.ordinal()
			.domain(d3.range(0, myData.length))
			.rangeBands([0, width])

		var colors = d3.scale.linear()
			.domain([0, myData.length])
			.range(['#90ee90','#30c230'])

		var myChart = d3.select('#chart').append('svg')
				.attr('width', width + margin.right + margin.left)
				.attr('height', height + margin.top + margin.bottom)
				.append('g')
				.attr('transform', 'translate('+margin.left+','+margin.top+')')
				.style('background', '#f4f4f4')
				.selectAll('rect')
					.data(myData)
					.enter().append('rect')
						.style('fill', function(d, i){
							return colors(i);
						})
						.attr('width', xScale.rangeBand())
						.attr('height', 0)
						.attr('x', function(d, i){
							return xScale(i);
						})
						.attr('y', height)
				.on('mouseover', function(d){
					tooltip.transition()
						.style('opacity', 1)

					tooltip.html(d)
						.style('left', (d3.event.pageX)+'px')
						.style('top', (d3.event.pageY+'px'))
					d3.select(this).style('opacity', 0.5)

				})
				.on('mouseout', function(d){
					tooltip.transition()
						.style('opacity', 0)
					d3.select(this).style('opacity', 1)
				})

		myChart.transition()
			.attr('height', function(d){
				return yScale(d);
			})
			.attr('y', function(d){
				return height - yScale(d)
			})
			.duration(animateDuration)
			.delay(function(d, i){
				return i * animateDelay
			})
			.ease('elastic')


		var vScale = d3.scale.linear()
			.domain([0, d3.max(myData)])
			.range([height, 0]);

		var hScale = d3.scale.ordinal()
			.domain(d3.range(0, myData.length))
			.rangeBands([0, width])

		// V Axis
		var vAxis = d3.svg.axis()
			.scale(vScale)
			.orient('left')
			.ticks(5)
			.tickPadding(5)

		// V Guide
		var vGuide = d3.select('svg')
			.append('g')
				vAxis(vGuide)
				vGuide.attr('transform','translate('+margin.left+','+margin.top+')')
				vGuide.selectAll('path')
					.style('fill', 'none')
					.style('stroke', '#000')
				vGuide.selectAll('line')
					.style('stroke', '#000')

		// H Axis
		var hAxis = d3.svg.axis()
			.scale(hScale)
			.orient('bottom')
			.tickValues(hScale.domain().filter(function(d, i){
				return !(i % (myData.length/5));
			}));

		// H Guide
		var hGuide = d3.select('svg')
			.append('g')
				hAxis(hGuide)
				hGuide.attr('transform','translate('+margin.left+','+(height + margin.top)+')')
				hGuide.selectAll('path')
					.style('fill', 'none')
					.style('stroke', '#000')
				hGuide.selectAll('line')
					.style('stroke', '#000')

	</script>
</body>
</html>