// import { gsap } from "gsap";
// gsap.registerPlugin();
// import Foundation from 'foundation-sites';

// If you want to pick and choose which modules to include, comment out the above and uncomment
// the line below
import './lib/foundation-explicit-pieces';

import 'jsvectormap'
import 'jsvectormap/dist/maps/world'

import Cursor from './cursor';

export class Main {

  constructor() {
    //define variables
    this.data = {};
    this.infoWindow;

    $(document).foundation();

    this.getData();
    this.switchThemeColor();
    this.search();
    this.initInfoWindow();
    this.initCursor();
  }

  initCursor() {
    const cursor = new Cursor(document.querySelector('[data-cursor]'));
    [...document.querySelectorAll('a, button, label, .jvm-region')].forEach(el => {
      el.addEventListener('mouseenter', () => cursor.emit('enter'));
      el.addEventListener('mouseleave', () => cursor.emit('leave'));
    });
  }

  initInfoWindow() {

    this.infoWindow = new Vue({
      delimiters: ['${', '}'],
      el: '#info',
      data: {
        closed: true,
        data: {},
      },
      methods: {},
      mounted() {
        console.log('vuse');
      },
    });


    // this.infoWindow.$data.data.countryName = 'UK';

  }

  setInfoData(countryCode,) {
    console.log(countryCode);
    this.infoWindow.$data.closed = false;

    let data = this.data.find((country) => country['countryCode'] == countryCode);
    this.infoWindow.$data.data = data;
  }

  initMap(data) {
    let colors = {}
    const _self = this;

    data.forEach((country) => {
      // console.log(country);
      colors[country['countryCode']] = country['borderStatus'];
    })

    console.log(data[0]);


    const map = new jsVectorMap({
      selector: '#map',
      map: 'world',
      regionsSelectable: true,
      showTooltip: true,
      zoomOnScroll: false,
      onRegionSelected: function (index, isSelected, selectedRegions) {
        map.clearSelectedRegions();
        // console.log(index, isSelected, selectedRegions);
        map.setSelected('regions', [index]);

        _self.setInfoData(index);

      },
      onRegionTooltipShow: function (tooltip, code) {
        tooltip.selector.innerHTML = tooltip.text() + ' <b>(Hello)</b>'
      },
      onLoaded(map) {
        // This is a great opportunity and useful use-case to handle the reszing of the window.
        _self.initCursor();
        window.addEventListener('resize', () => {
          map.updateSize();
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
    let buttonSwitcher = document.querySelector('[data-toggle-theme-color]');
    buttonSwitcher.addEventListener('click', () => {
      document.body.classList.toggle('switch-theme');
      if (document.cookie.indexOf('zg-switch-theme-color') == -1) {
        document.cookie = 'zg-switch-theme-color=1; path=/';
      } else {
        document.cookie = 'zg-switch-theme-color=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
      }
    })
  }

  search() {
    let searchInput = document.querySelector('[data-search-input]');
    searchInput.addEventListener('keyup', () => {
    });
  }
}
