import { Component, OnInit, Input, OnChanges, OnDestroy, SimpleChanges } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PostComponent } from '../post/post.component';
import { PublicacaoService, Publicacao } from '../../services/publicacao.service';
import { ComentarioService } from '../../services/comentario.service';
import { interval, Subscription } from 'rxjs';

@Component({
  selector: 'app-feed',
  standalone: true,
  imports: [CommonModule, PostComponent],
  templateUrl: './feed.component.html',
  styleUrl: './feed.component.scss'
})
export class FeedComponent implements OnInit, OnChanges, OnDestroy {
  @Input() reloadTrigger = 0;

  publicacoes: Publicacao[] = [];
  carregando = true;
  erro: string | null = null;
  pagina = 1;
  temMais = false;
  private realtimeSub?: Subscription;

  constructor(
    private publicacaoService: PublicacaoService,
    private comentarioService: ComentarioService
  ) {}

  ngOnInit(): void {
    this.carregar();
    this.realtimeSub = interval(8000).subscribe(() => this.sincronizarTempoReal());
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (!changes['reloadTrigger']?.firstChange) {
      this.carregar();
    }
  }

  carregar(): void {
    this.carregando = true;
    this.erro = null;
    this.pagina = 1;
    this.publicacaoService.listar(this.pagina).subscribe({
      next: res => {
        this.publicacoes = res.publicacoes;
        this.temMais = res.meta?.has_more ?? false;
        this.carregando = false;
      },
      error: () => { this.erro = 'Não foi possível carregar as publicações.'; this.carregando = false; }
    });
  }

  carregarMais(): void {
    if (this.carregando || !this.temMais) return;

    this.carregando = true;
    this.publicacaoService.listar(this.pagina + 1).subscribe({
      next: res => {
        this.pagina = res.meta?.current_page ?? this.pagina + 1;
        this.temMais = res.meta?.has_more ?? false;
        this.publicacoes = [...this.publicacoes, ...res.publicacoes];
        this.carregando = false;
      },
      error: () => {
        this.erro = 'Não foi possível carregar mais publicações.';
        this.carregando = false;
      }
    });
  }

  sincronizarTempoReal(): void {
    this.publicacaoService.listar(1).subscribe({
      next: res => {
        const idsDaPrimeiraPagina = new Set(res.publicacoes.map(publicacao => publicacao.id));
        const restantes = this.publicacoes.filter(publicacao => !idsDaPrimeiraPagina.has(publicacao.id));

        this.publicacoes = [...res.publicacoes, ...restantes].slice(0, Math.max(this.publicacoes.length, res.publicacoes.length));
        this.temMais = res.meta?.has_more ?? this.temMais;
      },
      error: () => {}
    });
  }

  handleAddComment(event: { publicacaoId: number; texto: string }): void {
    this.comentarioService.criar(event.publicacaoId, { texto: event.texto }).subscribe({
      next: () => this.carregar(),
      error: err => console.error('Erro ao comentar:', err)
    });
  }

  removerPublicacao(id: number): void {
    this.publicacoes = this.publicacoes.filter(publicacao => publicacao.id !== id);
  }

  ngOnDestroy(): void {
    this.realtimeSub?.unsubscribe();
  }
}
