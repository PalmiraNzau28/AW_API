import { Component, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { LucideAngularModule, X, ImageIcon } from 'lucide-angular';
import { PublicacaoService } from '../../services/publicacao.service';

@Component({
  selector: 'app-create-post',
  standalone: true,
  imports: [CommonModule, FormsModule, LucideAngularModule],
  templateUrl: './create-post.component.html',
  styleUrl: './create-post.component.scss'
})
export class CreatePostComponent {
  @Output() closeModal = new EventEmitter<void>();
  @Output() publicacaoCriada = new EventEmitter<void>();

  readonly XIcon = X;
  readonly ImageIcon = ImageIcon;

  selectedImage: string | null = null;
  selectedFile: File | null = null;
  legenda = '';
  enviando = false;
  erro: string | null = null;

  constructor(private publicacaoService: PublicacaoService) {}

  handleImageSelect(event: Event): void {
    const file = (event.target as HTMLInputElement).files?.[0];
    if (file) {
      this.selectedFile = file;
      const reader = new FileReader();
      reader.onloadend = () => { this.selectedImage = reader.result as string; };
      reader.readAsDataURL(file);
    }
  }

  handleSubmit(): void {
    if (!this.selectedFile) return;

    const formData = new FormData();
    formData.append('imagem', this.selectedFile);
    if (this.legenda.trim()) {
      formData.append('legenda', this.legenda);
    }

    this.enviando = true;
    this.erro = null;

    this.publicacaoService.criar(formData).subscribe({
      next: () => {
        this.enviando = false;
        this.publicacaoCriada.emit();
        this.closeModal.emit();
      },
      error: err => {
        this.enviando = false;
        this.erro = err.error?.message ?? 'Erro ao criar publicação.';
        console.error(err);
      }
    });
  }
}
