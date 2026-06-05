import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Search, X } from 'lucide-angular';
import { UtilizadorResultado, UtilizadorService } from '../../services/utilizador.service';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-search',
  standalone: true,
  imports: [CommonModule, FormsModule, LucideAngularModule],
  templateUrl: './search.component.html',
  styleUrl: './search.component.scss'
})
export class SearchComponent {
  readonly SearchIcon = Search;
  readonly XIcon = X;

  searchQuery = '';
  resultados: UtilizadorResultado[] = [];
  carregando = false;
  erro: string | null = null;

  constructor(private utilizadorService: UtilizadorService) {}

  pesquisar(): void {
    const termo = this.searchQuery.trim();

    if (termo.length === 0) {
      this.resultados = [];
      this.erro = null;
      return;
    }

    this.carregando = true;
    this.erro = null;

    this.utilizadorService.pesquisar(termo).subscribe({
      next: res => {
        this.resultados = res.utilizadores;
        this.carregando = false;
      },
      error: () => {
        this.resultados = [];
        this.carregando = false;
        this.erro = 'Não foi possível pesquisar utilizadores.';
      },
    });
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
}
