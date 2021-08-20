// import { gsap } from "gsap";
// gsap.registerPlugin();
// import Foundation from 'foundation-sites';

// If you want to pick and choose which modules to include, comment out the above and uncomment
// the line below
import './lib/foundation-explicit-pieces';

export class Main {

  constructor() {
    //define variables

    $(document).foundation();
    this.getData();
  }
  getData(){
    let myHeaders = new Headers();
    myHeaders.append("Authorization", "Bearer LXUMFibZNyO5ttA2qcGYvpT7uBZ6");

    let requestOptions = {
      method: 'GET',
      headers: myHeaders,
      redirect: 'follow'
    };

    fetch("https://test.api.amadeus.com/v1/duty-of-care/diseases/covid19-area-report?countryCode=UA", requestOptions)
      .then(response => response.text())
      .then(result => console.log(result))
      .catch(error => console.log('error', error));
  }
}
