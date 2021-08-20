// import { gsap } from 'gsap';
import { map, lerp, calcWinsize, getMousePos } from './lib/utlis';
import { EventEmitter } from 'events';

// Calculate the viewport size
let winsize = calcWinsize();
window.addEventListener('resize', () => {
  winsize = calcWinsize();
});

// Track the mouse position
let mouse = {x: 0, y: 0};
window.addEventListener('mousemove', ev => mouse = getMousePos(ev));

export default class Cursor extends EventEmitter {
  constructor(el) {
    super();
    if(el === null) return;

    this.DOM = {el: el};
    this.DOM.el.style.opacity = 0;
    this.DOM.circleInner = this.DOM.el.querySelector('.cursor__inner');

    this.bounds = this.DOM.el.getBoundingClientRect();

    this.renderedStyles = {
      tx: {previous: 0, current: 0, amt: 0.2},
      ty: {previous: 0, current: 0, amt: 0.2},
      radius: {previous: 5, current: 5, amt: 0.2}
    };

    this.listen();

    this.onMouseMoveEv = () => {
      this.renderedStyles.tx.previous = this.renderedStyles.tx.current = mouse.x - this.bounds.width/2;
      this.renderedStyles.ty.previous = this.renderedStyles.ty.current = mouse.y - this.bounds.height/2;
      gsap.to(this.DOM.el, {duration: 0.9, ease: 'Power3.easeOut', opacity: 1});
      requestAnimationFrame(() => this.render());
      window.removeEventListener('mousemove', this.onMouseMoveEv);
    };
    window.addEventListener('mousemove', this.onMouseMoveEv);
  }
  render() {
    this.renderedStyles['tx'].current = mouse.x - this.bounds.width/2;
    this.renderedStyles['ty'].current = mouse.y - this.bounds.height/2;

    for (const key in this.renderedStyles ) {
      this.renderedStyles[key].previous = lerp(this.renderedStyles[key].previous, this.renderedStyles[key].current, this.renderedStyles[key].amt);
    }

    this.DOM.el.style.transform = `translateX(${(this.renderedStyles['tx'].previous)}px) translateY(${this.renderedStyles['ty'].previous}px)`;
    this.DOM.circleInner.setAttribute('r', this.renderedStyles['radius'].previous);

    requestAnimationFrame(() => this.render());
  }

  enter() {
    this.renderedStyles['radius'].current = 40;
    gsap.to(this.DOM.el, {duration: 0.8, ease: 'Power3.easeOut', opacity: 0.4});
  }
  leave() {
    this.renderedStyles['radius'].current = 5;
    gsap.to(this.DOM.el, {duration: 0.8, ease: 'Power3.easeOut', opacity: 1});
  }
  listen() {
    this.on('enter', () => this.enter());
    this.on('leave', () => this.leave());
  }
}
