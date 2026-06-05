import { Component, OnInit, Input, OnChanges, SimpleChanges } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PostComponent } from '../post/post.component';
import { PublicacaoService, Publicacao } from '../../services/publicacao.service';
import { ComentarioService } from '../../services/comentario.service';

@Component({
  selector: 'app-feed',
  standalone: true,
  imports: [CommonModule, PostComponent],
  templateUrl: './feed.component.html',
  styleUrl: './feed.component.scss'
})
export class FeedComponent implements OnInit, OnChanges {
  @Input() reloadTrigger = 0;

  publicacoes: Publicacao[] = [];
  carregando = true;
  erro: string | null = null;

  constructor(
    private publicacaoService: PublicacaoService,
    private comentarioService: ComentarioService
  ) {}

  ngOnInit(): void { this.carregar(); }

  ngOnChanges(changes: SimpleChanges): void {
    if (!changes['reloadTrigger']?.firstChange) {
      this.carregar();
    }
  }

  carregar(): void {
    this.carregando = true;
    this.erro = null;
    this.publicacaoService.listar().subscribe({
      next: res => { this.publicacoes = res.publicacoes; this.carregando = false; },
      error: () => { this.erro = 'Não foi possível carregar as publicações.'; this.carregando = false; }
    });
  }

  handleAddComment(event: { publicacaoId: number; texto: string }): void {
    this.comentarioService.criar(event.publicacaoId, { texto: event.texto }).subscribe({
      next: () => this.carregar(),
      error: err => console.error('Erro ao comentar:', err)
    });
  }
}
