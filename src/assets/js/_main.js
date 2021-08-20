// import { gsap } from "gsap";
// gsap.registerPlugin();
// import Foundation from 'foundation-sites';

// If you want to pick and choose which modules to include, comment out the above and uncomment
// the line below
import './lib/foundation-explicit-pieces';
// import './lib/jquery.vmap.min'
// import './lib/maps/jquery.vmap.world'

import 'jsvectormap'
import 'jsvectormap/dist/maps/world'


export class Main {

  constructor() {
    //define variables

    $(document).foundation();
    this.getData();
    this.initMap();
  }
  initMap(){
    const map = new jsVectorMap({
      selector: '#map',
      map: 'world',
      regionsSelectable: true,
      showTooltip: true,
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
              "Free": "#4bdc77",
              "Closed": "#ff5566"
            },
            values: {
              GB: "Free",
              MX: "Closed",
              LY: "Free",
            },
          },
        ]
      },
    })
  }
  getData(){
    let myHeaders = new Headers();
    myHeaders.append("Authorization", "Bearer JDiGZOvozuDN6G2G2k0HHYKiphyO5AQg");

    let requestOptions = {
      origin: 'http://localhost:1234',
      method: 'GET',
      headers: myHeaders,
    };

    fetch("https://www.kayak.com/charm/horizon/uiapi/seo/marketing/travelrestrictions/CountriesTravelRestrictionsAction", requestOptions)
      .then(response => response.text())
      .then(result => console.log(result))
      .catch(error => console.log('error', error));
  }
}
