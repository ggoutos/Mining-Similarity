function draw_pie(g,c,m,n) {

var width = 300,
    height = 300,
    radius = Math.min(width, height) / 2,
    innerRadius = 0.3 * radius;

var pie = d3.layout.pie()
    .sort(null)
    .value(function(d) { return d.width; });

var tip = d3.tip()
  .attr('class', 'd3-tip')
  .offset([0, 0])
  .html(function(d) {
    return d.data.label + ": <span style='color:orangered'>" + d.data.score + "%</span>";
  });

  
var arc = d3.svg.arc()
  .innerRadius(innerRadius)
  .outerRadius(function (d) { 
    return (radius - innerRadius) * (d.data.score / 100.0) + innerRadius; 
  });
  
  
 var arc_2 = d3.svg.arc()
  .innerRadius(function (d) { 
    return (radius - innerRadius) * (d.data.score / 100.0) + innerRadius; 
  })
  .outerRadius(radius);

var outlineArc = d3.svg.arc()
        .innerRadius(innerRadius)
        .outerRadius(radius);

var svg = d3.select("#plot").append("svg")
.attr("width", width)
    .attr("height", height)
    .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

svg.call(tip);




var data = [
    { label: "Pages", score: g, order: 1, weight: 1, color: "#9E0041", width: 1},
    { label: "Checkins", score: c, order: 1, weight: 1, color: "#E1514B", width: 1},
    { label: "Music", score: m, order: 1, weight: 1, color: "#4776B4", width: 1},
    { label: "Movies", score: n, order: 1, weight: 1, color: "#5E4EA1", width: 1}
];



//d3.csv('aster_data.csv', function(error, data) {

  data.forEach(function(d) {
    d.id     =  d.id;
    d.order  = +d.order;
    d.color  =  d.color;
    d.weight = +d.weight;
    d.score  = +d.score;
    d.width  = +d.weight;
    d.label  =  d.label;
  });
  
  var path = svg.selectAll(".solidArc")
      .data(pie(data))
    .enter().append("path")
      .attr("fill", function(d) { return d.data.color; })
      .attr("class", "solidArc")
      .attr("stroke", "gray")
      .attr("d", arc)
      .on('mouseover', tip.show)
      .on('mouseout', tip.hide);
	  
	 var path2 = svg.selectAll(".solidArc2")
      .data(pie(data))
    .enter().append("path")
      .attr("fill", function(d) { return d.data.color; })
	  .attr("opacity", 0)
      .attr("class", "solidArc2")
      .attr("stroke", "gray")
      .attr("d", arc_2)
      .on('mouseover', tip.show)
      .on('mouseout', tip.hide);

  var outerPath = svg.selectAll(".outlineArc")
      .data(pie(data))
    .enter().append("path")
      .attr("fill", "none")
      .attr("stroke", "gray")
      .attr("class", "outlineArc")
      .attr("d", outlineArc);


  // calculate the weighted mean score
var score=0;
for(i=0; i<data.length; i++) {
score += data[i].score;
}
score=(score/4).toPrecision(4);


  svg.append("svg:text")
    .attr("class", "aster-score")
    .attr("dy", ".35em")
    .attr("text-anchor", "middle") // text-align: right
  .attr("fill", "#FFF")
    .text(score+'%');

/*
  svg.append("svg:text")
  .attr("class", "aster-score")
    .attr("dx", "10%")
    .attr("dy", "-20%")
    .attr("style", "text-align: right") // text-align: right
    .text('Pages');
    */

//});


}