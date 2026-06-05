import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SidebarComponent, ViewType } from './components/sidebar/sidebar.component';
import { FeedComponent } from './components/feed/feed.component';
import { SearchComponent } from './components/search/search.component';
import { NotificationsComponent } from './components/notifications/notifications.component';
import { ProfileComponent } from './components/profile/profile.component';
import { CreatePostComponent } from './components/create-post/create-post.component';
import { AuthComponent } from './components/auth/auth.component';
import { AuthService } from './services/auth.service';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [
    CommonModule,
    SidebarComponent,
    FeedComponent,
    SearchComponent,
    NotificationsComponent,
    ProfileComponent,
    CreatePostComponent,
    AuthComponent,
  ],
  templateUrl: './app.html',
  styleUrl: './app.scss'
})
export class App {
  currentView: ViewType = 'feed';
  showCreatePost = false;
  feedReloadTrigger = 0;

  constructor(public authService: AuthService) {}

  get autenticado(): boolean {
    return this.authService.estaAutenticado();
  }

  onPublicacaoCriada(): void {
    this.showCreatePost = false;
    this.feedReloadTrigger++;
  }

  onAuthenticated(): void {
    this.currentView = 'feed';
    this.showCreatePost = false;
  }

  logout(): void {
    this.authService.logout();
    this.currentView = 'feed';
    this.showCreatePost = false;
  }
}
