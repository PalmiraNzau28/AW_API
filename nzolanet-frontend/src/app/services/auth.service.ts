import { Injectable, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Utilizador {
  id: number;
  nome: string;
  username: string;
  email: string;
  foto_perfil?: string;
  bio?: string;
}

export interface AuthResponse {
  message: string;
  utilizador: Utilizador;
  token: string;
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  private readonly baseUrl = `${environment.apiUrl}/auth`;

  utilizadorActual = signal<Utilizador | null>(null);
  token = signal<string | null>(localStorage.getItem('token'));

  constructor(private http: HttpClient) {
    if (this.token()) {
      this.carregarPerfil().subscribe({ error: () => this.logout() });
    }
  }

  register(dados: { nome: string; username: string; email: string; password: string; password_confirmation: string }): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.baseUrl}/register`, dados).pipe(
      tap(res => this.guardarSessao(res))
    );
  }

  login(dados: { email: string; password: string }): Observable<AuthResponse> {
    return this.http.post<AuthResponse>(`${this.baseUrl}/login`, dados).pipe(
      tap(res => this.guardarSessao(res))
    );
  }

  logout(): void {
    if (this.token()) {
      this.http.post(`${this.baseUrl}/logout`, {}).subscribe();
    }
    localStorage.removeItem('token');
    this.token.set(null);
    this.utilizadorActual.set(null);
  }

  me(): Observable<{ utilizador: Utilizador }> {
    return this.http.get<{ utilizador: Utilizador }>(`${this.baseUrl}/me`);
  }

  carregarPerfil(): Observable<{ utilizador: Utilizador }> {
    return this.me().pipe(
      tap(res => this.utilizadorActual.set(res.utilizador))
    );
  }

  refresh(): Observable<{ token: string }> {
    return this.http.post<{ token: string }>(`${this.baseUrl}/refresh`, {}).pipe(
      tap(res => {
        localStorage.setItem('token', res.token);
        this.token.set(res.token);
      })
    );
  }

  forgotPassword(email: string): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.baseUrl}/forgot-password`, { email });
  }

  resetPassword(dados: { email: string; token: string; password: string; password_confirmation: string }): Observable<{ message: string }> {
    return this.http.post<{ message: string }>(`${this.baseUrl}/reset-password`, dados);
  }

  atualizarPerfil(dados: Partial<Pick<Utilizador, 'nome' | 'username' | 'bio'>> & { perfil_privado?: boolean }): Observable<{ message: string; utilizador: Utilizador }> {
    return this.http.put<{ message: string; utilizador: Utilizador }>(`${this.baseUrl}/perfil`, dados).pipe(
      tap(res => this.utilizadorActual.set(res.utilizador))
    );
  }

  atualizarFotoPerfil(foto: File): Observable<{ message: string; utilizador: Utilizador }> {
    const formData = new FormData();
    formData.append('foto_perfil', foto);

    return this.http.post<{ message: string; utilizador: Utilizador }>(`${this.baseUrl}/foto-perfil`, formData).pipe(
      tap(res => this.utilizadorActual.set(res.utilizador))
    );
  }

  estaAutenticado(): boolean {
    return !!this.token();
  }

  private guardarSessao(res: AuthResponse): void {
    localStorage.setItem('token', res.token);
    this.token.set(res.token);
    this.utilizadorActual.set(res.utilizador);
  }
}
