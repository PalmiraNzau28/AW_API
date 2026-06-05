import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LucideAngularModule, Bell, Heart, MessageCircle, UserPlus } from 'lucide-angular';
import { NotificacaoService, Notificacao } from '../../services/notificacao.service';
import { OnInit } from '@angular/core';

@Component({
  selector: 'app-notifications',
  standalone: true,
  imports: [CommonModule, LucideAngularModule],
  templateUrl: './notifications.component.html',
  styleUrl: './notifications.component.scss'
})
export class NotificationsComponent implements OnInit {
  readonly BellIcon = Bell;
  readonly HeartIcon = Heart;
  readonly MessageIcon = MessageCircle;
  readonly UserPlusIcon = UserPlus;

  notificacoes: Notificacao[] = [];
  carregando = true;
  erro: string | null = null;

  constructor(private notificationService: NotificacaoService) {}

  ngOnInit(): void {
    this.carregar();
  }

  carregar(): void {
    this.carregando = true;
    this.erro = null;

    this.notificationService.listar().subscribe({
      next: res => {
        this.notificacoes = res.notificacoes;
        this.carregando = false;
      },
      error: () => {
        this.notificacoes = [];
        this.carregando = false;
        this.erro = 'Não foi possível carregar as notificações.';
      }
    });
  }

  icone(tipo: string) {
    switch (tipo) {
      case 'curtida':
      case 'like':
        return this.HeartIcon;
      case 'comentario':
      case 'comment':
        return this.MessageIcon;
      default:
        return this.UserPlusIcon;
    }
  }

  marcarComoLida(id: number): void {
    this.notificationService.marcarComoLida(id).subscribe({
      next: () => this.carregar(),
      error: () => this.erro = 'Não foi possível actualizar a notificação.'
    });
  }
}
