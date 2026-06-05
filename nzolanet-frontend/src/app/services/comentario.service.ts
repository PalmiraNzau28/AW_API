import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Comentario {
  id: number;
  publicacao_id: number;
  utilizador_id: number;
  texto: string;
  created_at: string;
  autor_nome?: string;
  autor_foto?: string;
  utilizador?: {
    id: number;
    nome?: string;
    username: string;
    foto_perfil?: string;
  };
}

export interface ComentariosResponse {
  comentarios: Comentario[];
}

export interface ComentarioResponse {
  comentario: Comentario;
  message?: string;
}

@Injectable({ providedIn: 'root' })
export class ComentarioService {
  private readonly baseUrl = `${environment.apiUrl}/publicacoes`;

  constructor(private http: HttpClient) {}

  listar(publicacaoId: number): Observable<ComentariosResponse> {
    return this.http.get<ComentariosResponse>(`${this.baseUrl}/${publicacaoId}/comentarios`);
  }

  criar(publicacaoId: number, dados: { texto: string }): Observable<ComentarioResponse> {
    return this.http.post<ComentarioResponse>(`${this.baseUrl}/${publicacaoId}/comentarios`, dados);
  }

  atualizar(id: number, dados: { texto: string }): Observable<ComentarioResponse> {
    return this.http.put<ComentarioResponse>(`${environment.apiUrl}/comentarios/${id}`, dados);
  }

  eliminar(id: number): Observable<{ message: string }> {
    return this.http.delete<{ message: string }>(`${environment.apiUrl}/comentarios/${id}`);
  }
}
