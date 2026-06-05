import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface UtilizadorResultado {
  id: number;
  nome: string;
  username: string;
  foto_perfil?: string;
  bio?: string;
  seguindo?: boolean;
}

export interface UtilizadoresResponse {
  utilizadores: UtilizadorResultado[];
}

@Injectable({ providedIn: 'root' })
export class UtilizadorService {
  private readonly baseUrl = `${environment.apiUrl}/utilizadores`;

  constructor(private http: HttpClient) {}

  pesquisar(termo: string): Observable<UtilizadoresResponse> {
    return this.http.get<UtilizadoresResponse>(`${this.baseUrl}/pesquisa`, {
      params: { q: termo }
    });
  }
}
