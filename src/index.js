//import "./styles.css";
//import { generateDataSets } from "./dataGenerator";
//import { BarChartRace } from "./BarChartRace";

//import { select as d3Select } from "d3";

const myChart = new BarChartRace("bar-chart-race");

myChart
  .setTitle("Evolución nuevos casos de COVID-19 en Europa por millón de habitantes")
  .addDatasets(dataSet)
  .render();

d3.select("button").on("click", function() {
  if (this.innerHTML === "Parar") {
    this.innerHTML = "Continuar";
    myChart.stop();
  } else if (this.innerHTML === "Continuar") {
    this.innerHTML = "Parar";
    myChart.start();
  } else {
    this.innerHTML = "Parar";
    myChart.render();
  }
});

var ctx = document.getElementById('chart-vacunas');
ctx.height = 200;
const vacunasChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['2020', '2021', '2022', '2023'],
        datasets: [

            {
                label: 'Al menos una dosis',
                data: vacunas_una,
                backgroundColor: 'rgb(44,157,205)'

            },
            {
                label: 'Todas las dosis recomendadas',
                data: vacunas_full,
                backgroundColor: 'rgb(2, 53, 73)'
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Chart.js Bar Chart - Stacked'
            }
        },
        responsive: true,
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true
            }
        }
    }
});

var ctx2 = document.getElementById('chartPobreza');
ctx2.height = 100;
const pobrezaChart = new Chart(ctx2, {
    type: 'polarArea',
    data: {
        labels: labels_pobreza,
        datasets: [

            {
                label: 'Pobreza 2023',
                data: bar_pobreza,
                backgroundColor: [
                    'rgb(26,70,123)',
                    'rgb(30,81,143)',
                    'rgb(35,93,164)',
                    'rgb(39,105,184)',
                    'rgb(44,157,205)',
                    'rgb(65,130,210)',
                    'rgb(86,144,215)',
                    'rgb(107,158,220)',
                    'rgb(128,172,225)',
                    'rgb(149,186,230)'
                ]

            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Porcentaje de población que vive en pobreza extrema'
            }
        },
        responsive: true,
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true
            }
        }
    }
});

var ctx3 = document.getElementById('chart-gdp');
ctx3.height = 100;

const gdpChart = new Chart(ctx3, {
    type: 'polarArea',
    data: {
        labels: labels_gdp,
        datasets: [

            {
                label: 'GDP (Renta)',
                data: bar_gdp,
                backgroundColor: [
                    'rgb(14,50,48)',
                    'rgb(19,66,65)',
                    'rgb(24,83,81)',
                    'rgb(28,100,97)',
                    'rgb(33,116,114)',
                    'rgb(38,133,130)',
                    'rgb(43,150,146)',
                    'rgb(48,167,163)',
                    'rgb(68,175,172)',
                    'rgb(68,175,172)'
                ]

            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Porcentaje de población que vive en pobreza extrema'
            }
        },
        responsive: true,
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true
            }
        }
    }
});



showMap = function(year){
    
   $('.eventMap').each(function(){
      
       $(this).addClass('displayNone');
       
       svg2020 = d3.select('#world-chart-'+year),
	width = svg2020.attr("width"),
	height = svg2020.attr("height"),
	path = d3.geoPath(),
	data = d3.map(),
	worldmap = "https://raw.githubusercontent.com/holtzy/D3-graph-gallery/master/DATA/world.geojson";
       
       d3.queue()
	.defer(d3.json, worldmap)
	.defer(d3.csv, "data/new_death_"+year+".csv", function(d) {
		data.set(d.code, +d.pop);
	})
	.await(ready);
   });
   
   $('#canvas-map-'+year).removeClass('displayNone');
};



 
 // initial setup
var svg2020 = d3.select('#world-chart-2020'),
	width = svg2020.attr("width"),
	height = svg2020.attr("height"),
	path = d3.geoPath(),
	data = d3.map(),
	worldmap = "https://raw.githubusercontent.com/holtzy/D3-graph-gallery/master/DATA/world.geojson";

const svg2021 = d3.select('#world-chart-2021');

const svg2022 = d3.select('#world-chart-2022');


let centered, world;

// style of geographic projection and scaling
const projection = d3.geoRobinson()
	.scale(130)
	.translate([width / 2, height / 2]);

// Define color scale
const colorScale = d3.scaleThreshold()
	.domain([50, 100, 500, 1000, 1500, 2000])
	.range(d3.schemeBlues[7]);

// add tooltip
const tooltip = d3.select("body").append("div")
	.attr("class", "tooltip")
	.style("opacity", 0);

// Load external data and boot
d3.queue()
	.defer(d3.json, worldmap)
	.defer(d3.csv, "data/new_death_2020.csv", function(d) {
		data.set(d.code, +d.pop);
	})
	.await(ready);


