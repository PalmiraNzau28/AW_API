import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SidebarComponent, ViewType } from './components/sidebar/sidebar.component';
import { FeedComponent } from './components/feed/feed.component';
import { SearchComponent } from './components/search/search.component';
import { NotificationsComponent } from './components/notifications/notifications.component';
import { ProfileComponent } from './components/profile/profile.component';
import { CreatePostComponent } from './components/create-post/create-post.component';

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
  ],
  templateUrl: './app.html',
  styleUrl: './app.scss'
})
export class App {
  currentView: ViewType = 'feed';
  showCreatePost = false;
  feedReloadTrigger = 0;

  onPublicacaoCriada(): void {
    this.showCreatePost = false;
    this.feedReloadTrigger++;
  }
}
