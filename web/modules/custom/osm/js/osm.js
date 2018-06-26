(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.osm = {
    attach: function (context, settings) {
      $('#mapid', context).each(function () {
        var $container = $(this);
        // attach the map if is not in the context
        if ($container.data('leaflet') === undefined) {
          $container.data('leaflet', new Drupal.Leaflet(Drupal.DomUtil.get('mapid'), 'mapid', settings.leaflet.markers));
          // Add the leaflet map to our settings object to make it accessible
          //data.lMap = $container.data('leaflet').lMap;
        }
      });
    }
  };

  Drupal.DomUtil = {
    get: function (id) {
      return typeof id === 'string' ? document.getElementById(id) : id;
    }
  }

  Drupal.Leaflet = function (container, mapId, markers) {
    this.container = container;
    this.mapId = mapId;
    this.settings = {
      center : {
        lat : '40.302458',
        lng : '-107.4507555'
      },
      zoom : 4
    };
    this.markers = markers;
    this.initialise();
  };

  Drupal.Leaflet.prototype.initialise = function () {
    // Instantiate a new Leaflet map.
    this.lMap = new L.Map(this.mapId);
    // set map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(this.lMap);
    // set view
    this.lMap.setView(new L.LatLng(this.settings.center.lat, this.settings.center.lng), this.settings.zoom);
    // set markers
    for (var key in this.markers) {
      L.marker([this.markers[key].lat, this.markers[key].lng]).addTo(this.lMap)
        .bindPopup('<h2>' + this.markers[key].title + '</h2> <br>' + this.markers[key].description);
    }
  }


})(jQuery, Drupal);
