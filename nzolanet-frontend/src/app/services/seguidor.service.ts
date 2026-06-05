import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface SeguidorResponse {
  message: string;
}

@Injectable({ providedIn: 'root' })
export class SeguidorService {
  constructor(private http: HttpClient) {}

  seguir(utilizadorId: number): Observable<SeguidorResponse> {
    return this.http.post<SeguidorResponse>(`${environment.apiUrl}/utilizadores/${utilizadorId}/seguir`, {});
  }

  deixarSeguir(utilizadorId: number): Observable<SeguidorResponse> {
    return this.http.delete<SeguidorResponse>(`${environment.apiUrl}/utilizadores/${utilizadorId}/seguir`);
  }
}
