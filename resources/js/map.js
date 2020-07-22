const mapboxgl = require('mapbox-gl');
const defaultStyles = [{
        label: '<i class="fas fa-moon"></i>',
        styleName: 'Mapbox Dark',
        styleUrl: 'mapbox://styles/mapbox/dark-v10',
    }, {
        label: '<i class="fas fa-sun"></i>',
        styleName: 'Mapbox Streets',
        styleUrl: 'mapbox://styles/mapbox/streets-v11',
    }, {
        label: '<i class="fas fa-satellite-dish"></i>',
        styleName: 'Mapbox Satellite Streets',
        styleUrl: 'mapbox://sprites/mapbox/satellite-streets-v11',
    },
    {
        label: '<i class="fas fa-mountain"></i>',
        styleName: 'Mapbox Outdoors',
        styleUrl: 'mapbox://sprites/mapbox/outdoors-v11',
    }
];

class StylesControl {
    constructor(options = {}) {
        this.styles = options.styles || defaultStyles;
        this.onChange = options.onChange;
    }

    insertControls() {
        this.container = document.createElement('div');
        this.container.classList.add('mapboxgl-ctrl');
        this.container.classList.add('mapboxgl-ctrl-group');
        this.container.classList.add('mapboxgl-ctrl-styles');
        this.nodes = [];
        this.styles.forEach((style) => {
            const node = document.createElement('button');
            node.setAttribute('type', 'button');
            node.innerHTML = style.label;
            node.addEventListener('click', () => {
                if (node.classList.contains('-active')) return;
                this.map.setStyle(style.styleUrl);
                if (this.onChange) this.onChange(style);
            });
            this.nodes.push(node);
            this.container.appendChild(node);
        });
    }

    onAdd(map) {
        this.map = map;
        this.insertControls();
        this.map.on('styledata', () => {
            [].forEach.call(this.container.querySelectorAll('button'), (div) => {
                div.classList.remove('-active');
            });
            const styleNames = this.styles.map((style) => style.styleName);
            const currentStyleIndex = styleNames.indexOf(this.map.getStyle().name);
            if (currentStyleIndex !== -1) {
                const currentNode = this.nodes[currentStyleIndex];
                currentNode.classList.add('-active');
            }
        });
        return this.container;
    }

    onRemove() {
        this.container.parentNode.removeChild(this.container);
        this.map = undefined;
    }
}

class Map {
    constructor(theater_of_operations_id, map_id, center_zoom, center_lat, center_long) {
        let that = this;
        this.theater_of_operations_id = theater_of_operations_id;
        this.map_id = map_id;
        this.center_zoom = center_zoom;
        this.center_lat = center_lat;
        this.center_long = center_long;

        mapboxgl.accessToken = 'pk.eyJ1IjoiY290ZW1lcm8iLCJhIjoiY2tjODF1d2QwMTdiaDJzbzB4aWFyamt6OSJ9.w6EjJ_JXQ8B6ESKZqKwP-w';

        this.mapbox_map = new mapboxgl.Map({
            container: this.map_id,
            style: 'mapbox://styles/mapbox/dark-v10',
            center: [this.center_long, this.center_lat],
            zoom: this.center_zoom
        });
        this.mapbox_map.addControl(new StylesControl({
            "onChange": function () {
                if (that.theater_of_operations_id == 0) {
                    that.createPOIs();
                    that.createUnits();
                    that.createEvents();
                    that.createTOs();
                    that.loadIcons();
                } else {
                    that.createPOIs();
                    that.createUnits();
                    that.createEvents();
                    that.loadIcons();
                }
            }
        }), 'top-left');
        this.mapbox_map.addControl(new mapboxgl.FullscreenControl(), 'top-left');
        this.mapbox_map.addControl(new mapboxgl.NavigationControl());
        this.mapbox_map.addControl(new mapboxgl.ScaleControl(), 'bottom-right');
        this.mapbox_map.on('load', function () {
            that.createPOIs();
            that.createUnits();
            that.createEvents();
            setInterval(function () {
                that.updatePOIs();
            }, 5000);
            setInterval(function () {
                that.updateUnits();
            }, 5000);
            setInterval(function () {
                that.updateEvents();
            }, 5000);
            if (that.theater_of_operations_id == 0) {
                that.createTOs();
                setInterval(function () {
                    that.updateTOs();
                }, 5000);
                that.prepareClickableTOs();
            }
            that.loadIcons();
            that.prepareClickablePOIs();
            that.prepareClickableUnits();
            that.prepareClickableEvents();
        });
        $(".mapboxgl-ctrl .mapboxgl-ctrl-logo").closest('div').prepend('<a class="mapboxgl-ctrl-logo-cne" target="_blank" rel="noopener nofollow" href="https://emergenciacvp.pt/" aria-label="CNE CVP logo"></a>');
    }

