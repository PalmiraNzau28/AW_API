import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Output } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, LogIn, Mail, Lock, User, UserPlus } from 'lucide-angular';
import { AuthService } from '../../services/auth.service';
import { RippleDirective } from '../ripple/ripple.directive';

type AuthMode = 'login' | 'register' | 'forgot' | 'reset';

@Component({
  selector: 'app-auth',
  standalone: true,
  imports: [CommonModule, FormsModule, LucideAngularModule, RippleDirective],
  templateUrl: './auth.component.html',
  styleUrl: './auth.component.scss',
})
export class AuthComponent {
  @Output() authenticated = new EventEmitter<void>();

  readonly LogInIcon = LogIn;
  readonly MailIcon = Mail;
  readonly LockIcon = Lock;
  readonly UserIcon = User;
  readonly UserPlusIcon = UserPlus;

  mode: AuthMode = 'login';
  loginForm = {
    email: '',
    password: '',
  };
  registerForm = {
    nome: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
  };
  resetForm = {
    email: '',
    token: '',
    password: '',
    password_confirmation: '',
  };
  carregando = false;
  erro: string | null = null;
  sucesso: string | null = null;

  constructor(private authService: AuthService) {
    const params = new URLSearchParams(window.location.search);
    const token = params.get('token');
    const email = params.get('email');

    if (token && email) {
      this.mode = 'reset';
      this.resetForm.token = token;
      this.resetForm.email = email;
      this.loginForm.email = email;
    }
  }

  setMode(mode: AuthMode): void {
    this.mode = mode;
    this.erro = null;
    this.sucesso = null;
  }

  entrar(): void {
    this.carregando = true;
    this.erro = null;
    this.sucesso = null;

    this.authService.login(this.loginForm).subscribe({
      next: () => {
        this.carregando = false;
        this.authenticated.emit();
      },
      error: err => {
        this.carregando = false;
        this.erro = err.error?.message ?? 'Não foi possível iniciar sessão.';
      },
    });
  }

  registar(): void {
    this.carregando = true;
    this.erro = null;
    this.sucesso = null;

    this.authService.register(this.registerForm).subscribe({
      next: () => {
        this.carregando = false;
        this.authenticated.emit();
      },
      error: err => {
        this.carregando = false;
        this.erro = err.error?.message ?? 'Não foi possível criar a conta.';
      },
    });
  }

  recuperarSenha(): void {
    if (!this.loginForm.email.trim()) {
      this.erro = 'Informe o email para recuperar a senha.';
      return;
    }

    this.carregando = true;
    this.erro = null;
    this.sucesso = null;

    this.authService.forgotPassword(this.loginForm.email).subscribe({
      next: res => {
        this.carregando = false;
        this.sucesso = res.message;
      },
      error: err => {
        this.carregando = false;
        this.erro = err.error?.message ?? 'Não foi possível enviar a recuperação.';
      },
    });
  }

  redefinirSenha(): void {
    this.carregando = true;
    this.erro = null;
    this.sucesso = null;

    this.authService.resetPassword(this.resetForm).subscribe({
      next: res => {
        this.carregando = false;
        this.mode = 'login';
        this.sucesso = res.message;
      },
      error: err => {
        this.carregando = false;
        this.erro = err.error?.message ?? 'Não foi possível redefinir a senha.';
      },
    });
  }
}
