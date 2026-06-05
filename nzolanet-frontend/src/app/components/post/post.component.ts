import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Heart, MessageCircle, MoreHorizontal, Trash2, Pencil, Check, X } from 'lucide-angular';
import { Publicacao, PublicacaoService } from '../../services/publicacao.service';
import { BazeService } from '../../services/baze.service';
import { Comentario, ComentarioService } from '../../services/comentario.service';
import { AuthService } from '../../services/auth.service';
import { RippleDirective } from '../ripple/ripple.directive';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-post',
  standalone: true,
  imports: [CommonModule, FormsModule, LucideAngularModule, RippleDirective],
  templateUrl: './post.component.html',
  styleUrl: './post.component.scss'
})
export class PostComponent {
  @Input() publicacao!: Publicacao;
  @Output() addComment = new EventEmitter<{ publicacaoId: number; texto: string }>();
  @Output() deleted = new EventEmitter<number>();

  readonly HeartIcon = Heart;
  readonly MessageCircleIcon = MessageCircle;
  readonly MoreHorizontalIcon = MoreHorizontal;
  readonly TrashIcon = Trash2;
  readonly PencilIcon = Pencil;
  readonly CheckIcon = Check;
  readonly XIcon = X;

  commentText = '';
  comentarios: Comentario[] = [];
  comentariosVisiveis = false;
  carregandoComentarios = false;
  editandoPublicacao = false;
  editandoComentarioId: number | null = null;
  editTexto = '';
  editComentarioTexto = '';
  erro: string | null = null;

  constructor(
    private authService: AuthService,
    private bazeService: BazeService,
    private comentarioService: ComentarioService,
    private publicacaoService: PublicacaoService
  ) {}

  mediaUrl(path?: string): string {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    return `${environment.apiUrl.replace('/api', '')}/storage/${path}`;
  }

  get imagemUrl(): string {
    return this.mediaUrl(this.publicacao.imagem);
  }

  get videoUrl(): string {
    return this.mediaUrl(this.publicacao.video);
  }

  get texto(): string {
    return this.publicacao.texto ?? this.publicacao.legenda ?? '';
  }

  get avatarUrl(): string {
    const foto = this.publicacao.utilizador?.foto_perfil;
    if (!foto) return 'https://ui-avatars.com/api/?name=' + (this.publicacao.utilizador?.username ?? 'U');
    if (foto.startsWith('http')) return foto;
    return `${environment.apiUrl.replace('/api', '')}/storage/${foto}`;
  }

  get podeGerirPublicacao(): boolean {
    return this.authService.utilizadorActual()?.id === this.publicacao.utilizador_id;
  }

  get comentariosLabel(): string {
    const total = this.publicacao.comentarios_count ?? 0;
    return `${total} ${total === 1 ? 'comentário' : 'comentários'}`;
  }

  handleSubmitComment(): void {
    if (this.commentText.trim()) {
      const texto = this.commentText.trim();
      this.comentarioService.criar(this.publicacao.id, { texto }).subscribe({
        next: () => {
          this.commentText = '';
          this.publicacao.comentarios_count = (this.publicacao.comentarios_count ?? 0) + 1;
          this.carregarComentarios(true);
        },
        error: err => this.erro = err.error?.message ?? 'Erro ao comentar.'
      });
    }
  }

  toggleBaze(): void {
    const estavaBazado = !!this.publicacao.bazado;
    const totalAtual = this.publicacao.bazes_count ?? 0;

    this.publicacao.bazado = !estavaBazado;
    this.publicacao.bazes_count = estavaBazado
      ? Math.max(totalAtual - 1, 0)
      : totalAtual + 1;

    this.bazeService.toggle(this.publicacao.id).subscribe({
      next: res => {
        this.publicacao.bazado = res.bazado;
        this.publicacao.bazes_count = res.bazes_count;
      },
      error: err => {
        this.publicacao.bazado = estavaBazado;
        this.publicacao.bazes_count = totalAtual;
        this.erro = err.error?.message ?? 'Erro ao actualizar baze.';
      }
    });
  }

  carregarComentarios(forcarAbrir = false): void {
    this.comentariosVisiveis = forcarAbrir || !this.comentariosVisiveis;
    if (!this.comentariosVisiveis) return;

    this.carregandoComentarios = true;
    this.comentarioService.listar(this.publicacao.id).subscribe({
      next: res => {
        this.comentarios = res.comentarios;
        this.carregandoComentarios = false;
      },
      error: () => {
        this.erro = 'Não foi possível carregar os comentários.';
        this.carregandoComentarios = false;
      }
    });
  }

  podeGerirComentario(comentario: Comentario): boolean {
    return this.authService.utilizadorActual()?.id === comentario.utilizador_id;
  }

  iniciarEdicaoPublicacao(): void {
    this.editandoPublicacao = true;
    this.editTexto = this.texto;
  }

  guardarPublicacao(): void {
    const formData = new FormData();
    formData.append('texto', this.editTexto);

    this.publicacaoService.atualizar(this.publicacao.id, formData).subscribe({
      next: res => {
        this.publicacao = { ...this.publicacao, ...res.publicacao };
        this.editandoPublicacao = false;
      },
      error: err => this.erro = err.error?.message ?? 'Erro ao editar publicação.'
    });
  }

  eliminarPublicacao(): void {
    this.publicacaoService.eliminar(this.publicacao.id).subscribe({
      next: () => this.deleted.emit(this.publicacao.id),
      error: err => this.erro = err.error?.message ?? 'Erro ao excluir publicação.'
    });
  }

  iniciarEdicaoComentario(comentario: Comentario): void {
    this.editandoComentarioId = comentario.id;
    this.editComentarioTexto = comentario.texto;
  }

  guardarComentario(comentario: Comentario): void {
    this.comentarioService.atualizar(comentario.id, { texto: this.editComentarioTexto }).subscribe({
      next: () => {
        this.editandoComentarioId = null;
        this.carregarComentarios(true);
      },
      error: err => this.erro = err.error?.message ?? 'Erro ao editar comentário.'
    });
  }

  eliminarComentario(comentario: Comentario): void {
    this.comentarioService.eliminar(comentario.id).subscribe({
      next: () => {
        this.publicacao.comentarios_count = Math.max((this.publicacao.comentarios_count ?? 1) - 1, 0);
        this.carregarComentarios(true);
      },
      error: err => this.erro = err.error?.message ?? 'Erro ao excluir comentário.'
    });
  }
}
