import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LucideAngularModule, Bell } from 'lucide-angular';

// Nota: A API Nzolanet tem o modelo Notificacao mas ainda não expõe
// a rota GET /api/notificacoes. Este componente aguarda essa rota.

@Component({
  selector: 'app-notifications',
  standalone: true,
  imports: [CommonModule, LucideAngularModule],
  templateUrl: './notifications.component.html',
  styleUrl: './notifications.component.scss'
})
export class NotificationsComponent {
  readonly BellIcon = Bell;
}
