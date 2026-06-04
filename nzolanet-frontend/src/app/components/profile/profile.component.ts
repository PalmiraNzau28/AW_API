import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LucideAngularModule, Settings, Grid2x2 } from 'lucide-angular';
import { RippleDirective } from '../ripple/ripple.directive';
import { AuthService, Utilizador } from '../../services/auth.service';
import { PublicacaoService, Publicacao } from '../../services/publicacao.service';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-profile',
  standalone: true,
  imports: [CommonModule, LucideAngularModule, RippleDirective],
  templateUrl: './profile.component.html',
  styleUrl: './profile.component.scss'
})
export class ProfileComponent implements OnInit {
  readonly SettingsIcon = Settings;
  readonly GridIcon = Grid2x2;

  utilizador: Utilizador | null = null;
  publicacoes: Publicacao[] = [];
  carregando = true;

  constructor(
    private authService: AuthService,
    private publicacaoService: PublicacaoService
  ) {}

  ngOnInit(): void {
    this.utilizador = this.authService.utilizadorActual();
    this.publicacaoService.listar().subscribe({
      next: res => {
        // Filtrar apenas publicações do utilizador actual
        this.publicacoes = res.publicacoes.filter(
          p => p.utilizador_id === this.utilizador?.id
        );
        this.carregando = false;
      },
      error: () => { this.carregando = false; }
    });
  }

  get avatarUrl(): string {
    const foto = this.utilizador?.foto_perfil;
    if (!foto) return 'https://ui-avatars.com/api/?name=' + (this.utilizador?.username ?? 'U') + '&size=150';
    if (foto.startsWith('http')) return foto;
    return `${environment.apiUrl.replace('/api', '')}/storage/${foto}`;
  }

  imagemUrl(pub: Publicacao): string {
    if (!pub.imagem) return '';
    if (pub.imagem.startsWith('http')) return pub.imagem;
    return `${environment.apiUrl.replace('/api', '')}/storage/${pub.imagem}`;
  }
}
