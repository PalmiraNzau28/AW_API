import { Component, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Search, X } from 'lucide-angular';
import { UtilizadorResultado, UtilizadorService } from '../../services/utilizador.service';
import { environment } from '../../../environments/environment';
import { SeguidorService } from '../../services/seguidor.service';
import { RippleDirective } from '../ripple/ripple.directive';
import { Subject, debounceTime, distinctUntilChanged, switchMap, takeUntil, tap, of, catchError } from 'rxjs';

@Component({
  selector: 'app-search',
  standalone: true,
  imports: [CommonModule, FormsModule, LucideAngularModule, RippleDirective],
  templateUrl: './search.component.html',
  styleUrl: './search.component.scss'
})
export class SearchComponent implements OnDestroy {
  readonly SearchIcon = Search;
  readonly XIcon = X;

  searchQuery = '';
  resultados: UtilizadorResultado[] = [];
  carregando = false;
  erro: string | null = null;
  private readonly search$ = new Subject<string>();
  private readonly destroy$ = new Subject<void>();

  constructor(
    private utilizadorService: UtilizadorService,
    private seguidorService: SeguidorService
  ) {
    this.search$
      .pipe(
        debounceTime(350),
        distinctUntilChanged(),
        tap(termo => {
          this.erro = null;
          this.carregando = termo.length > 0;
          if (termo.length === 0) this.resultados = [];
        }),
        switchMap(termo => {
          if (termo.length === 0) return of({ utilizadores: [] });
          return this.utilizadorService.pesquisar(termo).pipe(
            catchError(() => {
              this.erro = 'Não foi possível pesquisar utilizadores.';
              return of({ utilizadores: [] });
            })
          );
        }),
        takeUntil(this.destroy$)
      )
      .subscribe(res => {
        this.resultados = res.utilizadores;
        this.carregando = false;
      });
  }

  pesquisar(): void {
    const termo = this.searchQuery.trim();

    this.search$.next(termo);
  }

  get semResultados(): boolean {
    return this.searchQuery.length > 0 && this.resultados.length === 0;
  }

  avatarUrl(utilizador: UtilizadorResultado): string {
    const foto = utilizador.foto_perfil;
    if (!foto) return 'https://ui-avatars.com/api/?name=' + utilizador.username;
    if (foto.startsWith('http')) return foto;
    return `${environment.apiUrl.replace('/api', '')}/storage/${foto}`;
  }

  toggleSeguir(utilizador: UtilizadorResultado): void {
    const pedido = utilizador.seguindo
      ? this.seguidorService.deixarSeguir(utilizador.id)
      : this.seguidorService.seguir(utilizador.id);

    pedido.subscribe({
      next: () => utilizador.seguindo = !utilizador.seguindo,
      error: () => this.erro = 'Não foi possível actualizar o seguimento.'
    });
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }
}
