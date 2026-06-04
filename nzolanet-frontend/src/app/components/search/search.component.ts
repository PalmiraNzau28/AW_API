import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, Search, X } from 'lucide-angular';

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
  resultados: { id: string; username: string; nome: string }[] = [];

  get semResultados(): boolean {
    return this.searchQuery.length > 0 && this.resultados.length === 0;
  }
}
