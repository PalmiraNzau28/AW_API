import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Publicacao {
  id: number;
  utilizador_id: number;
  texto?: string;
  legenda?: string;
  imagem?: string;
  video?: string;
  created_at: string;
  updated_at: string;
  utilizador?: {
    id: number;
    username: string;
    nome: string;
    foto_perfil?: string;
  };
}

export interface PublicacoesResponse {
  publicacoes: Publicacao[];
}

export interface PublicacaoResponse {
  publicacao: Publicacao;
  message?: string;
}

@Injectable({ providedIn: 'root' })
export class PublicacaoService {
  private readonly baseUrl = `${environment.apiUrl}/publicacoes`;

  constructor(private http: HttpClient) {}

  listar(): Observable<PublicacoesResponse> {
    return this.http.get<PublicacoesResponse>(this.baseUrl);
  }

  ver(id: number): Observable<PublicacaoResponse> {
    return this.http.get<PublicacaoResponse>(`${this.baseUrl}/${id}`);
  }

  criar(dados: FormData): Observable<PublicacaoResponse> {
    return this.http.post<PublicacaoResponse>(this.baseUrl, dados);
  }

  atualizar(id: number, dados: FormData): Observable<PublicacaoResponse> {
    // Laravel não lê FormData em PUT, usamos POST com _method=PUT
    dados.append('_method', 'PUT');
    return this.http.post<PublicacaoResponse>(`${this.baseUrl}/${id}`, dados);
  }

  eliminar(id: number): Observable<{ message: string }> {
    return this.http.delete<{ message: string }>(`${this.baseUrl}/${id}`);
  }
}
