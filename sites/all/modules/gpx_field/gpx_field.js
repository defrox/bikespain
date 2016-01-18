(function ($) {

  /**
   * Generates charts and map to each GPX field element.
   */
  Drupal.behaviors.GPX = {
    attach: function (context, settings) {
      // Level chart (level diagram).
      $.each(settings.gpxField.gpxFieldLevelCharts, function(index, value) {
        var $element = $('#' + value.element);
        $element.once('gpx-generated', function() {
          value.settings.xAxis.labels = {
            // Show only first and last label.
            formatter: function() {
              if (this.isFirst || this.isLast) {
                return this.value;
              }
              else {
                return null;
              }
            }
          };
          $element.highcharts(value.settings);
        });
      });

      // Difficulty chart (levels).
      $.each(settings.gpxField.gpxFieldDifficultyCharts, function(index, value) {
        var $element = $('#' + value.element);
        $element.once('gpx-generated', function() {
          $element.highcharts(value.settings);
        });
      });

      // Show coordinates in google map.
      $.each(settings.gpxField.gpxMaps, function(index, mapvalue) {
        var $element = $('#' + mapvalue.element);
        $element.once('gpx-generated', function() {
          var coordinates = [];
          var lat_min = 0;
          var lat_max = 0;
          var lng_min = 0;
          var lng_max = 0;

          // Get the coordinate points.
          $.each(mapvalue.points, function(i, point) {
            if (i == 0) {
              lat_min = point.lat;
              lat_max = point.lat;
              lng_min = point.lon;
              lng_max = point.lon;
            }
            else {
              lat_min = Math.min(point.lat, lat_min);
              lat_max = Math.max(point.lat, lat_max);
              lng_min = Math.min(point.lon, lng_min);
              lng_max = Math.max(point.lat, lng_max);
            }
            coordinates.push(new google.maps.LatLng(point.lat, point.lon));
          });

          var options = {
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.TERRAIN
          };
          // Google maps doesn't generate the map if I use $element dom elem.
          var map = new google.maps.Map(document.getElementById(mapvalue.element), options);
          var polyline = new google.maps.Polyline({
            path: coordinates,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
          });

          // Auto zoom and center.
          polyline.setMap(map);
          var bounds = new google.maps.LatLngBounds();
          for (var i = 0; i < coordinates.length; i++) {
            bounds.extend(coordinates[i]);
          }
          bounds.getCenter();
          map.fitBounds(bounds);
        });
      });
    }
  };
})(jQuery);
