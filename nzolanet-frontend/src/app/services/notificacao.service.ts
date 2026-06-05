import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Notificacao {
  id: number;
  tipo: string;
  mensagem: string;
  lida: boolean;
  referencia_id?: number | null;
  created_at?: string;
  utilizador?: {
    id: number;
    nome: string;
    username: string;
    foto_perfil?: string;
  };
}

export interface NotificacoesResponse {
  notificacoes: Notificacao[];
}

@Injectable({ providedIn: 'root' })
export class NotificacaoService {
  private readonly baseUrl = `${environment.apiUrl}/notificacoes`;

  constructor(private http: HttpClient) {}

  listar(): Observable<NotificacoesResponse> {
    return this.http.get<NotificacoesResponse>(this.baseUrl);
  }

  marcarComoLida(id: number): Observable<{ message: string; notificacao: Notificacao }> {
    return this.http.post<{ message: string; notificacao: Notificacao }>(`${this.baseUrl}/${id}/ler`, {});
  }
}
