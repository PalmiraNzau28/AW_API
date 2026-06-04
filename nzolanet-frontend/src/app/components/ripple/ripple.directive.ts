import { Directive, ElementRef, HostListener, Renderer2 } from '@angular/core';

@Directive({
  selector: '[appRipple]',
  standalone: true,
  host: { 'style': 'position:relative; overflow:hidden; cursor:pointer;' }
})
export class RippleDirective {
  constructor(private el: ElementRef, private renderer: Renderer2) {}

  @HostListener('mousedown', ['$event'])
  createRipple(event: MouseEvent) {
    const host: HTMLElement = this.el.nativeElement;
    const rect = host.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    const size = Math.max(rect.width, rect.height) * 2;

    const span: HTMLSpanElement = this.renderer.createElement('span');
    this.renderer.setStyle(span, 'position', 'absolute');
    this.renderer.setStyle(span, 'border-radius', '50%');
    this.renderer.setStyle(span, 'background', 'currentColor');
    this.renderer.setStyle(span, 'opacity', '0.3');
    this.renderer.setStyle(span, 'pointer-events', 'none');
    this.renderer.setStyle(span, 'left', `${x}px`);
    this.renderer.setStyle(span, 'top', `${y}px`);
    this.renderer.setStyle(span, 'width', `${size}px`);
    this.renderer.setStyle(span, 'height', `${size}px`);
    this.renderer.setStyle(span, 'transform', 'translate(-50%, -50%) scale(0)');
    this.renderer.addClass(span, 'animate-ripple');
    this.renderer.appendChild(host, span);

    setTimeout(() => {
      if (host.contains(span)) {
        this.renderer.removeChild(host, span);
      }
    }, 600);
  }
}