    loadIcons() {
        let that = this;
        this.mapbox_map.loadImage(
            '/img/map_icons/Ocorrência Geral.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_OcorrênciaGeral', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/Evacuação.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_Evacuação', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/Incêndio.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_Incêndio', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/POI Geral.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_POIGeral', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/PC.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_PC', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/ZCAP.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_ZCAP', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/ZCR.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_ZCR', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/Logística.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_Logistica', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/PMA.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_PMA', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/Antena.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_Antena', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/Satélite.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_Satélite', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/A1.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_A1', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/A2.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_A2', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/B.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_B', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/C.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_C', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/VDTD.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_VDTD', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/TL.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_TL', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/CC.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_CC', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/CO.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_CO', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/LO.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_LO', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/LP.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_LP', image);
            }
        );
        this.mapbox_map.loadImage(
            '/img/map_icons/HELI.png',
            function (error, image) {
                if (error) throw error;
                that.mapbox_map.addImage('icon_HELI', image);
            }
        );
    }

    getUnitsFeatures(callback) {
        let that = this;
        if (this.theater_of_operations_id == 0) {
            axios.get("/units_info")
                .then(function (response) {
                    let a1_features = [];
                    let a2_features = [];
                    let b_features = [];
                    let c_features = [];
                    let vdtd_features = [];
                    let tl_features = [];
                    let cc_features = [];
                    let co_features = [];
                    let lo_features = [];
                    let lp_features = [];
                    let heli_features = [];
                    response.data.forEach(unit => {
                        if (unit["type"] == "A1") {
                            a1_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "A2") {
                            a2_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "B") {
                            b_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "C") {
                            c_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "VDTD") {
                            vdtd_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "TL") {
                            tl_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "CC") {
                            cc_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "CO") {
                            co_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "LO") {
                            lo_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "LP") {
                            lp_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        } else if (unit["type"] == "HELI") {
                            heli_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit["id"],
                                    'name': unit["tail_number"] == null ? (unit["plate"] == null ? "N/A" : unit["plate"]) : unit["tail_number"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit["long"], unit["lat"]]
                                }
                            });
                        }
                    });
                    callback({
                        "a1": a1_features,
                        "a2": a2_features,
                        "b": b_features,
                        "c": c_features,
                        "vdtd": vdtd_features,
                        "tl": tl_features,
                        "cc": cc_features,
                        "co": co_features,
                        "lo": lo_features,
                        "lp": lp_features,
                        "heli": heli_features,
                    });
                });
        } else {
            axios.get("/" + this.theater_of_operations_id + "/getActiveUnits")
                .then(function (response) {
                    let a1_features = [];
                    let a2_features = [];
                    let b_features = [];
                    let c_features = [];
                    let vdtd_features = [];
                    let tl_features = [];
                    let cc_features = [];
                    let co_features = [];
                    let lo_features = [];
                    let lp_features = [];
                    let heli_features = [];
                    response.data.forEach(unit => {
                        if (unit[0] == "A1") {
                            a1_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "A2") {
                            a2_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "B") {
                            b_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "C") {
                            c_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "VDTD") {
                            vdtd_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "TL") {
                            tl_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "CC") {
                            cc_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "CO") {
                            co_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "LO") {
                            lo_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "LP") {
                            lp_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        } else if (unit[0] == "HELI") {
                            heli_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'unit_id': unit[8],
                                    'name': unit[1] == "N/A" ? unit[2] : unit[1],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [unit[7], unit[6]]
                                }
                            });
                        }
                    });
                    callback({
                        "a1": a1_features,
                        "a2": a2_features,
                        "b": b_features,
                        "c": c_features,
                        "vdtd": vdtd_features,
                        "tl": tl_features,
                        "cc": cc_features,
                        "co": co_features,
                        "lo": lo_features,
                        "lp": lp_features,
                        "heli": heli_features,
                    });
                });
        }
    }

    createUnits(addSources = true, createLayer = true) {
        let that = this;
        this.getUnitsFeatures(function (features) {
            if (addSources) {
                that.mapbox_map.addSource('icons_A1', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["a1"]
                    }
                });
                that.mapbox_map.addSource('icons_A2', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["a2"]
                    }
                });
                that.mapbox_map.addSource('icons_B', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["b"]
                    }
                });
                that.mapbox_map.addSource('icons_C', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["c"]
                    }
                });
                that.mapbox_map.addSource('icons_VDTD', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["vdtd"]
                    }
                });
                that.mapbox_map.addSource('icons_TL', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["tl"]
                    }
                });
                that.mapbox_map.addSource('icons_CC', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["cc"]
                    }
                });
                that.mapbox_map.addSource('icons_CO', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["co"]
                    }
                });
                that.mapbox_map.addSource('icons_LO', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["lo"]
                    }
                });
                that.mapbox_map.addSource('icons_LP', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["lp"]
                    }
                });
                that.mapbox_map.addSource('icons_HELI', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["heli"]
                    }
                });
            }
            if (createLayer) {
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_A1',
                    'type': 'symbol',
                    'source': 'icons_A1',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_A1',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_A2',
                    'type': 'symbol',
                    'source': 'icons_A2',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_A2',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_B',
                    'type': 'symbol',
                    'source': 'icons_B',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_B',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_C',
                    'type': 'symbol',
                    'source': 'icons_C',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_C',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_VDTD',
                    'type': 'symbol',
                    'source': 'icons_VDTD',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_VDTD',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_TL',
                    'type': 'symbol',
                    'source': 'icons_TL',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_TL',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_CC',
                    'type': 'symbol',
                    'source': 'icons_CC',
                    'paint': {
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_CC',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_CO',
                    'type': 'symbol',
                    'source': 'icons_CO',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_CO',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_LO',
                    'type': 'symbol',
                    'source': 'icons_LO',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_LO',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_LP',
                    'type': 'symbol',
                    'source': 'icons_LP',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_LP',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_HELI',
                    'type': 'symbol',
                    'source': 'icons_HELI',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#0051de",
                    },
                    'layout': {
                        'text-anchor': 'bottom',
                        'text-field': '{name}',
                        'icon-image': 'icon_HELI',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
            }
        });
    }

    updateUnits() {
        let that = this;
        this.getUnitsFeatures(function (features) {
            that.mapbox_map.getSource('icons_A1').setData({
                'type': 'FeatureCollection',
                'features': features["a1"]

            });
            that.mapbox_map.getSource('icons_A2').setData({
                'type': 'FeatureCollection',
                'features': features["a2"]

            });
            that.mapbox_map.getSource('icons_B').setData({
                'type': 'FeatureCollection',
                'features': features["b"]

            });
            that.mapbox_map.getSource('icons_C').setData({
                'type': 'FeatureCollection',
                'features': features["c"]

            });
            that.mapbox_map.getSource('icons_VDTD').setData({
                'type': 'FeatureCollection',
                'features': features["vdtd"]

            });
            that.mapbox_map.getSource('icons_TL').setData({
                'type': 'FeatureCollection',
                'features': features["tl"]

            });
            that.mapbox_map.getSource('icons_CC').setData({
                'type': 'FeatureCollection',
                'features': features["cc"]

            });
            that.mapbox_map.getSource('icons_CO').setData({
                'type': 'FeatureCollection',
                'features': features["co"]

            });
            that.mapbox_map.getSource('icons_LO').setData({
                'type': 'FeatureCollection',
                'features': features["lo"]

            });
            that.mapbox_map.getSource('icons_LP').setData({
                'type': 'FeatureCollection',
                'features': features["lp"]

            });
            that.mapbox_map.getSource('icons_HELI').setData({
                'type': 'FeatureCollection',
                'features': features["heli"]

            });
        });
    }

    prepareClickableUnits() {
        let that = this;
        let this_function = function (e) {
            location.replace('/unit/' + e.features[0].properties.unit_id);
        };
        let cursor_enter_function = function () {
            that.mapbox_map.getCanvas().style.cursor = 'pointer'
        }
        let cursor_leave_function = function () {
            that.mapbox_map.getCanvas().style.cursor = ''
        }
        this.mapbox_map.on('click', 'layer_icons_A1', this_function);
        this.mapbox_map.on('click', 'layer_icons_A2', this_function);
        this.mapbox_map.on('click', 'layer_icons_B', this_function);
        this.mapbox_map.on('click', 'layer_icons_C', this_function);
        this.mapbox_map.on('click', 'layer_icons_VDTD', this_function);
        this.mapbox_map.on('click', 'layer_icons_TL', this_function);
        this.mapbox_map.on('click', 'layer_icons_CC', this_function);
        this.mapbox_map.on('click', 'layer_icons_CO', this_function);
        this.mapbox_map.on('click', 'layer_icons_LO', this_function);
        this.mapbox_map.on('click', 'layer_icons_LP', this_function);
        this.mapbox_map.on('click', 'layer_icons_HELI', this_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_A1', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_A1', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_A2', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_A2', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_B', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_B', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_C', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_C', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_VDTD', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_VDTD', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_TL', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_TL', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_CC', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_CC', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_CP', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_CP', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_LO', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_LO', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_LP', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_LP', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_HELI', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_HELI', cursor_leave_function);

    }

    getEventsFeatures(callback) {
        let that = this;
        if (this.theater_of_operations_id == 0) {
            axios.get("/events_info")
                .then(function (response) {
                    let geral_features = [];
                    let evacuacao_features = [];
                    response.data.forEach(event => {
                        if (event["type"] == "EVA") {
                            evacuacao_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'event_id': event["id"],
                                    'name': (event["type"] + " - " + event["location"]),
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [event["long"], event["lat"]]
                                }
                            });
                        } else {
                            geral_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'event_id': event["id"],
                                    'name': (event["type"] + " - " + event["location"]),
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [event["long"], event["lat"]]
                                }
                            });
                        }
                    });
                    callback({
                        "geral": geral_features,
                        "evacuacao": evacuacao_features,
                    });
                });
        } else {
            axios.get("/" + this.theater_of_operations_id + "/getActiveEvents")
                .then(function (response) {
                    let geral_features = [];
                    let evacuacao_features = [];
                    response.data.forEach(event => {
                        if (event[0] == "EVA") {
                            evacuacao_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'event_id': event[7],
                                    'name': (event[0] + " - " + event[1]),
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [event[6], event[5]]
                                }
                            });
                        } else {
                            geral_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'event_id': event[7],
                                    'name': (event[0] + " - " + event[1]),
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [event[6], event[5]]
                                }
                            });
                        }
                    });
                    callback({
                        "geral": geral_features,
                        "evacuacao": evacuacao_features,
                    });
                });
        }
    }

    createEvents(addSources = true, createLayer = true) {
        let that = this;
        this.getEventsFeatures(function (features) {
            if (addSources) {
                that.mapbox_map.addSource('events_Evacuação', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["evacuacao"]
                    }
                });
                that.mapbox_map.addSource('events_Geral', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["geral"]
                    }
                });
            }
            if (createLayer) {
                that.mapbox_map.addLayer({
                    'id': 'layer_events_Evacuação',
                    'type': 'symbol',
                    'source': 'events_Evacuação',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#a10000",
                    },
                    'layout': {
                        'text-anchor': 'bottom',
                        'text-field': '{name}',
                        'icon-image': 'icon_Evacuação',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_events_Geral',
                    'type': 'symbol',
                    'source': 'events_Geral',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#a10000",
                    },
                    'layout': {
                        'text-anchor': 'bottom',
                        'text-field': '{name}',
                        'icon-image': 'icon_OcorrênciaGeral',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
            }
        });
    }

    updateEvents() {
        let that = this;
        let features = this.getEventsFeatures(function (features) {
            that.mapbox_map.getSource('events_Evacuação').setData({
                'type': 'FeatureCollection',
                'features': features["evacuacao"]

            });
            that.mapbox_map.getSource('events_Geral').setData({
                'type': 'FeatureCollection',
                'features': features["geral"]
            });
        });
    }

    prepareClickableEvents() {
        let that = this;
        let this_function = function (e) {
            location.replace('/event/' + e.features[0].properties.event_id);
        };
        let cursor_enter_function = function () {
            that.mapbox_map.getCanvas().style.cursor = 'pointer'
        }
        let cursor_leave_function = function () {
            that.mapbox_map.getCanvas().style.cursor = ''
        }
        this.mapbox_map.on('click', 'layer_events_Evacuação', this_function);
        this.mapbox_map.on('click', 'layer_events_Geral', this_function);
        this.mapbox_map.on('mouseenter', 'layer_events_Evacuação', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_events_Evacuação', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_events_Geral', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_events_Geral', cursor_leave_function);
    }

    getTOsFeatures(callback) {
        let that = this;
        axios.get("/info")
            .then(function (response) {
                let geral_features = [];
                let incendio_features = [];                
                response.data.forEach(to => {
                    if (to["type"] == "Incêndio") {
                        incendio_features.push({
                            'type': 'Feature',
                            'properties': {
                                'to_id': to["id"],
                                'name': to["name"],
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [to["long"], to["lat"]]
                            }
                        });
                    }
                    else {
                        geral_features.push({
                            'type': 'Feature',
                            'properties': {
                                'to_id': to["id"],
                                'name': to["name"],
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [to["long"], to["lat"]]
                            }
                        });
                    }
                });
                callback({
                    "geral": geral_features,
                    "incêndio": incendio_features,
                });
            });
    }

    createTOs(addSources = true, createLayer = true) {
        let that = this;
        this.getTOsFeatures(function (features) {
            if (addSources) {
                that.mapbox_map.addSource('icons_OcorrênciaGeral', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["geral"]
                    }
                });
                that.mapbox_map.addSource('icons_Incêndio', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["incêndio"]
                    }
                });
            }
            if (createLayer) {
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_OcorrênciaGeral',
                    'type': 'symbol',
                    'source': 'icons_OcorrênciaGeral',
                    'paint': {
                        "text-color": "#a10000",
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                    },
                    'layout': {
                        'text-anchor': 'bottom',
                        'text-field': '{name}',
                        'icon-image': 'icon_OcorrênciaGeral',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_Incêndio',
                    'type': 'symbol',
                    'source': 'icons_Incêndio',
                    'paint': {
                        "text-color": "#a10000",
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                    },
                    'layout': {
                        'text-anchor': 'bottom',
                        'text-field': '{name}',
                        'icon-image': 'icon_Incêndio',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
            }
        });
    }

    updateTOs() {
        let that = this;
        let features = this.getTOsFeatures(function (features) {
            that.mapbox_map.getSource('icons_OcorrênciaGeral').setData({
                'type': 'FeatureCollection',
                'features': features["geral"]

            });
            that.mapbox_map.getSource('icons_Incêndio').setData({
                'type': 'FeatureCollection',
                'features': features["incêndio"]

            });
        });
    }

    prepareClickableTOs() {
        let that = this;
        let this_function = function (e) {
            location.replace('/' + e.features[0].properties.to_id);
        };
        let cursor_enter_function = function () {
            that.mapbox_map.getCanvas().style.cursor = 'pointer'
        }
        let cursor_leave_function = function () {
            that.mapbox_map.getCanvas().style.cursor = ''
        }
        this.mapbox_map.on('click', 'layer_icons_Incêndio', this_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_Incêndio', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_Incêndio', cursor_leave_function);
    }

    getPOIsFeatures(callback) {
        let that = this;
        if (this.theater_of_operations_id == 0) {
            axios.get("/pois_info")
                .then(function (response) {
                    let geral_features = [];
                    let pc_features = [];
                    let zcap_features = [];
                    let zcr_features = [];
                    let logistica_features = [];
                    let pma_features = [];
                    let antena_features = [];
                    let satelite_features = [];
                    response.data.forEach(poi => {
                        if (poi["symbol"] == "POI Geral") {
                            geral_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi["name"],
                                    'poi_id': poi["id"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi["long"], poi["lat"]]
                                }
                            });
                        } else if (poi["symbol"] == "PC") {
                            pc_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi["name"],
                                    'poi_id': poi["id"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi["long"], poi["lat"]]
                                }
                            });
                        } else if (poi["symbol"] == "ZCAP") {
                            zcap_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi["name"],
                                    'poi_id': poi["id"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi["long"], poi["lat"]]
                                }
                            });
                        } else if (poi["symbol"] == "ZCR") {
                            zcr_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi["name"],
                                    'poi_id': poi["id"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi["long"], poi["lat"]]
                                }
                            });
                        } else if (poi["symbol"] == "Logística") {
                            logistica_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi["name"],
                                    'poi_id': poi["id"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi["long"], poi["lat"]]
                                }
                            });
                        } else if (poi["symbol"] == "PMA") {
                            pma_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi["name"],
                                    'poi_id': poi["id"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi["long"], poi["lat"]]
                                }
                            });
                        } else if (poi["symbol"] == "Antena") {
                            antena_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi["name"],
                                    'poi_id': poi["id"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi["long"], poi["lat"]]
                                }
                            });
                        } else if (poi["symbol"] == "Satélite") {
                            satelite_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi["name"],
                                    'poi_id': poi["id"],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi["long"], poi["lat"]]
                                }
                            });
                        }
                    });
                    callback({
                        "geral": geral_features,
                        "pc": pc_features,
                        "zcap": zcap_features,
                        "zcr": zcr_features,
                        "logistica": logistica_features,
                        "pma": pma_features,
                        "antena": antena_features,
                        "satelite": satelite_features,
                    });
                });

        } else {
            axios.get("/" + this.theater_of_operations_id + "/getPOIs")
                .then(function (response) {
                    let geral_features = [];
                    let pc_features = [];
                    let zcap_features = [];
                    let zcr_features = [];
                    let logistica_features = [];
                    let pma_features = [];
                    let antena_features = [];
                    let satelite_features = [];
                    response.data.forEach(poi => {
                        if (poi[3] == "POI Geral") {
                            geral_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi[0],
                                    'poi_id': poi[6],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi[5], poi[4]]
                                }
                            });
                        } else if (poi[3] == "PC") {
                            pc_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi[0],
                                    'poi_id': poi[6],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi[5], poi[4]]
                                }
                            });
                        } else if (poi[3] == "ZCAP") {
                            zcap_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi[0],
                                    'poi_id': poi[6],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi[5], poi[4]]
                                }
                            });
                        } else if (poi[3] == "ZCR") {
                            zcr_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi[0],
                                    'poi_id': poi[6],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi[5], poi[4]]
                                }
                            });
                        } else if (poi[3] == "Logística") {
                            logistica_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi[0],
                                    'poi_id': poi[6],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi[5], poi[4]]
                                }
                            });
                        } else if (poi[3] == "PMA") {
                            pma_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi[0],
                                    'poi_id': poi[6],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi[5], poi[4]]
                                }
                            });
                        } else if (poi[3] == "Antena") {
                            antena_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi[0],
                                    'poi_id': poi[6],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi[5], poi[4]]
                                }
                            });
                        } else if (poi[3] == "Satélite") {
                            satelite_features.push({
                                'type': 'Feature',
                                'properties': {
                                    'name': poi[0],
                                    'poi_id': poi[6],
                                },
                                'geometry': {
                                    'type': 'Point',
                                    'coordinates': [poi[5], poi[4]]
                                }
                            });
                        }
                    });
                    callback({
                        "geral": geral_features,
                        "pc": pc_features,
                        "zcap": zcap_features,
                        "zcr": zcr_features,
                        "logistica": logistica_features,
                        "pma": pma_features,
                        "antena": antena_features,
                        "satelite": satelite_features,
                    });
                });

        }
    }

    createPOIs(addSources = true, createLayer = true) {
        let that = this;
        this.getPOIsFeatures(function (features) {
            if (addSources) {
                that.mapbox_map.addSource('icons_POIGeral', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["geral"]
                    }
                });
                that.mapbox_map.addSource('icons_PC', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["pc"]
                    }
                });
                that.mapbox_map.addSource('icons_ZCAP', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["zcap"]
                    }
                });
                that.mapbox_map.addSource('icons_ZCR', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["zcr"]
                    }
                });
                that.mapbox_map.addSource('icons_Logistica', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["logistica"]
                    }
                });
                that.mapbox_map.addSource('icons_PMA', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["pma"]
                    }
                });
                that.mapbox_map.addSource('icons_Antena', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["antena"]
                    }
                });
                that.mapbox_map.addSource('icons_Satelite', {
                    'type': 'geojson',
                    'data': {
                        'type': 'FeatureCollection',
                        'features': features["satelite"]
                    }
                });
            }
            if (createLayer) {
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_POIGeral',
                    'type': 'symbol',
                    'source': 'icons_POIGeral',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#ffff00",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_POIGeral',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_PC',
                    'type': 'symbol',
                    'source': 'icons_PC',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#555500",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_PC',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_ZCAP',
                    'type': 'symbol',
                    'source': 'icons_ZCAP',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#555500",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_ZCAP',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_ZCR',
                    'type': 'symbol',
                    'source': 'icons_ZCR',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#555500",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_ZCR',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_Logistica',
                    'type': 'symbol',
                    'source': 'icons_Logistica',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#555500",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_Logistica',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_PMA',
                    'type': 'symbol',
                    'source': 'icons_PMA',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#555500",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_PMA',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_Antena',
                    'type': 'symbol',
                    'source': 'icons_Antena',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#555500",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_Antena',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
                that.mapbox_map.addLayer({
                    'id': 'layer_icons_Satelite',
                    'type': 'symbol',
                    'source': 'icons_Satelite',
                    'paint': {
                        'text-halo-width': 2,
                        'text-halo-color': "#ffffff",
                        "text-color": "#555500",
                    },
                    'layout': {

                        'text-anchor': 'bottom',
                        'text-field': '{name}',

                        'icon-image': 'icon_Satelite',
                        'icon-size': ['interpolate', ['linear'],
                            ['zoom'], 5, 0.5, 7, 0.75, 13, 1
                        ],
                        'icon-allow-overlap': true,
                        'text-allow-overlap': true,
                        'icon-ignore-placement': true,
                        'text-ignore-placement': true,
                    }
                });
            }
        });
    }

    updatePOIs() {
        let that = this;
        let features = this.getPOIsFeatures(function (features) {
            that.mapbox_map.getSource('icons_POIGeral').setData({
                'type': 'FeatureCollection',
                'features': features["geral"]

            });
            that.mapbox_map.getSource('icons_PC').setData({
                'type': 'FeatureCollection',
                'features': features["pc"]

            });
            that.mapbox_map.getSource('icons_ZCAP').setData({
                'type': 'FeatureCollection',
                'features': features["zcap"]

            });
            that.mapbox_map.getSource('icons_ZCR').setData({
                'type': 'FeatureCollection',
                'features': features["zcr"]

            });
            that.mapbox_map.getSource('icons_Logistica').setData({
                'type': 'FeatureCollection',
                'features': features["logistica"]

            });
            that.mapbox_map.getSource('icons_PMA').setData({
                'type': 'FeatureCollection',
                'features': features["pma"]

            });
            that.mapbox_map.getSource('icons_Antena').setData({
                'type': 'FeatureCollection',
                'features': features["antena"]

            });
            that.mapbox_map.getSource('icons_Satelite').setData({
                'type': 'FeatureCollection',
                'features': features["satelite"]

            });
        });
    }

    prepareClickablePOIs() {
        let that = this;
        let this_function = function (e) {
            location.replace('/poi/' + e.features[0].properties.poi_id);
        }
        let cursor_enter_function = function () {
            that.mapbox_map.getCanvas().style.cursor = 'pointer'
        }
        let cursor_leave_function = function () {
            that.mapbox_map.getCanvas().style.cursor = ''
        }
        this.mapbox_map.on('click', 'layer_icons_POIGeral', this_function);
        this.mapbox_map.on('click', 'layer_icons_PC', this_function);
        this.mapbox_map.on('click', 'layer_icons_ZCAP', this_function);
        this.mapbox_map.on('click', 'layer_icons_ZCR', this_function);
        this.mapbox_map.on('click', 'layer_icons_Logistica', this_function);
        this.mapbox_map.on('click', 'layer_icons_PMA', this_function);
        this.mapbox_map.on('click', 'layer_icons_Antena', this_function);
        this.mapbox_map.on('click', 'layer_icons_Satelite', this_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_POIGeral', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_POIGeral', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_PC', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_PC', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_ZCAP', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_ZCAP', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_ZCR', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_ZCR', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_Logistica', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_Logistica', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_PMA', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_PMA', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_Antena', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_Antena', cursor_leave_function);
        this.mapbox_map.on('mouseenter', 'layer_icons_Satelite', cursor_enter_function);
        this.mapbox_map.on('mouseleave', 'layer_icons_Satelite', cursor_leave_function);
    }

    zoomOn(lat, long, zoom) {
        this.mapbox_map.flyTo({
            center: [long, lat],
            zoom: zoom,
            essential: true
        });
    }

    recenter() {
        this.zoomOn(this.center_lat, this.center_long, this.center_zoom);
    }
}

window.Map = Map;
