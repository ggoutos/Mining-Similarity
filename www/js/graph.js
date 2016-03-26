function graph(me, friend, genre, section, response) {

    if (section == "Pages") {
        section = "#details1";
    };
    if (section == "Checkins") {
        section = "#details2";
    };
    if (section == "Music") {
        section = "#details3";
    };
    if (section == "Movies") {
        section = "#details4";
    };

    var graph = {
        "nodes": [
            {
                "name": me,
                "group": 1
            },
            {
                "name": friend,
                "group": 1
            },
            {
                "name": genre,
                "group": 2
            }

  ],
        "links": [
            {
                "source": me,
                "target": friend,
                "value": 1
            },

  ]
    };

    for (i = 0; i < response.length; i++) {
        graph.nodes.push({
            "name": response[i].name,
            "group": 3
        });
        graph.links.push({
            "source": me,
            "target": response[i].name,
            "group": 3
        });
        graph.links.push({
            "source": friend,
            "target": response[i].name,
            "group": 3
        });
        graph.links.push({
            "source": genre,
            "target": response[i].name,
            "group": 3
        });
    }

    var width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
    var height = 300;

    if (graph.nodes.length > 20) {
        height = 500;
    }


    var color = d3.scale.category20();

    var force = d3.layout.force()
        .charge(-2000)
        .linkDistance(100)
        .chargeDistance(500)
        .gravity(0.5)
        .size([width, height])
        .on("tick", tick);

    var svg = d3.select(section).append("svg")
        .attr("width", width)
        .attr("height", height);

    var nodeMap = {};

    graph.nodes.forEach(function (d) {
        nodeMap[d.name] = d;
    });

    graph.links.forEach(function (l) {
        l.source = nodeMap[l.source];
        l.target = nodeMap[l.target];
    })

    var link = svg.selectAll(".link"),
        node = svg.selectAll(".node");

    var nodes = graph.nodes,
        links = graph.links;

    // Restart the force layout.
    force
        .nodes(graph.nodes)
        .links(graph.links)
        .start();

    // Update links.
    var link = svg.selectAll(".link")
        .data(graph.links)
        .enter().append("line")
        .attr("class", "link")
        .style("stroke-width", function (d) {
            return Math.sqrt(d.value);
        });


    // Update nodes.
    node = node.data(nodes, function (d) {
        return d.name;
    });

    node.exit().remove();

    var nodeEnter = node.enter().append("g")
        .attr("class", "node")
        .call(force.drag);

    nodeEnter.append("circle")
        .attr("r", 13);

    nodeEnter.append("text")
        .attr("dy", "-15px")
        .text(function (d) {
            return d.name;
        });

    node.select("circle")
        .style("fill", function (d) {
            return color(d.group);
        });


    function tick() {
        link.attr("x1", function (d) {
                return d.source.x;
            })
            .attr("y1", function (d) {
                return d.source.y;
            })
            .attr("x2", function (d) {
                return d.target.x;
            })
            .attr("y2", function (d) {
                return d.target.y;
            });

        node.attr("transform", function (d) {
            return "translate(" + d.x + "," + d.y + ")";
        });
    }


}
