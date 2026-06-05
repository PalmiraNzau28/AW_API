import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Heart, MessageCircle, MoreHorizontal } from 'lucide-angular';
import { Publicacao } from '../../services/publicacao.service';
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

  readonly HeartIcon = Heart;
  readonly MessageCircleIcon = MessageCircle;
  readonly MoreHorizontalIcon = MoreHorizontal;

  commentText = '';

  get imagemUrl(): string {
    if (!this.publicacao.imagem) return '';
    // Se já é URL absoluta, devolve como está; senão prefixed com storage URL
    if (this.publicacao.imagem.startsWith('http')) return this.publicacao.imagem;
    return `${environment.apiUrl.replace('/api', '')}/storage/${this.publicacao.imagem}`;
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

  handleSubmitComment(): void {
    if (this.commentText.trim()) {
      this.addComment.emit({ publicacaoId: this.publicacao.id, texto: this.commentText });
      this.commentText = '';
    }
  }
}
