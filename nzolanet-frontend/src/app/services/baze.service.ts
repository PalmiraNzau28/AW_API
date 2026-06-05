import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface BazeResponse {
  message: string;
  bazado: boolean;
  bazes_count: number;
}

@Injectable({ providedIn: 'root' })
export class BazeService {
  constructor(private http: HttpClient) {}

  toggle(publicacaoId: number): Observable<BazeResponse> {
    return this.http.post<BazeResponse>(`${environment.apiUrl}/publicacoes/${publicacaoId}/baze`, {});
  }
}
