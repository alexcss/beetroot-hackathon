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
    this.map;

    $(document).foundation();

    this.getData();
    this.switchThemeColor();
    this.initSearch();
    this.initInfoWindow();
    this.initCursor();
  }

  searchByData(term) {
    const countries = this.data.filter((country) => {
      const regex = new RegExp(term, "gi");
      return country.countryName.match(regex);
    });
    if (!term) return;
    const code = countries[0]['countryCode'];

    this.map.clearSelectedRegions();
    this.setInfoData(code);
    this.map.setSelected('regions', [code]);

  }

  initCursor() {
    const cursor = new Cursor(document.querySelector('[data-cursor]'));
    [...document.querySelectorAll('a, button, label, .jvm-region')].forEach(el => {
      el.addEventListener('mouseenter', () => cursor.emit('enter'));
      el.addEventListener('mouseleave', () => cursor.emit('leave'));
    });
  }

  initInfoWindow() {
    const _self = this;
    this.infoWindow = new Vue({
      delimiters: ['${', '}'],
      el: '#info',
      data: {
        closed: true,
        data: {},
      },
      methods: {
        close(){
          this.closed = true;
          _self.map.clearSelectedRegions();
        }
      },
      mounted() {

      },
    });

  }

  setInfoData(countryCode,) {
    this.infoWindow.$data.closed = false;

    let data = this.data.find((country) => country['countryCode'] == countryCode);
    this.infoWindow.$data.data = data;
  }

  initMap(data) {
    let colors = {}
    const _self = this;

    data.forEach((country) => {
      colors[country['countryCode']] = country['borderStatus'];
    })


    this.map = new jsVectorMap({
      selector: '#map',
      map: 'world',
      regionsSelectable: true,
      showTooltip: true,
      zoomOnScroll: false,
      onRegionSelected: function (index, isSelected, selectedRegions) {
        _self.map.clearSelectedRegions();

        _self.map.setSelected('regions', [index]);

        _self.setInfoData(index);

      },
      onRegionTooltipShow: function (tooltip, code) {
        tooltip.selector.innerHTML = '<b>' + tooltip.text() + '</b>';
      },
      onLoaded(map) {
        // This is a great opportunity and useful use-case to handle the reszing of the window.
        _self.initCursor();
        window.addEventListener('resize', () => {
          map.updateSize();
        })
      },
      // -------- Region and label style --------
      regionStyle: {
        selected: {
          fill: "#2d98ef"
        }
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

    let requestOptions = {
      method: 'GET'
    };

    fetch(`${themeUrl}/data.json`, requestOptions)
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

  initSearch() {
    let searchInput = document.querySelector('[data-search-input]');
    searchInput.addEventListener('submit', (e) => {
      e.preventDefault();
      if ( e.target.search.value.trim() == '') return;

      this.searchByData(e.target.search.value.trim());

      document.getElementById('map').scrollIntoView();
    });
  }
}
