// import { gsap } from "gsap";
// gsap.registerPlugin();
// import Foundation from 'foundation-sites';

// If you want to pick and choose which modules to include, comment out the above and uncomment
// the line below
import './lib/foundation-explicit-pieces';

import 'jsvectormap'
import 'jsvectormap/dist/maps/world'

export class Main {

  constructor() {
    //define variables
    this.data = {};

    $(document).foundation();

    this.getData();
  }

  initMap(data) {
    let colors = {}

    data.forEach((country) => {
      console.log(country);
      colors[country['countryCode']] = country['borderStatus'];
    })

    console.log(colors);

    const map = new jsVectorMap({
      selector: '#map',
      map: 'world',
      regionsSelectable: true,
      showTooltip: true,
      zoomOnScroll: false,
      onRegionSelected: function (index, isSelected, selectedRegions) {
        map.clearSelectedRegions();
        console.log(index, isSelected, selectedRegions);
        map.setSelected('regions', [index]);
      },
      onRegionTooltipShow: function (tooltip, code) {
        tooltip.selector.innerHTML = tooltip.text() + ' <b>(Hello)</b>'
      },
      onLoaded(map) {
        // This is a great opportunity and useful use-case to handle the reszing of the window.
        window.addEventListener('resize', () => {
          map.updateSize()
        })
      },
      // -------- Series --------
      series: {
        // ---------- Region series ----------
        regions: [
          {
            attribute: 'fill',
            attributes: {
              // EG: 'red'
            },
            legend: {
              title: 'Country status',
              vertical: true,
            },
            scale: {
              "OPEN": "#4bdc77",
              "CLOSED": "#ff5566",
              "RESTRICTIONS": "#ffac7d",
            },
            values: colors,
          },
        ]
      },
    })
  }

  getData() {
    let headers = new Headers();
    // headers.append("Authorization", "Bearer JDiGZOvozuDN6G2G2k0HHYKiphyO5AQg");
    headers.append('Content-Type', 'application/json');
    headers.append('Accept', 'application/json');

    headers.append('Access-Control-Allow-Origin', '*');
    headers.append('referer', 'www.kayak.com');

    let requestOptions = {
      method: 'GET',
      // headers: headers,
    };

    fetch("/wp-content/themes/zgraya/data.json", requestOptions)
      .then(response => response.json())
      .then(result => {
        this.data = result;
        this.initMap(result);
      })
      .catch(error => console.log('error', error));
  }

  switchThemeColor() {

  }
}
