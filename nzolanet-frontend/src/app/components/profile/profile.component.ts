import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Settings, Grid2x2 } from 'lucide-angular';
import { RippleDirective } from '../ripple/ripple.directive';
import { AuthService, Utilizador } from '../../services/auth.service';
import { PublicacaoService, Publicacao } from '../../services/publicacao.service';
import { environment } from '../../../environments/environment';
import { of } from 'rxjs';
import { catchError, switchMap } from 'rxjs';

@Component({
  selector: 'app-profile',
  standalone: true,
  imports: [CommonModule, FormsModule, LucideAngularModule, RippleDirective],
  templateUrl: './profile.component.html',
  styleUrl: './profile.component.scss'
})
export class ProfileComponent implements OnInit {
  readonly SettingsIcon = Settings;
  readonly GridIcon = Grid2x2;

  publicacoes: Publicacao[] = [];
  carregando = true;
  editando = false;
  perfilForm = {
    nome: '',
    username: '',
    bio: '',
  };
  erro: string | null = null;

  constructor(
    private authService: AuthService,
    private publicacaoService: PublicacaoService
  ) {}

  get utilizador(): Utilizador | null {
    return this.authService.utilizadorActual();
  }

  ngOnInit(): void {
    const perfil$ = this.utilizador
      ? of(this.utilizador)
      : this.authService.carregarPerfil().pipe(
          catchError(() => of(null)),
          switchMap(() => of(this.utilizador))
        );

    perfil$.subscribe(() => {
      this.publicacaoService.listar().subscribe({
        next: res => {
          this.publicacoes = res.publicacoes.filter(
            p => p.utilizador_id === this.utilizador?.id
          );
          this.carregando = false;
        },
        error: () => { this.carregando = false; }
      });
    });
  }

  iniciarEdicao(): void {
    this.perfilForm = {
      nome: this.utilizador?.nome ?? '',
      username: this.utilizador?.username ?? '',
      bio: this.utilizador?.bio ?? '',
    };
    this.editando = true;
    this.erro = null;
  }

  guardarPerfil(): void {
    this.authService.atualizarPerfil(this.perfilForm).subscribe({
      next: () => this.editando = false,
      error: err => this.erro = err.error?.message ?? 'Não foi possível actualizar o perfil.'
    });
  }

  atualizarFoto(event: Event): void {
    const foto = (event.target as HTMLInputElement).files?.[0];
    if (!foto) return;

    this.authService.atualizarFotoPerfil(foto).subscribe({
      error: err => this.erro = err.error?.message ?? 'Não foi possível actualizar a foto.'
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

  get publicacoesLabel(): string {
    const total = this.publicacoes.length;
    return total === 1 ? 'publicação' : 'publicações';
  }
}
