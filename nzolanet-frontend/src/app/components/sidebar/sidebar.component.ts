import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { LucideAngularModule, Home, Search, Bell, User, PlusSquare, LogOut } from 'lucide-angular';
import { RippleDirective } from '../ripple/ripple.directive';

export type ViewType = 'feed' | 'search' | 'notifications' | 'profile';

@Component({
  selector: 'app-sidebar',
  standalone: true,
  imports: [CommonModule, LucideAngularModule, RippleDirective],
  templateUrl: './sidebar.component.html',
  styleUrl: './sidebar.component.scss'
})
export class SidebarComponent {
  @Input() currentView: ViewType = 'feed';
  @Output() viewChange = new EventEmitter<ViewType>();
  @Output() createPost = new EventEmitter<void>();
  @Output() logout = new EventEmitter<void>();

  readonly HomeIcon = Home;
  readonly SearchIcon = Search;
  readonly BellIcon = Bell;
  readonly UserIcon = User;
  readonly PlusSquareIcon = PlusSquare;
  readonly LogoutIcon = LogOut;

  menuItems = [
    { id: 'feed' as ViewType, iconName: 'home', label: 'Feed' },
    { id: 'search' as ViewType, iconName: 'search', label: 'Pesquisar' },
    { id: 'notifications' as ViewType, iconName: 'bell', label: 'Notificações' },
    { id: 'profile' as ViewType, iconName: 'user', label: 'Perfil' },
  ];

  getIcon(name: string) {
    const map: Record<string, any> = {
      home: Home, search: Search, bell: Bell, user: User
    };
    return map[name];
  }
}