// Add clickable background
svg2020.append("rect")
  .attr("class", "background")
	.attr("width", width)
	.attr("height", height)
	.on("click", click);


// ----------------------------
//Start of Choropleth drawing
// ----------------------------

function ready(error, topo) {
	// topo is the data received from the d3.queue function (the world.geojson)
	// the data from world_population.csv (country code and country population) is saved in data variable

	let mouseOver = function(d) {
		d3.selectAll(".Country")
			.transition()
			.duration(200)
			.style("opacity", .5)
			.style("stroke", "transparent");
		d3.select(this)
			.transition()
			.duration(200)
			.style("opacity", 1)
			.style("stroke", "black");
		tooltip.style("left", (d3.event.pageX + 15) + "px")
			.style("top", (d3.event.pageY - 28) + "px")
			.transition().duration(400)
			.style("opacity", 1)
                        .text(d.properties.name + ': ' + Math.round((d.total) * 10) / 10 + ' muertes por  millón de habitantes.');
			//.text(d.properties.name + ': ' + Math.round((d.total / 1000000) * 10) / 10 + ' mio.');
	}

	let mouseLeave = function() {
		d3.selectAll(".Country")
			.transition()
			.duration(200)
			.style("opacity", 1)
			.style("stroke", "transparent");
		tooltip.transition().duration(300)
			.style("opacity", 0);
	}

	// Draw the map
	world = svg2020.append("g")
    .attr("class", "world");
	world.selectAll("path")
		.data(topo.features)
		.enter()
		.append("path")
		// draw each country
		// d3.geoPath() is a built-in function of d3 v4 and takes care of showing the map from a properly formatted geojson file, if necessary filtering it through a predefined geographic projection
		.attr("d", d3.geoPath().projection(projection))

		//retrieve the name of the country from data
		.attr("data-name", function(d) {
			return d.properties.name
		})

		// set the color of each country
		.attr("fill", function(d) {
			d.total = data.get(d.id) || 0;
			return colorScale(d.total);
		})

		// add a class, styling and mouseover/mouseleave and click functions
		.style("stroke", "transparent")
		.attr("class", function(d) {
			return "Country"
		})
		.attr("id", function(d) {
			return d.id
		})
		.style("opacity", 1)
		.on("mouseover", mouseOver)
		.on("mouseleave", mouseLeave)
		.on("click", click);
  
	// Legend
	const x = d3.scaleLinear()
		.domain([2.6, 75.1])
		.rangeRound([600, 860]);

	const legend = svg2020.append("g")
		.attr("id", "legend");

	const legend_entry = legend.selectAll("g.legend")
		.data(colorScale.range().map(function(d) {
			d = colorScale.invertExtent(d);
			if (d[0] == null) d[0] = x.domain()[0];
			if (d[1] == null) d[1] = x.domain()[1];
			return d;
		}))
		.enter().append("g")
		.attr("class", "legend_entry");

	const ls_w = 20,
		ls_h = 20;

	legend_entry.append("rect")
		.attr("x", 20)
		.attr("y", function(d, i) {
			return height - (i * ls_h) - 2 * ls_h;
		})
		.attr("width", ls_w)
		.attr("height", ls_h)
		.style("fill", function(d) {
			return colorScale(d[0]);
		})
		.style("opacity", 0.8);

	legend_entry.append("text")
		.attr("x", 50)
		.attr("y", function(d, i) {
			return height - (i * ls_h) - ls_h - 6;
		}).text(function(d, i) {
			if (i === 0) return "< " + d[1] + "";
			if (d[1] < d[0]) return d[0] + "+";
			return d[0] + " - " + d[1] + "";
		});
		/*.text(function(d, i) {
			if (i === 0) return "< " + d[1] / 1000000 + " m";
			if (d[1] < d[0]) return d[0] / 1000000 + " m +";
			return d[0] / 1000000 + " m - " + d[1] / 1000000 + " m";
		});*/
    
                // lo he cambiado para el ejercicio

	legend.append("text").attr("x", 15).attr("y", 280).text("Muertes (millón de hab.)");
}


// Zoom functionality
function click(d) {
  var x, y, k;

  if (d && centered !== d) {
    var centroid = path.centroid(d);
    x = -(centroid[0] * 6);
    y = (centroid[1] * 6);
    k = 3;
    centered = d;
  } else {
    x = 0;
    y = 0;
    k = 1;
    centered = null;
  }

  world.selectAll("path")
      .classed("active", centered && function(d) { return d === centered; });

  world.transition()
      .duration(750)
      .attr("transform", "translate(" + x + "," + y + ") scale(" + k + ")" );
  
}


